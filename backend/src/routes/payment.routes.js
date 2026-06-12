import { Router } from 'express';
import { query, transaction } from '../config/db.js';
import { decodeId } from '@mmyvv/shared/idEncryption';

const router = Router();

/* =========================
   GET PAYMENT DETAILS
========================= */
router.get('/:studentId', async (req, res, next) => {
  try {
    const studentId = decodeId(req.params.studentId);
    if (!studentId) {
      return res.status(400).json({ message: 'Invalid student id' });
    }
    console.log("PAYMENT GET studentId:", studentId, typeof studentId);

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
      { studentId }
    );
    console.log("PAYMENT GET rows:", rows);

    if (!rows[0]) {
      return res.status(404).json({
        message: 'Student not found'
      });
    }

    // Default amount
    let amount = 272;

    const pc = rows[0].portal_charges;
    const pf = rows[0].program_fees;

    if (pc && !isNaN(Number(pc))) {
      amount = Number(pc);
    } else if (pf && !isNaN(Number(pf))) {
      amount = Number(pf);
    }

    res.json({
      student: rows[0],
      amount
    });

  } catch (error) {
    next(error);
  }
});


/* =========================
   PAY NOW
========================= */
router.post('/:studentId/pay', async (req, res, next) => {
  try {
    const studentId = decodeId(req.params.studentId);
    if (!studentId) {
      return res.status(400).json({ message: 'Invalid student id' });
    }

    const rows = await query(
      `SELECT 
          student_id,
          name,
          course_group_id,
          class_id,
          institute_id,
          session,
          portal_charges,
          program_fees
       FROM student
       WHERE student_id = :studentId
       LIMIT 1`,
      { studentId }
    );

    const student = rows[0];

    if (!student) {
      return res.status(404).json({
        message: 'Student not found'
      });
    }

    // Amount Calculation
    let amount = 272;

    if (
      student.portal_charges &&
      !Number.isNaN(Number(student.portal_charges))
    ) {
      amount = Number(student.portal_charges);
    } else if (
      student.program_fees &&
      !Number.isNaN(Number(student.program_fees))
    ) {
      amount = Number(student.program_fees);
    }

    const now = new Date();

    const paymentDate = now.toISOString().slice(0, 10);

    const paymentTime = now
      .toTimeString()
      .slice(0, 8);

    const clientTxnId = `DEV-${studentId}-${Date.now()}`;

    const result = await transaction(async (connection) => {

      const [insertResult] = await connection.execute(
        `INSERT INTO online_payment_transaction (
          student_id,
          institute_id,
          course_group_id,
          class_id,
          amount,
          fees_head,
          remark,
          exam_session,
          student_name,
          payment,
          payment_status,
          image,
          payment_date,
          payment_mode,
          payment_time,
          clientTxnId,
          PGTxnNo,
          SabPaisaTxId,
          issuerRefNo
        ) VALUES (
          :studentId,
          :instituteId,
          :courseGroupId,
          :classId,
          :amount,
          'Admission Fee',
          'Development payment entry',
          :session,
          :studentName,
          'Y',
          'SUCCESS',
          '',
          :paymentDate,
          'Online',
          :paymentTime,
          :clientTxnId,
          '',
          '',
          ''
        )`,
        {
          studentId,
          instituteId: student.institute_id || 0,
          courseGroupId: student.course_group_id || 0,
          classId: student.class_id || 0,
          amount,
          session: student.session || '2026',
          studentName: student.name || '',
          paymentDate,
          paymentTime,
          clientTxnId
        }
      );

      // Update student payment status
      await connection.execute(
        `UPDATE student
         SET payment_status = 'Y'
         WHERE student_id = :studentId`,
        { studentId }
      );

      return {
        paymentId: insertResult.insertId,
        amount,
        clientTxnId
      };
    });

    res.status(201).json({
      ...result,
      studentId: Number(studentId),
      redirectTo: `/student-dashboard/${encodeId(studentId)}`
    });

  } catch (error) {
    next(error);
  }
});

export default router;
