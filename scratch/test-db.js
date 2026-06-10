import { query, transaction } from '../backend/src/config/db.js';

async function run() {
  console.log("Testing Mock DB");

  const studentId = 1001;

  await transaction(async (connection) => {
    await connection.execute(`INSERT INTO student (student_id) VALUES (:studentId)`, { studentId });
  });

  const rows = await query(`SELECT * FROM student WHERE student_id = :studentId`, { studentId });
  console.log("Rows found:", rows);
}

run().catch(console.error);
