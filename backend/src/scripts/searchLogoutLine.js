import fs from 'fs';

const file = 'c:/Users/Admin/Downloads/mmyvv/application/controllers/admin/Admins.php';
const content = fs.readFileSync(file, 'utf8');
const lines = content.split('\n');

for (let i = 0; i < lines.length; i++) {
  if (lines[i].includes('logout')) {
    console.log(`Line ${i + 1}: ${lines[i]}`);
  }
}
