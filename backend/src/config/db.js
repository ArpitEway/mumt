import mysql from 'mysql2/promise';
import { env } from './env.js';

let isMockMode = false;

export const pool = mysql.createPool({
  ...env.db,
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
  dateStrings: true,
  namedPlaceholders: true
});

// Setup fallback/mock database in memory
const mockDb = {
  admin_master: [
    {
      id: 1,
      name: "Administrator",
      designation: "Admin",
      user_name: "admin",
      password: "21232f297a57a5a743894a0e4a801fc3", // md5 of 'admin'
      account_type: "admin",
      status: "Y",
      admin_order: 1
    }
  ],
  center: [
    {
      id: 1,
      center_name: "Delhi Center",
      center_code: "C001",
      password: "center123",
      status: "Y"
    },
    {
      id: 2,
      center_name: "Mumbai Center",
      center_code: "C002",
      password: "center456",
      status: "Y"
    }
  ],
  student: [
    {
      id: 1,
      student_id: 1,
      name: "Student One",
      enrollment_no: "S001",
      dob: "2000-01-01",
      status: "Y"
    },
    {
      id: 2,
      student_id: 2,
      name: "Student Two",
      enrollment_no: "S002",
      dob: "1999-05-15",
      status: "Y"
    }
  ],
  student_data: [
    {
      id: 1,
      student_id: 1,
      p_mobile_no: "9876543210",
      p_email: "student1@gmail.com"
    },
    {
      id: 2,
      student_id: 2,
      p_mobile_no: "9876543211",
      p_email: "student2@gmail.com"
    }
  ],
  course: [
    { id: 1, course_name: "Bachelor of Computer Applications", course_code: "BCA", duration: "3 Years" },
    { id: 2, course_name: "Master of Computer Applications", course_code: "MCA", duration: "2 Years" },
    { id: 3, course_name: "Diploma in Information Technology", course_code: "DIT", duration: "1 Year" }
  ],
  course_group: [
    { id: 1, course_name: "BCA Group A", course_code: "BCA-GP-A", mode: "Regular", status: "Y" },
    { id: 2, course_name: "MCA Group B", course_code: "MCA-GP-B", mode: "Regular", status: "Y" }
  ],
  class_master: [
    { id: 1, class_name: "1st Semester", course_group_id: 1, mode: "Regular", admission_permission: "Y" },
    { id: 2, class_name: "2nd Semester", course_group_id: 1, mode: "Regular", admission_permission: "Y" }
  ],
  session: [
    { id: 1, session_name: "2024-2025", status: "Y" },
    { id: 2, session_name: "2025-2026", status: "Y" }
  ],
  online_payment_transaction: [
    { id: 1, student_id: 1, amount: 5000.00, payment: "N", transaction_date: "2026-05-26" },
    { id: 2, student_id: 2, amount: 7500.00, payment: "Y", transaction_date: "2026-05-25" }
  ],
  paper_master: [
    { id: 1, paper_name: "Programming in C", paper_code: "BCA-101", max_marks: 100, test_id: "T101" },
    { id: 2, paper_name: "Database Management Systems", paper_code: "BCA-102", max_marks: 100, test_id: "T102" }
  ],
  menu_heading: [
    { id: 1, label: "Login...", heading_name: "Login...", heading_order: 1 },
    { id: 2, label: "Students", heading_name: "Students", heading_order: 2 },
    { id: 3, label: "Academics", heading_name: "Academics", heading_order: 3 },
    { id: 4, label: "Permission", heading_name: "Permission", heading_order: 4 },
    { id: 5, label: "Menu Management", heading_name: "Menu Management", heading_order: 5 },
    { id: 6, label: "Pre Exam Processing", heading_name: "Pre Exam Processing", heading_order: 6 },
    { id: 7, label: "Result", heading_name: "Result", heading_order: 7 },
    { id: 8, label: "Centers", heading_name: "Centers", heading_order: 8 }
  ],
  menu: [
    // Under "Login..."
    { id: 1, heading_id: 1, label: "Login As Admin Account", table: "admin_master", menu_order: 1 },
    { id: 2, heading_id: 1, label: "Manage Admin", table: "admin_master", menu_order: 2 },
    { id: 3, heading_id: 1, label: "Log As Center", table: "center", menu_order: 3 },
    { id: 4, heading_id: 1, label: "Log As Exam Center", table: "exam_center", menu_order: 4 },
    
    // Under "Students"
    { id: 5, heading_id: 2, label: "Students List", table: "student", menu_order: 1 },
    { id: 6, heading_id: 2, label: "Student Profiles", table: "student_profile", menu_order: 2 },
    
    // Under "Academics"
    { id: 7, heading_id: 3, label: "Course Detail", table: "course_group", menu_order: 1 },
    { id: 8, heading_id: 3, label: "Session", table: "session", menu_order: 2 },
    { id: 15, heading_id: 3, label: "Course", table: "course", menu_order: 3 },
    { id: 16, heading_id: 3, label: "Classes", table: "class_master", menu_order: 4 },
    { id: 17, heading_id: 3, label: "Paper", table: "paper_master", menu_order: 5 },
    { id: 18, heading_id: 3, label: "Test Id Wise Paper List", table: "paper_master", menu_order: 6 },
    
    // Under "Permission"
    { id: 9, heading_id: 4, label: "Admin Permissions", table: "admin_permission", menu_order: 1 },
    
    // Under "Menu Management"
    { id: 10, heading_id: 5, label: "Configure Headings", table: "menu_heading", menu_order: 1 },
    { id: 11, heading_id: 5, label: "Configure Menus", table: "menu", menu_order: 2 },
    
    // Under "Pre Exam Processing"
    { id: 12, heading_id: 6, label: "Exam Papers", table: "paper_master", menu_order: 1 },
    
    // Under "Result"
    { id: 13, heading_id: 7, label: "Student Marks", table: "marks", menu_order: 1 },
    
    // Under "Centers"
    { id: 14, heading_id: 8, label: "Exam Centers", table: "exam_center", menu_order: 1 }
  ]
};

const schemas = {
  admin_master: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "name", type: "varchar", columnKey: "", extra: "" },
    { name: "designation", type: "varchar", columnKey: "", extra: "" },
    { name: "user_name", type: "varchar", columnKey: "", extra: "" },
    { name: "password", type: "varchar", columnKey: "", extra: "" },
    { name: "account_type", type: "varchar", columnKey: "", extra: "" },
    { name: "status", type: "char", columnKey: "", extra: "" },
    { name: "admin_order", type: "int", columnKey: "", extra: "" }
  ],
  center: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "center_name", type: "varchar", columnKey: "", extra: "" },
    { name: "center_code", type: "varchar", columnKey: "", extra: "" },
    { name: "password", type: "varchar", columnKey: "", extra: "" },
    { name: "status", type: "char", columnKey: "", extra: "" }
  ],
  student: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "student_id", type: "int", columnKey: "", extra: "" },
    { name: "name", type: "varchar", columnKey: "", extra: "" },
    { name: "enrollment_no", type: "varchar", columnKey: "", extra: "" },
    { name: "dob", type: "date", columnKey: "", extra: "" },
    { name: "status", type: "char", columnKey: "", extra: "" }
  ],
  student_data: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "student_id", type: "int", columnKey: "", extra: "" },
    { name: "p_mobile_no", type: "varchar", columnKey: "", extra: "" },
    { name: "p_email", type: "varchar", columnKey: "", extra: "" }
  ],
  course: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "course_name", type: "varchar", columnKey: "", extra: "" },
    { name: "course_code", type: "varchar", columnKey: "", extra: "" },
    { name: "duration", type: "varchar", columnKey: "", extra: "" }
  ],
  course_group: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "course_name", type: "varchar", columnKey: "", extra: "" },
    { name: "course_code", type: "varchar", columnKey: "", extra: "" },
    { name: "mode", type: "varchar", columnKey: "", extra: "" },
    { name: "status", type: "char", columnKey: "", extra: "" }
  ],
  class_master: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "class_name", type: "varchar", columnKey: "", extra: "" },
    { name: "course_group_id", type: "int", columnKey: "", extra: "" },
    { name: "mode", type: "varchar", columnKey: "", extra: "" },
    { name: "admission_permission", type: "char", columnKey: "", extra: "" }
  ],
  session: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "session_name", type: "varchar", columnKey: "", extra: "" },
    { name: "status", type: "char", columnKey: "", extra: "" }
  ],
  online_payment_transaction: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "student_id", type: "int", columnKey: "", extra: "" },
    { name: "amount", type: "decimal", columnKey: "", extra: "" },
    { name: "payment", type: "char", columnKey: "", extra: "" },
    { name: "transaction_date", type: "date", columnKey: "", extra: "" }
  ],
  paper_master: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "paper_name", type: "varchar", columnKey: "", extra: "" },
    { name: "paper_code", type: "varchar", columnKey: "", extra: "" },
    { name: "max_marks", type: "int", columnKey: "", extra: "" },
    { name: "test_id", type: "varchar", columnKey: "", extra: "" }
  ],
  menu_heading: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "label", type: "varchar", columnKey: "", extra: "" },
    { name: "heading_name", type: "varchar", columnKey: "", extra: "" },
    { name: "heading_order", type: "int", columnKey: "", extra: "" }
  ],
  menu: [
    { name: "id", type: "int", columnKey: "PRI", extra: "auto_increment" },
    { name: "heading_id", type: "int", columnKey: "", extra: "" },
    { name: "label", type: "varchar", columnKey: "", extra: "" },
    { name: "table", type: "varchar", columnKey: "", extra: "" },
    { name: "menu_order", type: "int", columnKey: "", extra: "" }
  ]
};

// Simple Mock SQL Runner for testing without real MySQL
function runMockQuery(sql, params) {
  const queryLower = sql.trim().toLowerCase();

  // 1. INFORMATION_SCHEMA tables listing
  if (queryLower.includes('information_schema.tables')) {
    return Object.keys(mockDb).map(name => ({ name }));
  }

  // 2. INFORMATION_SCHEMA columns schema
  if (queryLower.includes('information_schema.columns')) {
    const table = params.table;
    return schemas[table] || [];
  }

  // 3. COUNT(*) queries
  if (queryLower.startsWith('select count(*)')) {
    const match = sql.match(/from\s+([a-zA-Z0-9_]+)/i);
    if (match) {
      const table = match[1];
      const items = mockDb[table] || [];
      return [{ total: items.length }];
    }
    return [{ total: 0 }];
  }

  // 4. SELECT * from specific tables with criteria
  if (queryLower.startsWith('select')) {
    const fromMatch = sql.match(/from\s+([a-zA-Z0-9_]+)/i);
    if (fromMatch) {
      const table = fromMatch[1];
      let items = JSON.parse(JSON.stringify(mockDb[table] || []));

      // Handle simple filters (e.g. user_name = :username or enrollment_no = :username)
      if (table === 'admin_master') {
        const username = params.username || params.user_name;
        if (username) {
          items = items.filter(u => u.user_name === username);
        }
      } else if (table === 'center') {
        const code = params.username || params.center_code;
        if (code) {
          items = items.filter(c => c.center_code === code);
        }
      } else if (table === 'student') {
        const enroll = params.username || params.enrollmentNo || params.enrollment_no;
        const studentId = params.studentId || params.student_id;
        if (enroll) {
          items = items.filter(s => s.enrollment_no === enroll);
        } else if (studentId) {
          items = items.filter(s => Number(s.student_id || s.studentId) === Number(studentId));
        }
      } else if (table === 'student_data') {
        const mobile = params.mobile || params.p_mobile_no;
        if (mobile) {
          items = items.filter(s => s.p_mobile_no === mobile || s.mobile === mobile);
        }
      }

      // Handle pagination
      if (params.limit !== undefined && params.offset !== undefined) {
        const limit = Number(params.limit);
        const offset = Number(params.offset);
        items = items.slice(offset, offset + limit);
      }

      return items;
    }
  }

  // 5. INSERT
  if (queryLower.startsWith('insert into')) {
    const match = sql.match(/insert into\s+([a-zA-Z0-9_]+)/i);
    if (match) {
      const table = match[1];
      if (mockDb[table]) {
        const newId = mockDb[table].reduce((max, item) => Math.max(max, item.id || 0), 0) + 1;
        const newRecord = { id: newId, ...params };
        if (table === 'student' && !newRecord.student_id) {
          newRecord.student_id = newId;
        }
        mockDb[table].push(newRecord);
        return { insertId: newId, affectedRows: 1 };
      }
    }
  }

  // 6. UPDATE
  if (queryLower.startsWith('update')) {
    const match = sql.match(/update\s+([a-zA-Z0-9_]+)/i);
    if (match) {
      const table = match[1];
      const lookupId = params.id || (table === 'student' ? params.studentId : undefined);
      if (mockDb[table] && lookupId) {
        const index = mockDb[table].findIndex(item => Number(item.id) === Number(lookupId) || Number(item.student_id) === Number(lookupId));
        if (index !== -1) {
          mockDb[table][index] = { ...mockDb[table][index], ...params };
          return { affectedRows: 1 };
        }
      }
    }
  }

  // 7. DELETE
  if (queryLower.startsWith('delete from')) {
    const match = sql.match(/delete from\s+([a-zA-Z0-9_]+)/i);
    if (match) {
      const table = match[1];
      if (mockDb[table] && params.id) {
        const lengthBefore = mockDb[table].length;
        mockDb[table] = mockDb[table].filter(item => Number(item.id) !== Number(params.id));
        return { affectedRows: lengthBefore - mockDb[table].length };
      }
    }
  }

  return [];
}

export async function query(sql, params = {}) {
  if (isMockMode) {
    return runMockQuery(sql, params);
  }

  try {
    const [rows] = await pool.execute(sql, params);
    return rows;
  } catch (error) {
    // If database connection fails (AggregateError or ECONNREFUSED), fall back to Mock mode automatically!
    const isConnError = error.message.includes('AggregateError') || 
                        error.message.includes('ECONNREFUSED') || 
                        error.code === 'ECONNREFUSED' || 
                        error.code === 'ENOTFOUND';
    
    if (isConnError) {
      console.warn("⚠️ MySQL connection failed. Automatically falling back to robust in-memory Developer Mock Mode.");
      isMockMode = true;
      return runMockQuery(sql, params);
    }
    throw error;
  }
}

export async function transaction(callback) {
  if (isMockMode) {
    return await callback({
      execute: async (sql, params = {}) => [runMockQuery(sql, params)],
      commit: async () => {},
      rollback: async () => {},
      release: () => {}
    });
  }

  const connection = await pool.getConnection();
  try {
    await connection.beginTransaction();
    const result = await callback(connection);
    await connection.commit();
    return result;
  } catch (error) {
    await connection.rollback();
    throw error;
  } finally {
    connection.release();
  }
}
