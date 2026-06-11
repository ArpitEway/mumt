const fs = require('fs');
const path = require('path');

const pkgPath = path.join(process.cwd(), 'package.json');
const backupPath = path.join(process.cwd(), 'package.json.bak');

try {
  const raw = fs.readFileSync(pkgPath, 'utf8');
  const pkg = JSON.parse(raw);
  fs.writeFileSync(backupPath, JSON.stringify(pkg, null, 2));
  if (pkg.workspaces) {
    delete pkg.workspaces;
    fs.writeFileSync(pkgPath, JSON.stringify(pkg, null, 2));
    console.log('Removed workspaces and backed up package.json to package.json.bak');
  } else {
    console.log('No workspaces field present; backup created');
  }
} catch (err) {
  console.error('Failed to remove workspaces:', err.message);
  process.exit(1);
}
