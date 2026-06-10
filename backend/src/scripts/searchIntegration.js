import fs from 'fs';
import path from 'path';

const searchDir = 'c:/Users/Admin/Downloads/mmyvv/application';

function search(dir) {
  const files = fs.readdirSync(dir);
  for (const file of files) {
    const fullPath = path.join(dir, file);
    const stat = fs.statSync(fullPath);
    if (stat.isDirectory()) {
      search(fullPath);
    } else if (file.endsWith('.php')) {
      const content = fs.readFileSync(fullPath, 'utf8');
      if (content.includes('root') || content.includes('dist/') || content.includes('frontend')) {
        console.log(`Found match in: ${fullPath}`);
      }
    }
  }
}

search(searchDir);
