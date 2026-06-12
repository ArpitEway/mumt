import { Router } from 'express';
import { query, transaction } from '../config/db.js';
import { registerSchema } from '@mmyvv/shared';
import { validate } from '../middleware/validate.js';

const router = Router();
const ADMISSION_SESSION = '2026';

function badRequest(message) {
  const error = new Error(message);
  error.status = 400;
  return error;
}

function normalizeMobile(mobile) {
  return String(mobile || '').replace(/\D/g, '');
}

function normalizeCategory(value) {
  const text = String(value || '').toLowerCase();
  if (text.includes('diploma') || text.includes('पत्रोपाधि')) return 'Diploma';
  if (text.includes('pg') || text.includes('post') || text.includes('स्नातकोत्तर') || text.includes('आचार्य')) return 'PG';
  return 'UG';
}

async function getCourseGroupMetadata() {
  const groupColumns = await query(
    `SELECT COLUMN_NAME AS name
     FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table`,
    { table: 'course_group' }
  );
  const columnNames = new Set(groupColumns.map((column) => column.name));
  const nameField = columnNames.has('course_name') ? 'course_name' : columnNames.has('name') ? 'name' : null;
  const nameEnField = columnNames.has('course_name_en') ? 'course_name_en' : nameField;
  const eligibilityField = columnNames.has('eligibility')
    ? 'eligibility'
    : columnNames.has('eligibility_criteria')
      ? 'eligibility_criteria'
      : columnNames.has('course_eligibility')
        ? 'course_eligibility'
        : null;
  const categoryField = columnNames.has('category') ? 'category' : columnNames.has('course_type') ? 'course_type' : null;
  const statusField = columnNames.has('status') ? 'status' : null;
  const permissionField = columnNames.has('admission_permission') ? 'admission_permission' : null;

  return {
    nameField,
    nameEnField,
    eligibilityField,
    categoryField,
    statusField,
    permissionField
  };
}

async function getCourseGroups() {
  const metadata = await getCourseGroupMetadata();
  const { nameField, nameEnField, eligibilityField, categoryField, statusField, permissionField } = metadata;
  if (!nameField) return [];

  const nameEnSql = nameEnField || nameField;
  const categorySql = eligibilityField
    ? eligibilityField
    : categoryField
      ? categoryField
      : `'General'`;
  const statusWhere = statusField ? `AND ${statusField} IN ('A', 'Y', 'yes')` : '';
  const permissionWhere = permissionField ? `AND ${permissionField} = 'Y'` : '';

  const rows = await query(
    `SELECT id AS id, id AS source_id ,${nameField} AS course_name,
            ${nameEnSql} AS course_name_en, ${categorySql} AS category,${eligibilityField} AS eligibility, mode, 'course_group' AS source
     FROM course_group
     WHERE 1 = 1 ${permissionWhere} ${statusWhere}
     ORDER BY category, course_name`
  );

  return rows;
}

router.get('/options', async (req, res, next) => {
  try {
    const rows = await getCourseGroups();
    console.log('Fetched course groups:', rows);
    const eligibilityMap = new Map();

    rows.forEach((row) => {
      const eligibility = String(row.eligibility).trim();
      if (!eligibilityMap.has(eligibility)) {
        eligibilityMap.set(eligibility, { name: eligibility, courses: [] });
      }
      eligibilityMap.get(eligibility).courses.push({
        id: row.id,
        sourceId: row.source_id,
        source: row.source,
        name: row.course_name,
        nameEn: row.course_name_en,
        mode: row.mode
      });
    });

    res.json({
      session: ADMISSION_SESSION,
      eligibilities: Array.from(eligibilityMap.values())
    });
  } catch (error) {
    next(error);
  }
});

// Public registration endpoint. Inserts a student record and returns the new studentId.
router.post('/', validate(registerSchema), async (req, res, next) => {
  try {
    
    const { fullName, dob, mobile, fatherName, email, mode, category, course } = req.body;
    const cleanMobile = normalizeMobile(mobile);

    const duplicateRows = await query(
      `SELECT student_id FROM student_data WHERE p_mobile_no = :mobile LIMIT 1`,
      { mobile: cleanMobile }
    );

    if (duplicateRows.length) {
      throw badRequest('Mobile number already exists');
    }

    const selectedCourseId = Number(course);
    if (!selectedCourseId) {
      throw badRequest('Please select a course');
    }

    const metadata = await getCourseGroupMetadata();
    const { nameField, nameEnField, eligibilityField, categoryField, statusField, permissionField } = metadata;
    if (!nameField) {
      throw badRequest('The course group table is not configured properly');
    }

    const nameEnSql = nameEnField || nameField;
    const categorySql = eligibilityField
      ? `${eligibilityField} AS category`
      : categoryField
        ? `${categoryField} AS category`
        : `'General' AS category`;
    const statusWhere = statusField ? `AND ${statusField} IN ('A', 'Y', 'yes')` : '';
    const permissionWhere = permissionField ? `AND ${permissionField} = 'Y'` : '';

    const courseRows = await query(
      `SELECT id, id AS course_group_id, ${nameField} AS course_name, ${categorySql}, mode
       FROM course_group
       WHERE id = :selectedCourseId ${permissionWhere} ${statusWhere}
       LIMIT 1`,
      { selectedCourseId }
    );
    const selectedCourse = courseRows[0];
    if (!selectedCourse) {
      throw badRequest('Selected course is not available for admission');
    }
    if (category && normalizeCategory(selectedCourse.category) !== normalizeCategory(category)) {
      throw badRequest('Selected course does not belong to the selected category');
    }

    const admissionMode = mode === 'Regular' ? 'REG' : (selectedCourse.mode || mode || 'REG');
    const courseGroupId = Number(selectedCourse.course_group_id || 0);
    const courseCategory = normalizeCategory(selectedCourse.category || category);
    const courseName = selectedCourse.course_name || '';

    const result = await transaction(async (connection) => {
      const [nextStudentRows] = await connection.execute(
        `SELECT COALESCE(MAX(student_id), 1000) + 1 AS nextStudentId
         FROM student
         WHERE student_id >= 1001 AND student_id < 1000000
         FOR UPDATE`
      );
      const studentId = Number(nextStudentRows[0]?.nextStudentId || 1001);

      await connection.execute(
        `INSERT INTO student (
          student_id, user_id, course_group_id, enrollment_no, abc_id, course_name, class_name, session,
          name, name_hindi, f_h_name_hindi, class_id, old_class_id, institute_id, institute_code,
          f_h_name, mother_name, mother_name_hindi, dob, gender, permanent_resident, category,
          course_before_phd, photo, eligibility, approved_by, roll_number, marksheet_no,
          notification_no, group_id, exam_year, adhar_no, adhar_name, adhar_dob, sm_id,
          course_category, course_duration, mode, form_status, approved, document_uploaded,
          payment_status, enrolled, new_exam_form, late_exam_form, old_enrollment, degree, MP, IV_YEAR
        ) VALUES (
          :studentId, 0, :courseGroupId, '-', '', :courseName, '', :session,
          :name, '', '', 0, 0, 0, '',
          :fatherName, '', '', :dob, '', '', '',
          '', '', '', '', '', '',
          0, '', :session, '', '', '', '',
          :courseCategory, '', :mode, 'N', 'N', 'N',
          'N', 'N', 'N', 'N', '', '', '', ''
        )`,
        {
          studentId,
          courseGroupId,
          courseName,
          session: ADMISSION_SESSION,
          name: fullName,
          fatherName: fatherName || '',
          dob,
          courseCategory,
          mode: admissionMode
        }
      );

      await connection.execute(
        `INSERT INTO student_data (
          student_id, nationality, religion, f_h_occupation, mother_occupation,
          c_address, c_city, c_district, c_state, c_pin_code, f_h_mobile_no,
          p_address, p_city, p_district, p_state, p_pin_code, p_mobile_no, p_email,
          eligibility, ten_board, twowelth_board, twowelth_total_marks, twowelth_marks,
          twowelth_subject, twowelth_year, graduation_university, graduation_total_marks,
          graduation_marks, graduation_subject, graduation_year, pg_marks, pg_total_marks,
          pg_year, pg_subject, pg_university, blood_group, guardian_mob, samagra_id
        ) VALUES (
          :studentId, '', '', '', '',
          '', '', '', '', 0, '',
          '', '', '', '', 0, :mobile, :email,
          '', '', '', '', '',
          '', '', '', '',
          '', '', '', '', '',
          '', '', '', '', '', ''
        )`,
        {
          studentId,
          mobile: cleanMobile,
          email
        }
      );

      return { studentId, enrollmentNo: '-' };
    });

    res.status(201).json(result);
  } catch (error) {
    next(error);
  }
});

export default router;
