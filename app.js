const { spawn } = require('child_process');
const path = require('path');

// Path to the backend server file
const backendServer = path.join(__dirname, 'backend', 'src', 'server.js');

const child = spawn(process.execPath, [backendServer], {
  cwd: __dirname,
  env: process.env,
  stdio: 'inherit'
});

function forward(signal) {
  if (child.killed) return;
  child.kill(signal);
}

process.on('SIGINT', () => forward('SIGINT'));
process.on('SIGTERM', () => forward('SIGTERM'));
process.on('SIGHUP', () => forward('SIGHUP'));

child.on('exit', (code, signal) => {
  if (signal) process.kill(process.pid, signal);
  else process.exit(code);
});
