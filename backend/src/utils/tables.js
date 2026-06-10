export const allowedTables = new Set([
  'admin_master',
  'center',
  'student',
  'student_data',
  'course',
  'course_group',
  'class_master',
  'paper_master',
  'session',
  'menu',
  'menu_heading',
  'student_menu',
  'student_menu_heading',
  'center_menu',
  'center_menu_heading',
  'state',
  'distt',
  'teacher',
  'exam_center',
  'time_table',
  'online_payment_transaction',
  'payment_complaint',
  'master'
]);

export function assertAllowedTable(table) {
  if (!allowedTables.has(table)) {
    const error = new Error(`Table "${table}" is not exposed by the API.`);
    error.status = 400;
    throw error;
  }
}

export function coerceIdentifier(value) {
  if (!/^[a-zA-Z0-9_]+$/.test(value)) {
    const error = new Error('Invalid SQL identifier.');
    error.status = 400;
    throw error;
  }
  return `\`${value}\``;
}
