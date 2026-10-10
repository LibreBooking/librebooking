import { defineConfig, devices } from '@playwright/test';
import { authFile } from './support/auth';

// The trailing slash matters: relative page.goto('schedule.php') must resolve under /Web/.
const baseURL = process.env.PLAYWRIGHT_BASE_URL ?? 'http://localhost:8088/Web/';

// On the host (no PLAYWRIGHT_BASE_URL) start the Docker stack, or reuse a
// running one; support/stack.mjs removes a stack it started again.
const startStack = !process.env.PLAYWRIGHT_BASE_URL;

export default defineConfig({
  testDir: './specs',
  outputDir: 'test-results',
  reporter: [['list'], ['html', { open: 'never', outputFolder: 'playwright-report' }]],
  retries: process.env.CI ? 1 : 0,
  use: {
    baseURL,
    trace: 'retain-on-failure',
    screenshot: 'only-on-failure',
  },
  webServer: startStack
    ? {
        command: 'node support/stack.mjs',
        url: new URL('index.php', baseURL).href,
        reuseExistingServer: true,
        timeout: 10 * 60 * 1000,
        gracefulShutdown: { signal: 'SIGTERM', timeout: 60 * 1000 },
      }
    : undefined,
  projects: [
    { name: 'setup', testMatch: /auth\.setup\.ts/ },
    {
      name: 'chromium',
      testMatch: /.*\.spec\.ts/,
      use: { ...devices['Desktop Chrome'], storageState: authFile('admin.json') },
      dependencies: ['setup'],
    },
  ],
});
