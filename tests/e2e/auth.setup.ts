import { test as setup, expect } from '@playwright/test';
import path from 'path';
import fs from 'fs';

const username = process.env.PLAYWRIGHT_USERNAME ?? 'admin';
const password = process.env.PLAYWRIGHT_PASSWORD ?? 'password';
const authFile = path.join(__dirname, '../playwright/.auth/user.json');
fs.mkdirSync(path.dirname(authFile), { recursive: true });

setup('authenticate', async ({ page }) => {
  // Perform authentication steps. Replace these actions with your own.
  await page.goto('/');
  await page.getByTestId('login-username').fill(username);
  await page.getByTestId('login-password').fill(password);
  await page.getByTestId('login-submit').click();
  // Wait until the page receives the cookies.
  //
  // Sometimes login flow sets cookies in the process of several redirects.
  // Wait for the final URL to ensure that the cookies are actually set.
  await page.waitForURL('**/dashboard.php');
  // Alternatively, you can wait until the page reaches a state where all cookies are set.
  //   await expect(page.getByRole('button', { name: 'View profile and more' })).toBeVisible();

  // End of authentication steps.

  await page.context().storageState({ path: authFile });
});
