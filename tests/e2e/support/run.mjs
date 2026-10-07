// npm run test:e2e:docker: runs the tests in a fresh stack and removes it again,
// also when interrupted, with the exit status of the tests.
import { spawn, spawnSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';

const cwd = fileURLToPath(new URL('..', import.meta.url));
const compose = ['compose', '-p', 'lb-e2e-run', '-f', 'docker/docker-compose.yml'];
const down = () => spawnSync('docker', [...compose, 'down', '-v', '--remove-orphans'], { cwd, stdio: 'inherit' });

down();
const run = spawn('docker', [...compose, 'run', '--rm', '--build', 'playwright'], { cwd, stdio: 'inherit' });

// Ctrl+C reaches docker directly; stay alive until it has stopped so the stack is removed.
for (const signal of ['SIGINT', 'SIGTERM', 'SIGHUP']) {
  process.on(signal, () => run.kill(signal));
}

run.on('exit', (code) => {
  down();
  process.exit(code ?? 1);
});
