#!/usr/bin/env node

const { spawn } = require('child_process');
const path = require('path');

function run(command, args, cwd, label) {
  return new Promise((resolve, reject) => {
    console.log(`\n📦 ${label}...`);
    const proc = spawn(command, args, {
      cwd,
      stdio: 'inherit',
      shell: true
    });

    proc.on('close', (code) => {
      if (code !== 0) {
        reject(new Error(`${label} failed with code ${code}`));
      } else {
        resolve();
      }
    });

    proc.on('error', (err) => reject(err));
  });
}

async function main() {
  try {
    const rootDir = path.join(__dirname, '..');
    const frontendDir = path.join(rootDir, 'frontend');
    const backendDir = path.join(rootDir, 'backend');

    await run('npm', ['run', 'build'], frontendDir, 'Building frontend');

    console.log('\n🚀 Starting backend...');
    const backend = spawn('node', ['src/server.js'], {
      cwd: backendDir,
      stdio: 'inherit',
      shell: false,
      env: process.env
    });

    backend.on('close', (code, signal) => {
      if (signal) process.kill(process.pid, signal);
      else process.exit(code);
    });

    backend.on('error', (err) => {
      console.error('Backend process failed:', err);
      process.exit(1);
    });
  } catch (error) {
    console.error(error.message);
    process.exit(1);
  }
}

main();
