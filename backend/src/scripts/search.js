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
    } else if (file.endsWith('.js') || file.endsWith('.jsx') || file.endsWith('.html') || file.endsWith('.css')) {
      const content = fs.readFileSync(fullPath, 'utf8');
      if (content.includes('Jai Guru Dev')) {
        console.log(`Found in: ${fullPath}`);
      }
    }
  }
}

search(searchDir);
