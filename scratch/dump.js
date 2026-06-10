import { query } from '../backend/src/config/db.js';

async function run() {
  const rows = await query(
    `SELECT 
        s.student_id,
        s.name,
        s.f_h_name,
        s.enrollment_no,
        s.dob,
        s.portal_charges,
        s.program_fees,
        sd.p_mobile_no AS mobile,
        sd.p_email AS email
     FROM student s
     LEFT JOIN student_data sd
     ON s.student_id = sd.student_id
     WHERE s.student_id = :studentId
     LIMIT 1`,
    { studentId: "1001" }
  );
  console.log("Joined Rows:", rows);

  process.exit(0);
}

run().catch(console.error);
