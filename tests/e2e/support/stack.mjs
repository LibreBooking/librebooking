// Playwright webServer command: starts the stack and removes it when stopped.
import { spawn, spawnSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';

const cwd = fileURLToPath(new URL('..', import.meta.url));
const files = ['-f', 'docker/docker-compose.yml', '-f', 'docker/docker-compose.host.yml'];
const compose = (...args) => spawnSync('docker', ['compose', ...files, ...args], { cwd, stdio: 'inherit' });

compose('down', '-v', '--remove-orphans');
const up = spawn('docker', ['compose', ...files, 'up', '--build', '--attach-dependencies', 'web'], {
  cwd,
  stdio: 'inherit',
});

let stopping = false;
const stop = () => {
  if (!stopping) {
    stopping = true;
    up.kill('SIGTERM');
  }
};
for (const signal of ['SIGINT', 'SIGTERM', 'SIGHUP']) {
  process.on(signal, stop);
}

up.on('exit', (code) => {
  compose('down', '-v', '--remove-orphans');
  process.exit(stopping ? 0 : (code ?? 1));
});
