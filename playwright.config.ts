import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: 'tests/e2e',
    timeout: 30_000,
    use: {
        baseURL: process.env.PLAYWRIGHT_BASE_URL ?? 'http://localhost:8080',
        screenshot: 'only-on-failure',
        trace: 'retain-on-failure',
    },
    projects: [
        {
            name: 'setup',
            testMatch: '**/*.setup.ts',
        },
        {
            name: 'chromium',
            use: {
                storageState: 'tests/playwright/.auth/user.json',
            },
            dependencies: ['setup'],
        },
    ],
});
