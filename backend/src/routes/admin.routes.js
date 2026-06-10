import { Router } from 'express';
import { query } from '../config/db.js';
import { requireAuth } from '../middleware/auth.js';

const router = Router();

const defaultHeadings = [
  { id: 1, admin_id: 1, heading: "Login...", heading_order: 1, status: "Y" },
  { id: 2, admin_id: 1, heading: "Students", heading_order: 2, status: "Y" },
  { id: 3, admin_id: 1, heading: "Academics", heading_order: 3, status: "Y" },
  { id: 4, admin_id: 1, heading: "Permission", heading_order: 4, status: "Y" },
  { id: 5, admin_id: 1, heading: "Menu Management", heading_order: 5, status: "Y" },
  { id: 6, admin_id: 1, heading: "Pre Exam Processing", heading_order: 6, status: "Y" },
  { id: 7, admin_id: 1, heading: "Result", heading_order: 7, status: "Y" },
  { id: 8, admin_id: 1, heading: "Centers", heading_order: 8, status: "Y" }
];

const defaultMenus = [
  // Under "Login..."
  { id: 1, admin_id: 1, heading_id: 1, option: "Login As Admin Account", url: "admin_master", status: "Y", menu_order: 1 },
  { id: 2, admin_id: 1, heading_id: 1, option: "Manage Admin", url: "admin_master", status: "Y", menu_order: 2 },
  { id: 3, admin_id: 1, heading_id: 1, option: "Log As Center", url: "center", status: "Y", menu_order: 3 },
  { id: 4, admin_id: 1, heading_id: 1, option: "Log As Exam Center", url: "exam_center", status: "Y", menu_order: 4 },
  
  // Under "Students"
  { id: 5, admin_id: 1, heading_id: 2, option: "Students List", url: "student", status: "Y", menu_order: 1 },
  { id: 6, admin_id: 1, heading_id: 2, option: "Student Profiles", url: "student_profile", status: "Y", menu_order: 2 },
  
  // Under "Academics"
  { id: 7, admin_id: 1, heading_id: 3, option: "Course Detail", url: "course_group", status: "Y", menu_order: 1 },
  { id: 8, admin_id: 1, heading_id: 3, option: "Session", url: "session", status: "Y", menu_order: 2 },
  { id: 15, admin_id: 1, heading_id: 3, option: "Course", url: "course", status: "Y", menu_order: 3 },
  { id: 16, admin_id: 1, heading_id: 3, option: "Classes", url: "class_master", status: "Y", menu_order: 4 },
  { id: 17, admin_id: 1, heading_id: 3, option: "Paper", url: "paper_master", status: "Y", menu_order: 5 },
  { id: 18, admin_id: 1, heading_id: 3, option: "Test Id Wise Paper List", url: "paper_master", status: "Y", menu_order: 6 },
  
  // Under "Permission"
  { id: 9, admin_id: 1, heading_id: 4, option: "Admin Permissions", url: "admin_permission", status: "Y", menu_order: 1 },
  
  // Under "Menu Management"
  { id: 10, admin_id: 1, heading_id: 5, option: "Configure Headings", url: "menu_heading", status: "Y", menu_order: 1 },
  { id: 11, admin_id: 1, heading_id: 5, option: "Configure Menus", url: "menu", status: "Y", menu_order: 2 },
  
  // Under "Pre Exam Processing"
  { id: 12, admin_id: 1, heading_id: 6, option: "Exam Papers", url: "paper_master", status: "Y", menu_order: 1 },
  
  // Under "Result"
  { id: 13, admin_id: 1, heading_id: 7, option: "Student Marks", url: "marks", status: "Y", menu_order: 1 },
  
  // Under "Centers"
  { id: 14, admin_id: 1, heading_id: 8, option: "Exam Centers", url: "exam_center", status: "Y", menu_order: 1 }
];

const defaultStudentHeadings = [
  { id: 1, heading: 'Student', label: 'Student', heading_order: 1, status: 'Y' }
];

const defaultStudentMenus = [
  { id: 1, heading_id: 1, option: 'Registration', label: 'Registration', link: '/students/registration', menu_order: 1, status: 'Y' },
  { id: 2, heading_id: 1, option: 'Document', label: 'Document', link: '/students/documents', menu_order: 2, status: 'Y' },
  { id: 3, heading_id: 1, option: 'Payment', label: 'Payment', link: '/students/payments', menu_order: 3, status: 'Y' }
];

async function seedDefaultMenuIfEmpty() {
  try {
    const headings = await query(`SELECT COUNT(*) AS total FROM menu_heading`);
    if (Number(headings[0]?.total || 0) === 0) {
      console.log("Seeding default menu_heading values into MySQL...");
      for (const h of defaultHeadings) {
        await query(
          `INSERT INTO menu_heading (id, admin_id, heading, heading_order, status) 
           VALUES (:id, :admin_id, :heading, :heading_order, :status)`,
          h
        );
      }
    }

    const menus = await query(`SELECT COUNT(*) AS total FROM menu`);
    if (Number(menus[0]?.total || 0) === 0) {
      console.log("Seeding default menu values into MySQL...");
      for (const m of defaultMenus) {
        await query(
          `INSERT INTO menu (id, admin_id, heading_id, \`option\`, url, status, menu_order) 
           VALUES (:id, :admin_id, :heading_id, :option, :url, :status, :menu_order)`,
          m
        );
      }
    } else {
      // Sync outdated Academics options if present
      const oldMenu = await query(`SELECT COUNT(*) AS total FROM menu WHERE heading_id = 3 AND \`option\` = 'Manage Courses'`);
      if (Number(oldMenu[0]?.total || 0) > 0) {
        console.log("Updating Academics options in database...");
        await query(`DELETE FROM menu WHERE heading_id = 3`);
        const academicMenus = defaultMenus.filter(m => m.heading_id === 3);
        for (const m of academicMenus) {
          await query(
            `INSERT INTO menu (id, admin_id, heading_id, \`option\`, url, status, menu_order) 
             VALUES (:id, :admin_id, :heading_id, :option, :url, :status, :menu_order)`,
            m
          );
        }
      }
    }
  } catch (err) {
    console.error("⚠️ Autoseeding check skipped or failed:", err.message);
  }
}

async function tableCount(table, where = '1=1', params = {}) {
  try {
    const rows = await query(`SELECT COUNT(*) AS total FROM ${table} WHERE ${where}`, params);
    return Number(rows[0]?.total || 0);
  } catch {
    return 0;
  }
}

router.get('/summary', requireAuth, async (req, res, next) => {
  try {
    const [students, centers, courses, sessions, paymentsPending] = await Promise.all([
      tableCount('student'),
      tableCount('center', "status = 'Y'"),
      tableCount('course'),
      tableCount('session'),
      tableCount('online_payment_transaction', "payment = 'N'")
    ]);

    res.json({
      role: req.user.role,
      cards: [
        { label: 'Students', value: students },
        { label: 'Active Centers', value: centers },
        { label: 'Courses', value: courses },
        { label: 'Sessions', value: sessions },
        { label: 'Pending Payments', value: paymentsPending }
      ]
    });
  } catch (error) {
    next(error);
  }
});

router.get('/menu', requireAuth, async (req, res, next) => {
  try {
    if (req.user.role === 'student') {
      return res.json({ headings: defaultStudentHeadings, menus: defaultStudentMenus });
    }

    // Seed default menus if MySQL tables exist but are empty
    await seedDefaultMenuIfEmpty();

    let headingsTable = 'menu_heading';
    let menuTable = 'menu';
    let where = 'status = "Y"';
    const params = {};

    if (req.user.role === 'center') {
      headingsTable = 'center_menu_heading';
      menuTable = 'center_menu';
    } else if (req.user.role === 'admin') {
      // In the default empty DB, admin_id is 1. Make sure we query items mapped to admin_id
      where = '(admin_id = :adminId OR admin_id = 1) AND status = "Y"';
      params.adminId = req.user.id;
    }

    let headings = await query(`SELECT * FROM ${headingsTable} ORDER BY heading_order ASC, id ASC`);
    let menus = await query(`SELECT * FROM ${menuTable} WHERE ${where} ORDER BY heading_id ASC, menu_order ASC, id ASC`, params);

    // Ultimate fallback in case DB is active but tables cannot be written to/are still empty
    if (!headings || headings.length === 0) {
      headings = defaultHeadings;
    }
    if (!menus || menus.length === 0) {
      menus = defaultMenus.filter(m => m.admin_id === 1);
    }

    res.json({ headings, menus });
  } catch (error) {
    next(error);
  }
});

export default router;
