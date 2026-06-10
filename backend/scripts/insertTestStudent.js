#!/usr/bin/env node
import { query } from '../src/config/db.js';

const [,, enrollment = 'ENR001', dob = '1990-01-01', ...nameParts] = process.argv;
const name = nameParts.length ? nameParts.join(' ') : 'Test Student';

if (!enrollment || !dob) {
  console.error('Usage: node backend/scripts/insertTestStudent.js <ENROLLMENT_NO> <YYYY-MM-DD> [Full Name]');
  process.exit(1);
}

(async () => {
  try {
    const sql = `INSERT INTO student (name, student_name, enrollment_no, dob, admission_by, status, created_at)
      VALUES (:name, :student_name, :enrollment_no, :dob, :admission_by, :status, NOW())`;

    const params = {
      name,
      student_name: name,
      enrollment_no: enrollment,
      dob,
      admission_by: 'script',
      status: 'Y'
    };

    const result = await query(sql, params);
    console.log('Insert OK. Result:', result);
    console.log('You can now login at /login with Enrollment No:', enrollment, 'and DOB:', dob);
  } catch (err) {
    console.error('Insert failed:', err.message);
    console.error('If this fails, run `node backend/scripts/describeStudent.js` to inspect required columns.');
    process.exit(1);
  }
})();
