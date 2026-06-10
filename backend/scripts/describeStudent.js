import { query } from '../src/config/db.js';

(async () => {
  try {
    const sql = `SELECT COLUMN_NAME, COLUMN_TYPE, COLUMN_KEY, EXTRA
      FROM INFORMATION_SCHEMA.COLUMNS
      WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME='student'`;
    const rows = await query(sql);
    console.log(JSON.stringify(rows, null, 2));
  } catch (err) {
    console.error('Error:', err.message || err);
    process.exit(1);
  }
})();
