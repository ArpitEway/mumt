const path = require('path');
const { pathToFileURL } = require('url');

(async () => {
  try {
    const backendServer = path.join(__dirname, 'backend', 'src', 'server.js');
    await import(pathToFileURL(backendServer).href);
  } catch (error) {
    console.error('Failed to start backend server:', error);
    process.exit(1);
  }
})();
