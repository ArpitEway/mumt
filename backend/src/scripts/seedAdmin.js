import { pool } from '../config/db.js';
import { md5 } from '../utils/password.js';

function getArg(name, fallback) {
  const prefix = `--${name}=`;
  const match = process.argv.find((arg) => arg.startsWith(prefix));
  return match ? match.slice(prefix.length) : fallback;
}

const admin = {
  name: getArg('name', 'Administrator'),
  designation: getArg('designation', 'Admin'),
  username: getArg('username', 'admin'),
  password: getArg('password', 'admin123'),
  accountType: getArg('account-type', 'admin')
};

try {
  const [existing] = await pool.execute('SELECT id FROM admin_master WHERE user_name = ? LIMIT 1', [admin.username]);

  if (existing.length) {
    console.log(`Admin "${admin.username}" already exists. No changes made.`);
  } else {
    await pool.execute(
      `INSERT INTO admin_master
       (name, designation, user_name, password, account_type, status, admin_order)
       VALUES (?, ?, ?, ?, ?, 'Y', 1)`,
      [admin.name, admin.designation, admin.username, md5(admin.password), admin.accountType]
    );
    console.log(`Admin created: ${admin.username}`);
    console.log(`Password: ${admin.password}`);
  }
} catch (error) {
  console.error(error.message);
  process.exitCode = 1;
} finally {
  await pool.end();
}
