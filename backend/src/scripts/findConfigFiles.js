import fs from 'fs';
import path from 'path';

const searchDir = 'c:/Users/Admin/Downloads/mmyvv';

function search(dir) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const fullPath = path.join(dir, file);
    if (file === 'node_modules' || file === '.git' || file === 'dist') continue;
    const stat = fs.statSync(fullPath);
    if (stat.isDirectory()) {
      search(fullPath);
    } else if (file === 'routes.php' || file === 'config.php' || file === 'database.php') {
      console.log(`Found file: ${fullPath}`);
    }
  }
}

search(searchDir);
