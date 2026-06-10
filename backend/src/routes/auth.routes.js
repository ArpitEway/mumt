import { Router } from 'express';
import { loginSchema, changePasswordSchema } from '@mmyvv/shared';
import { validate } from '../middleware/validate.js';
import { query } from '../config/db.js';
import { requireAuth, signToken } from '../middleware/auth.js';
import { matchesLegacyOrHash, md5 } from '../utils/password.js';

const router = Router();

function publicUser(role, row) {
  if (role === 'admin') {
    return {
      id: row.id,
      role,
      name: row.name,
      username: row.user_name,
      accountType: row.account_type || 'admin'
    };
  }
  if (role === 'center') {
    return {
      id: row.id,
      role,
      name: row.center_name || row.name || row.center_code,
      centerCode: row.center_code,
      accountType: 'center'
    };
  }
  if (role === 'student') {
    return {
      id: row.student_id || row.id,
      role,
      name: row.name || row.student_name,
      enrollmentNo: row.enrollment_no,
      admissionBy: row.admission_by
    };
  }
  if (role === 'teacher') {
    return {
      id: row.id,
      role,
      name: row.name || row.teacher_name || row.phone,
      phone: row.phone
    };
  }
  return {
    id: row.id,
    role,
    name: row.name || row.center_name || row.username,
    username: row.username
  };
}

// Enhanced findUser to auto-detect email logins for non‑enrolled students
async function findUser(role, username, password, opts = {}) {
  if (role === 'admin') {
    const rows = await query(
      'SELECT * FROM admin_master WHERE user_name = :username AND password = :password LIMIT 1',
      { username, password: md5(password) }
    );
    return rows[0];
  }

  if (role === 'center') {
    const rows = await query(
      "SELECT * FROM center WHERE center_code = :username AND password = :password AND status = 'Y' LIMIT 1",
      { username, password }
    );
    return rows[0];
  }

    if (role === 'student') {
        // Convert DOB from dd/MM/yyyy (or ISO) to YYYY-MM-DD
        let dob = password;
        const ddMMyyyy = /^(\d{2})\/(\d{2})\/(\d{4})$/;
        if (ddMMyyyy.test(password)) {
            const [, dd, mm, yyyy] = password.match(ddMMyyyy);
            dob = `${yyyy}-${mm}-${dd}`;
        } else {
            const dateObj = new Date(password);
            if (!Number.isNaN(dateObj.getTime())) {
                dob = dateObj.toISOString().slice(0, 10);
            }
        }

        // Non‑enrolled login uses mobile number (provided as username) and DOB
        if (opts.nonEnrolled) {
            const rows = await query(
                `SELECT s.* FROM student s
                 INNER JOIN student_data sd ON s.student_id = sd.student_id
                 WHERE sd.p_mobile_no = :mobile AND s.dob = :dob
                 LIMIT 1`,
                { mobile: username, dob }
            );
            return rows[0];
        }

        // Enrolled student login uses enrollment number
        const rows = await query(
            'SELECT * FROM student WHERE enrollment_no = :username AND dob = :dob  AND enrolled = "Y" LIMIT 1',
            { username, dob }
        );
        return rows[0];
    }




  

  if (role === 'teacher') {
    const rows = await query('SELECT * FROM teacher WHERE phone = :username LIMIT 1', { username });
    const teacher = rows[0];
    if (teacher && (await matchesLegacyOrHash(password, teacher.password))) return teacher;
  }

  if (role === 'exam_center') {
    const rows = await query('SELECT * FROM exam_center WHERE username = :username LIMIT 1', { username });
    const examCenter = rows[0];
    if (examCenter && (await matchesLegacyOrHash(password, examCenter.password))) return examCenter;
  }

  return null;
}

router.post('/login', validate(loginSchema), async (req, res, next) => {
  try {
    const body = req.body;
    const row = await findUser(body.role, body.username, body.password, { nonEnrolled: body.nonEnrolled });
    
    if (!row) return res.status(401).json({ message: 'Invalid login details' });

    const user = publicUser(body.role, row);
    if (body.role === 'student') {
      user.nonEnrolled = !!body.nonEnrolled;
    }

    const token = signToken(user);
    res.json({ token, user });
  } catch (error) {
    next(error);
  }
});

router.get('/me', requireAuth, async (req, res) => {
  res.json({ user: req.user });
});



router.post('/change-password', requireAuth, validate(changePasswordSchema), async (req, res, next) => {
  try {
    const { currentPassword, newPassword } = req.body;
    const { role, id } = req.user;

    if (role === 'admin') {
      const rows = await query('SELECT * FROM admin_master WHERE id = :id AND password = :password LIMIT 1', { id, password: md5(currentPassword) });
      if (!rows[0]) return res.status(400).json({ message: 'Invalid current password' });

      await query('UPDATE admin_master SET password = :password WHERE id = :id', { id, password: md5(newPassword) });
      return res.json({ message: 'Password updated successfully' });
    }

    if (role === 'center') {
      const rows = await query('SELECT * FROM center WHERE id = :id AND password = :password LIMIT 1', { id, password: currentPassword });
      if (!rows[0]) return res.status(400).json({ message: 'Invalid current password' });

      await query('UPDATE center SET password = :password WHERE id = :id', { id, password: newPassword });
      return res.json({ message: 'Password updated successfully' });
    }

    return res.status(400).json({ message: 'Changing password not supported for this role' });
  } catch (error) {
    next(error);
  }
});

export default router;
