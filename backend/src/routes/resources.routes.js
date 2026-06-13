import { Router } from 'express';
import { listSchema } from '@mmyvv/shared';
import { validate } from '../middleware/validate.js';
import { query } from '../config/db.js';
import { requireAuth, requireRole } from '../middleware/auth.js';
import { assertAllowedTable, coerceIdentifier } from '../utils/tables.js';

const router = Router();

async function columnsFor(table) {
  assertAllowedTable(table);
  const rows = await query(
    `SELECT COLUMN_NAME AS name, DATA_TYPE AS type, COLUMN_KEY AS columnKey, EXTRA AS extra
     FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table
     ORDER BY ORDINAL_POSITION`,
    { table }
  );
  return rows;
}

function primaryKeyColumn(columns) {
  const pk = columns.find((c) => c.columnKey === 'PRI');
  return pk ? pk.name : 'id';
}

router.get('/tables', requireAuth, async (req, res, next) => {
  try {
    const tables = await query(
      `SELECT TABLE_NAME AS name
       FROM INFORMATION_SCHEMA.TABLES
       WHERE TABLE_SCHEMA = DATABASE()
       ORDER BY TABLE_NAME`
    );
    res.json({ tables: tables.filter((table) => table.name && table.name.length) });
  } catch (error) {
    next(error);
  }
});

router.get('/:table/schema', requireAuth, async (req, res, next) => {
  try {
    const columns = await columnsFor(req.params.table);
    res.json({ table: req.params.table, columns });
  } catch (error) {
    next(error);
  }
});

// router.get('/:table', requireAuth, validate(listSchema, 'query'), async (req, res, next) => {
//   try {
//     const table = req.params.table;
//     assertAllowedTable(table);
//     const parsed = req.query;
//     const columns = await columnsFor(table);
//     const searchable = columns
//       .filter((column) => ['varchar', 'text', 'char', 'longtext', 'mediumtext'].includes(column.type))
//       .slice(0, 8);

//     const tableSql = coerceIdentifier(table);
//     // Ensure the requested sort column exists on the table; otherwise fall back to primary key
//     const columnNames = columns.map((c) => c.name);
//     const sortColumn = columnNames.includes(parsed.sortBy) ? parsed.sortBy : primaryKeyColumn(columns) || columnNames[0];
//     const sortSql = coerceIdentifier(sortColumn);
//     const offset = (parsed.page - 1) * parsed.limit;
//     const whereParts = [];
//     const params = { limit: parsed.limit, offset };

//     if (parsed.q && searchable.length) {
//       searchable.forEach((column, index) => {
//         whereParts.push(`${coerceIdentifier(column.name)} LIKE :q${index}`);
//         params[`q${index}`] = `%${parsed.q}%`;
//       });
//     }

//     const where = whereParts.length ? `WHERE (${whereParts.join(' OR ')})` : '';
//     const rows = await query(
//       `SELECT * FROM ${tableSql} ${where} ORDER BY ${sortSql} ${parsed.sortDir.toUpperCase()} LIMIT :limit OFFSET :offset`,
//       params
//     );
//     const countRows = await query(`SELECT COUNT(*) AS total FROM ${tableSql} ${where}`, params);

//     res.json({
//       data: rows,
//       columns,
//       pagination: {
//         page: parsed.page,
//         limit: parsed.limit,
//         total: Number(countRows[0]?.total || 0)
//       }
//     });
//   } catch (error) {
//     next(error);
//   }
// });

router.get(
  '/:table',
  requireAuth,
  validate(listSchema, 'query'),
  async (req, res, next) => {
    try {
      const table = req.params.table;

      assertAllowedTable(table);

      const parsed = req.query;
      const columns = await columnsFor(table);

      const searchable = columns
        .filter((column) =>
          ['varchar', 'text', 'char', 'longtext', 'mediumtext'].includes(
            String(column.type).toLowerCase()
          )
        )
        .slice(0, 8);

      const tableSql = coerceIdentifier(table);

      // Find valid sort column
      const columnNames = columns.map((c) => c.name);

      const sortColumn = columnNames.includes(parsed.sortBy)
        ? parsed.sortBy
        : primaryKeyColumn(columns) || columnNames[0];

      const sortSql = coerceIdentifier(sortColumn);

      const page = Number(parsed.page || 1);
      const limit = Number(parsed.limit || 25);
      const offset = (page - 1) * limit;

      const whereParts = [];
      const searchParams = {};

      // Search query
      if (parsed.q && searchable.length) {
        searchable.forEach((column, index) => {
          whereParts.push(
            `${coerceIdentifier(column.name)} LIKE :q${index}`
          );

          searchParams[`q${index}`] = `%${parsed.q}%`;
        });
      }

      const where =
        whereParts.length > 0
          ? `WHERE (${whereParts.join(' OR ')})`
          : '';

      const dataSql = `
        SELECT *
        FROM ${tableSql}
        ${where}
        ORDER BY ${sortSql} ${String(parsed.sortDir || 'ASC').toUpperCase()}
        LIMIT ${limit}
        OFFSET ${offset}
      `;

      const countSql = `
        SELECT COUNT(*) AS total
        FROM ${tableSql}
        ${where}
      `;

      const rows = await query(dataSql, searchParams);

      const countRows = await query(countSql, searchParams);

      res.json({
        data: rows,
        columns,
        pagination: {
          page,
          limit,
          total: Number(countRows?.[0]?.total || 0)
        }
      });
    } catch (error) {
      console.error('Resource List Error:', error);
      next(error);
    }
  }
);

router.get('/:table/:id', requireAuth, async (req, res, next) => {
  try {
    const table = req.params.table;
    assertAllowedTable(table);
    const columns = await columnsFor(table);
    const pk = primaryKeyColumn(columns);
    const rows = await query(`SELECT * FROM ${coerceIdentifier(table)} WHERE ${coerceIdentifier(pk)} = :id LIMIT 1`, { id: req.params.id });
    if (!rows[0]) return res.status(404).json({ message: 'Record not found' });
    res.json({ data: rows[0] });
  } catch (error) {
    next(error);
  }
});

router.post('/:table', requireAuth, requireRole('admin'), async (req, res, next) => {
  try {
    const table = req.params.table;
    assertAllowedTable(table);
    const columns = await columnsFor(table);
    const writable = columns
      .filter((column) => column.extra !== 'auto_increment' && Object.hasOwn(req.body, column.name))
      .map((column) => column.name);

    if (!writable.length) return res.status(400).json({ message: 'No writable fields supplied' });

    const fieldSql = writable.map(coerceIdentifier).join(', ');
    const valueSql = writable.map((column) => `:${column}`).join(', ');
    await query(`INSERT INTO ${coerceIdentifier(table)} (${fieldSql}) VALUES (${valueSql})`, req.body);
    res.status(201).json({ message: 'Record created' });
  } catch (error) {
    next(error);
  }
});

router.put('/:table/:id', requireAuth, requireRole('admin'), async (req, res, next) => {
  try {
    const table = req.params.table;
    assertAllowedTable(table);
    const columns = await columnsFor(table);
    const pk = primaryKeyColumn(columns);
    const writable = columns
      .filter((column) => column.name !== pk && column.extra !== 'auto_increment' && Object.hasOwn(req.body, column.name))
      .map((column) => column.name);

    if (!writable.length) return res.status(400).json({ message: 'No writable fields supplied' });

    const setSql = writable.map((column) => `${coerceIdentifier(column)} = :${column}`).join(', ');
    await query(`UPDATE ${coerceIdentifier(table)} SET ${setSql} WHERE ${coerceIdentifier(pk)} = :id`, { ...req.body, id: req.params.id });
    res.json({ message: 'Record updated' });
  } catch (error) {
    next(error);
  }
});

router.delete('/:table/:id', requireAuth, requireRole('admin'), async (req, res, next) => {
  try {
    const table = req.params.table;
    assertAllowedTable(table);
    const cols = await columnsFor(table);
    const pkCol = primaryKeyColumn(cols);
    await query(`DELETE FROM ${coerceIdentifier(table)} WHERE ${coerceIdentifier(pkCol)} = :id`, { id: req.params.id });
    res.json({ message: 'Record deleted' });
  } catch (error) {
    next(error);
  }
});

export default router;
