// Runs the Playwright tests in a fresh Docker stack.
//
//   node support/run.mjs           prepare, then test
//   node support/run.mjs prepare   build and pull the images
//   node support/run.mjs test      run the tests
//
// "prepare" holds the long image build and pull output, so that "test" shows
// little more than the Playwright results. The application image is a copy of
// the working tree, so run "prepare" again after changing application code.
// "test" removes the stack again afterwards, also when interrupted, and exits
// with the status of the tests.
import { spawn, spawnSync } from 'node:child_process';
import { fileURLToPath } from 'node:url';

const cwd = fileURLToPath(new URL('..', import.meta.url));
const compose = ['compose', '-p', 'lb-e2e-run', '-f', 'docker/docker-compose.yml'];
const docker = (...args) => spawnSync('docker', [...compose, ...args], { cwd, stdio: 'inherit' }).status ?? 1;
const down = () => docker('down', '-v', '--remove-orphans');

// Returns the exit status of the first step that failed, or 0.
function prepare() {
  down();
  return docker('build') || docker('pull', '--ignore-buildable');
}

// Resolves with the exit status of the tests.
function test() {
  down();

  return new Promise((resolve) => {
    const run = spawn('docker', [...compose, 'run', '--rm', 'playwright'], { cwd, stdio: 'inherit' });

    // Ctrl+C reaches docker directly; stay alive until it has stopped so the stack is removed.
    for (const signal of ['SIGINT', 'SIGTERM', 'SIGHUP']) {
      process.on(signal, () => run.kill(signal));
    }

    run.on('exit', (code) => {
      down();
      resolve(code ?? 1);
    });
  });
}

const [command] = process.argv.slice(2);

switch (command) {
  case undefined:
    process.exit(prepare() || (await test()));
    break;
  case 'prepare':
    process.exit(prepare());
    break;
  case 'test':
    process.exit(await test());
    break;
  default:
    console.error(`Unknown command "${command}"; expected prepare or test`);
    process.exit(2);
}
