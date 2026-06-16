import { defineConfig } from '@playwright/test';

export default defineConfig({
    testDir: 'tests/e2e',
    timeout: 10_000,
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
