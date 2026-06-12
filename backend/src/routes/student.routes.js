import { Router } from 'express';
import fs from 'fs';
import path from 'path';
import { fileURLToPath } from 'url';
import multer from 'multer';
import { studentFormSchema } from '@mmyvv/shared';
import { decodeId } from '@mmyvv/shared/idEncryption';
import { validate } from '../middleware/validate.js';
import { query, transaction } from '../config/db.js';
import { requireAuth } from '../middleware/auth.js';

const router = Router();
const __dirname = path.dirname(fileURLToPath(import.meta.url));
const uploadRoot = path.resolve(__dirname, '../../uploads/admission-documents');
const photoUploadRoot = path.resolve(__dirname, '../../uploads/student-photos');
fs.mkdirSync(uploadRoot, { recursive: true });
fs.mkdirSync(photoUploadRoot, { recursive: true });

const upload = multer({
  storage: multer.diskStorage({
    destination: (req, file, cb) => cb(null, uploadRoot),
    filename: (req, file, cb) => {
      const safeBase = path.basename(file.originalname).replace(/[^a-zA-Z0-9._-]/g, '_');
      const studentId = req.studentId || req.params.studentId;
      cb(null, `${studentId}-${Date.now()}-${safeBase}`);
    }
  })
});

const photoUpload = multer({
  storage: multer.diskStorage({
    destination: (req, file, cb) => cb(null, photoUploadRoot),
    filename: (req, file, cb) => {
      const extension = path.extname(file.originalname || '') || '.jpg';
      const studentId = req.studentId || req.params.studentId;
      cb(null, `${studentId}-${Date.now()}${extension}`);
    }
  })
});

function normalizeStudentId(rawId) {
  return decodeId(rawId);
}

router.param('studentId', (req, res, next, rawId) => {
  const studentId = normalizeStudentId(rawId);
  if (!studentId) {
    return res.status(400).json({ message: 'Invalid student id' });
  }
  req.studentId = studentId;
  next();
});

const studentFormGroups = [
  {
    table: 'student',
    title: 'Admission Details',
    fields: [
      { name: 'session', label: 'Session', table: 'student' },
      { name: 'course_category', label: 'Course Category', table: 'student' },
      { name: 'course_name', label: 'Course Name', table: 'student' },
       { name: 'course_group_id', label: 'Course Group ID', table: 'student' },
      { name: 'class_name', label: 'Class Name', table: 'student' },
      { name: 'course_duration', label: 'Course Duration', table: 'student' },
      { name: 'mode', label: 'Mode', table: 'student' },
      { name: 'eligibility', label: 'Eligibility', table: 'student' },
      { name: 'exam_year', label: 'Exam Year', table: 'student' }
    ]
  },
  {
    table: 'student',
    title: 'Personal Details',
    fields: [
      { name: 'name', label: 'Name', table: 'student' },
      { name: 'name_hindi', label: 'Name in Hindi', table: 'student' },
      { name: 'f_h_name', label: 'Father / Husband Name', table: 'student' },
      { name: 'f_h_name_hindi', label: 'Father / Husband Name in Hindi', table: 'student' },
      { name: 'mother_name', label: 'Mother Name', table: 'student' },
      { name: 'mother_name_hindi', label: 'Mother Name in Hindi', table: 'student' },
      { name: 'gender', label: 'Gender', table: 'student' },
      { name: 'dob', label: 'Date of Birth', type: 'date', table: 'student' },
      { name: 'category', label: 'Category', table: 'student' },
      { name: 'permanent_resident', label: 'Permanent Resident', table: 'student' },
      { name: 'adhar_no', label: 'Aadhaar No', table: 'student' },
      { name: 'adhar_name', label: 'Aadhaar Name', table: 'student' },
      { name: 'adhar_dob', label: 'Aadhaar DOB', type: 'date', table: 'student' },
      { name: 'aadhaar_verifed', label: 'Aadhaar Verified', table: 'student' },
      { name: 'sm_id', label: 'SM ID', table: 'student' },
      { name: 'p_mobile_no', label: 'Mobile Number', type: 'tel', table: 'student_data' },
      { name: 'p_email', label: 'Email', type: 'email', table: 'student_data' }
    ]
  },
  {
    table: 'student_data',
    title: 'Contact Details',
    fields: [
      { name: 'nationality', label: 'Nationality', table: 'student_data' },
      { name: 'religion', label: 'Religion', table: 'student_data' },
      { name: 'f_h_occupation', label: 'Father / Husband Occupation', table: 'student_data' },
      { name: 'mother_occupation', label: 'Mother Occupation', table: 'student_data' },
      { name: 'p_handicapped', label: 'Person With Disability', table: 'student_data' },
      { name: 'c_address', label: 'Address', table: 'student_data' },
      { name: 'c_city', label: 'City', table: 'student_data' },
      { name: 'c_district', label: 'District', table: 'student_data' },
      { name: 'c_state', label: 'State', table: 'student_data' },
      { name: 'c_pin_code', label: 'Pin Code', table: 'student_data' }
    ]
  },
  {
    table: 'student_data',
    title: 'Qualification Details',
    fields: [
      { name: 'f_h_mobile_no', label: 'Father / Husband Mobile', table: 'student_data' },
      { name: 'p_address', label: 'Permanent Address', table: 'student_data' },
      { name: 'p_city', label: 'Permanent City', table: 'student_data' },
      { name: 'p_district', label: 'Permanent District', table: 'student_data' },
      { name: 'p_state', label: 'Permanent State', table: 'student_data' },
      { name: 'p_pin_code', label: 'Permanent Pin Code', table: 'student_data' },
      { name: 'ten_board', label: '10th Board', table: 'student_data' },
      { name: 'ten_total_marks', label: '10th Total Marks', table: 'student_data' },
      { name: 'ten_marks', label: '10th Obtained Marks', table: 'student_data' },
      { name: 'ten_subjects', label: '10th Subjects', table: 'student_data' },
      { name: 'ten_year', label: '10th Year', table: 'student_data' },
      { name: 'twowelth_board', label: '12th Board', table: 'student_data' },
      { name: 'twowelth_total_marks', label: '12th Total Marks', table: 'student_data' },
      { name: 'twowelth_marks', label: '12th Obtained Marks', table: 'student_data' },
      { name: 'twowelth_subject', label: '12th Subject', table: 'student_data' },
      { name: 'twowelth_year', label: '12th Year', table: 'student_data' },
      { name: 'graduation_university', label: 'Graduation University', table: 'student_data' },
      { name: 'graduation_total_marks', label: 'Graduation Total Marks', table: 'student_data' },
      { name: 'graduation_marks', label: 'Graduation Obtained Marks', table: 'student_data' },
      { name: 'graduation_subject', label: 'Graduation Subject', table: 'student_data' },
      { name: 'graduation_year', label: 'Graduation Year', table: 'student_data' },
      { name: 'pg_university', label: 'PG University', table: 'student_data' },
      { name: 'pg_total_marks', label: 'PG Total Marks', table: 'student_data' },
      { name: 'pg_marks', label: 'PG Obtained Marks', table: 'student_data' },
      { name: 'pg_subject', label: 'PG Subject', table: 'student_data' },
      { name: 'pg_year', label: 'PG Year', table: 'student_data' },
      { name: 'blood_group', label: 'Blood Group', table: 'student_data' },
      { name: 'guardian_mob', label: 'Guardian Mobile', table: 'student_data' },
      { name: 'samagra_id', label: 'Samagra ID', table: 'student_data' }
    ]
  }
];

const studentHiddenFields = new Set([
  'student_id',
  'user_id',
  'course_group_id',
  'enrollment_no',
  'abc_id',
  'class_id',
  'old_class_id',
  'institute_id',
  'institute_code',
  'exam_center_id',
  'remark',
  'remark_detail',
  'exam_form',
  'photo',
  'form_status',
  'approved',
  'approved_by',
  'cur_time',
  'enrolled',
  'roll_number',
  'roll_no',
  'result_show',
  'marksheet_no',
  'int_marks_sub',
  'p_marks_sub',
  'notification_no',
  'temp_exam_form',
  'course_complete',
  'group_id',
  'demo',
  'upload_result',
  'new_exam_form',
  'late_exam_form',
  'document_uploaded',
  'payment_status',
  'installment_permission',
  'program_fees',
  'cbcs',
  'exam_pattern',
  'other_exam_certificate',
  'old_enrollment',
  'old_upload_result',
  'promote',
  'roll_no_permission',
  'pass_in_eaxm',
  'id_card',
  'portal_charges',
  'degree',
  'BPL',
  'MP',
  'IV_YEAR',
  'id',
  'password'
]);

async function columnsFor(table) {
  return query(
    `SELECT COLUMN_NAME AS name, DATA_TYPE AS type, COLUMN_KEY AS columnKey, EXTRA AS extra
     FROM INFORMATION_SCHEMA.COLUMNS
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :table
     ORDER BY ORDINAL_POSITION`,
    { table }
  );
}

function fieldLabel(name) {
  return name
    .replace(/_/g, ' ')
    .replace(/\b\w/g, (letter) => letter.toUpperCase());
}

function inputType(type, name) {
  if (name.includes('email')) return 'email';
  if (name.includes('mobile') || name.includes('phone') || name.includes('pin')) return 'tel';
  if (type === 'date' || name === 'dob') return 'date';
  if (['int', 'decimal', 'float', 'double'].includes(type)) return 'number';
  return 'text';
}

async function profileFor(studentId) {
  const rows = await query(
    `SELECT s.*, sd.nationality, sd.religion, sd.f_h_occupation, sd.mother_occupation, sd.p_handicapped,
            sd.c_address, sd.c_city, sd.c_district, sd.c_state, sd.c_pin_code, sd.f_h_mobile_no,
            sd.p_address, sd.p_city, sd.p_district, sd.p_state, sd.p_pin_code, sd.p_mobile_no,
            sd.p_email, sd.ten_board, sd.ten_total_marks, sd.ten_marks, sd.ten_subjects, sd.ten_year,
            sd.twowelth_board, sd.twowelth_total_marks, sd.twowelth_marks, sd.twowelth_subject, sd.twowelth_year,
            sd.graduation_university, sd.graduation_total_marks, sd.graduation_marks, sd.graduation_subject,
            sd.graduation_year, sd.pg_marks, sd.pg_total_marks, sd.pg_year, sd.pg_subject, sd.pg_university,
            sd.blood_group, sd.guardian_mob, sd.samagra_id
     FROM student s
     LEFT JOIN student_data sd ON s.student_id = sd.student_id
     WHERE s.student_id = :studentId
     LIMIT 1`,
    { studentId }
  );
  return rows[0];
}

router.get('/:studentId/dashboard', async (req, res, next) => {
  try {
    const student = await profileFor(req.studentId);
    if (!student) return res.status(404).json({ message: 'Student not found' });
    res.json({
      data: {
        student_id: student.student_id,
        name: student.name,
        enrollment_no: student.enrollment_no || '-',
        course_name: student.course_name,
        form_status: student.form_status || 'N',
        payment_status: student.payment_status || 'N',
        document_uploaded: student.document_uploaded || 'N'
      }
    });
  } catch (error) {
    next(error);
  }
});

router.get('/:studentId/form', async (req, res, next) => {
  try {
    const student = await profileFor(req.studentId);
    if (!student) return res.status(404).json({ message: 'Student not found' });

    const fieldGroups = studentFormGroups.map((group) => ({
      table: group.table,
      title: group.title,
      fields: group.fields.map((field) => ({
        name: field.name,
        table: field.table || group.table,
        label: field.label || fieldLabel(field.name),
        type: field.type || inputType('varchar', field.name),
        value: student[field.name] ?? ''
      }))
    }));

    res.json({
      canEdit: (student.form_status || 'N') === 'N',
      formStatus: student.form_status || 'N',
      reviewMode: (student.form_status || 'N') !== 'N',
      fieldGroups,
      photo: student.photo || ''
    });
  } catch (error) {
    next(error);
  }
});

router.put('/:studentId/form', validate(studentFormSchema), async (req, res, next) => {
  try {
    const studentId = req.studentId;
    const student = await profileFor(studentId);
    if (!student) return res.status(404).json({ message: 'Student not found' });
    if ((student.form_status || 'N') !== 'N') {
      return res.status(409).json({ message: 'Registration form is already submitted' });
    }

    const studentAllowed = new Set(
      studentFormGroups
        .filter((group) => group.table === 'student')
        .flatMap((group) => group.fields.map((field) => field.name))
    );
    const dataAllowed = new Set(
      studentFormGroups
        .filter((group) => group.table === 'student_data')
        .flatMap((group) => group.fields.map((field) => field.name))
    );
    const studentPayload = req.body.student || {};
    const dataPayload = req.body.student_data || {};

    const studentWritable = Object.keys(studentPayload)
      .filter((key) => studentAllowed.has(key) && key !== 'student_id' && !studentHiddenFields.has(key));
    const dataWritable = Object.keys(dataPayload)
      .filter((key) => dataAllowed.has(key) && !['id', 'student_id', 'password'].includes(key));

    await transaction(async (connection) => {
      if (studentWritable.length) {
        const setSql = studentWritable.map((key) => `${key} = :student_${key}`).join(', ');
        const params = { studentId };
        studentWritable.forEach((key) => {
          params[`student_${key}`] = studentPayload[key] ?? '';
        });
        await connection.execute(`UPDATE student SET ${setSql} WHERE student_id = :studentId`, params);
      }

      if (dataWritable.length) {
        const setSql = dataWritable.map((key) => `${key} = :data_${key}`).join(', ');
        const params = { studentId };
        dataWritable.forEach((key) => {
          params[`data_${key}`] = dataPayload[key] ?? '';
        });
        await connection.execute(`UPDATE student_data SET ${setSql} WHERE student_id = :studentId`, params);
      }

      await connection.execute(
        `UPDATE student
         SET form_status = 'Y'
         WHERE student_id = :studentId`,
        { studentId }
      );
    });

    res.json({ message: 'Registration form submitted', formStatus: 'Y' });
  } catch (error) {
    next(error);
  }
});

router.post('/:studentId/photo', photoUpload.single('photo'), async (req, res, next) => {
  try {
    if (!req.file) return res.status(400).json({ message: 'Photo file is required' });

    const studentId = req.studentId;
    const student = await profileFor(studentId);
    if (!student) return res.status(404).json({ message: 'Student not found' });

    const photoPath = `/uploads/student-photos/${req.file.filename}`;

    await query(
      `UPDATE student
       SET photo = :photo
       WHERE student_id = :studentId`,
      { studentId, photo: photoPath }
    );

    res.status(201).json({ message: 'Photograph uploaded', photo: photoPath });
  } catch (error) {
    next(error);
  }
});

router.get('/:studentId/payments', async (req, res, next) => {
  try {
    const rows = await query(
      `SELECT id, amount, fees_head, payment, payment_status, payment_date, payment_mode,
              payment_time, clientTxnId, PGTxnNo, SabPaisaTxId, entry_time
       FROM online_payment_transaction
       WHERE student_id = :studentId
       ORDER BY id DESC`,
      { studentId: req.studentId }
    );
    res.json({ data: rows });
  } catch (error) {
    next(error);
  }
});

router.get('/:studentId/documents', async (req, res, next) => {
  try {
    const student = await profileFor(req.studentId);
    if (!student) return res.status(404).json({ message: 'Student not found' });

    const categoryColumns = await columnsFor('document_category');
    const names = new Set(categoryColumns.map((column) => column.name));
    const nameColumn = names.has('document_name') ? 'document_name' : 'document';
    const groupColumn = names.has('document_id') ? 'document_id' : 'category';

    let documentId = null;
    if (student.course_group_id) {
      const groupRows = await query(
        `SELECT document_id FROM course_group WHERE id = :courseGroupId LIMIT 1`,
        { courseGroupId: student.course_group_id }
      ).catch(() => []);
      documentId = groupRows[0]?.document_id || null;
    }

    const where = documentId ? `WHERE status = 'Y' AND ${groupColumn} = :documentId` : `WHERE status = 'Y'`;
    const requiredDocs = await query(
      `SELECT id, ${groupColumn} AS documentGroupId, ${nameColumn} AS documentName
       FROM document_category
       ${where}
       ORDER BY id`,
      { documentId }
    );
    const fallbackDocs = [
      { id: 1, documentGroupId: 0, documentName: 'Aadhaar Card' },
      { id: 2, documentGroupId: 0, documentName: 'Jati Praman Patra' },
      { id: 3, documentGroupId: 0, documentName: 'High School Marksheet' },
      { id: 4, documentGroupId: 0, documentName: 'Transfer Certificate' }
    ];

    const uploads = await query(
      `SELECT id, document_category_id, document_name, document_image, status, remark, date_time
       FROM admission_document
       WHERE student_id = :studentId
       ORDER BY id DESC`,
      { studentId: req.studentId }
    );

    res.json({ required: requiredDocs.length ? requiredDocs : fallbackDocs, uploaded: uploads });
  } catch (error) {
    next(error);
  }
});

router.post('/:studentId/documents', upload.single('document'), async (req, res, next) => {
  try {
    if (!req.file) return res.status(400).json({ message: 'Document file is required' });
    const student = await profileFor(req.studentId);
    if (!student) return res.status(404).json({ message: 'Student not found' });

    const documentCategoryId = Number(req.body.documentCategoryId || 0);
    const documentName = req.body.documentName || req.file.originalname;
    const filePath = `/uploads/admission-documents/${req.file.filename}`;

    await transaction(async (connection) => {
      await connection.execute(
        `INSERT INTO admission_document (
          student_id, course_group_id, document_name, document_image, status, remark, document_category_id
        ) VALUES (
          :studentId, :courseGroupId, :documentName, :filePath, 'Y', '', :documentCategoryId
        )`,
        {
          studentId: req.studentId,
          courseGroupId: student.course_group_id || 0,
          documentName,
          filePath,
          documentCategoryId
        }
      );

      await connection.execute(
        `UPDATE student SET document_uploaded = 'Y' WHERE student_id = :studentId`,
        { studentId: req.studentId }
      );
    });

    res.status(201).json({ message: 'Document uploaded', path: filePath });
  } catch (error) {
    next(error);
  }
});

router.get('/profile', requireAuth, async (req, res, next) => {
  try {
    const studentId = req.user.role === 'student' ? req.user.id : req.query.student_id;
    if (!studentId) return res.status(400).json({ message: 'student_id is required' });

    const rows = await query(
      `SELECT s.*, sd.*
       FROM student s
       LEFT JOIN student_data sd ON s.student_id = sd.student_id
       WHERE s.student_id = :studentId
       LIMIT 1`,
      { studentId }
    );

    if (!rows[0]) return res.status(404).json({ message: 'Student not found' });
    res.json({ data: rows[0] });
  } catch (error) {
    next(error);
  }
});

router.get('/papers', requireAuth, async (req, res, next) => {
  try {
    const studentId = req.user.role === 'student' ? req.user.id : req.query.student_id;
    if (!studentId) return res.status(400).json({ message: 'student_id is required' });

    const rows = await query(
      `SELECT pm.*
       FROM paper_master pm
       INNER JOIN new_exam_form nef ON pm.id = nef.paper_id
       WHERE nef.student_id = :studentId AND nef.paper_type = 'theory'
       ORDER BY pm.paper_code ASC`,
      { studentId }
    );

    res.json({ data: rows });
  } catch (error) {
    next(error);
  }
});

export default router;
