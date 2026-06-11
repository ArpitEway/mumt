import path from 'path';
import fs from 'fs';
import { fileURLToPath } from 'url';
import { app } from './app.js';
import { pool } from './config/db.js';
import { env } from './config/env.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

async function start() {
  // Skip DB connection check during development
  // await pool.query('SELECT 1');

  app.get('*', (req, res) => {
    // Serve the React app for any non-API route
    const indexPath = path.join(__dirname, '../../frontend/dist', 'index.html');
    if (fs.existsSync(indexPath)) {
      res.sendFile(indexPath);
    } else {
      // If frontend isn't built on the server, return a helpful message
      res.status(200).send('Frontend not found (build not present). API is running.');
    }
  });

  app.listen(env.port, () => {
    console.log(`Backend running on http://localhost:${env.port}`);
  });
}

start().catch((error) => {
  console.error('Unable to start backend:', error.message);
  process.exit(1);
});
