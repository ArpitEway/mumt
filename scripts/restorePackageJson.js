const fs = require('fs');
const path = require('path');

const pkgPath = path.join(process.cwd(), 'package.json');
const backupPath = path.join(process.cwd(), 'package.json.bak');

try {
  if (!fs.existsSync(backupPath)) {
    console.error('Backup package.json.bak not found. Aborting restore.');
    process.exit(1);
  }
  const backup = fs.readFileSync(backupPath, 'utf8');
  fs.writeFileSync(pkgPath, backup);
  fs.unlinkSync(backupPath);
  console.log('Restored package.json from package.json.bak and removed backup');
} catch (err) {
  console.error('Failed to restore package.json:', err.message);
  process.exit(1);
}
