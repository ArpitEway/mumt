-- Replace ENR001 and 1990-01-01 with desired values and run in your MySQL client
INSERT INTO student (`name`, student_name, enrollment_no, dob, admission_by, status, created_at)
VALUES ('Test Student', 'Test Student', 'ENR001', '1990-01-01', 'script', 'Y', NOW());

-- Verify
SELECT id, enrollment_no, name, dob FROM student WHERE enrollment_no = 'ENR001';
