import { test as setup, expect, type Page } from '@playwright/test';
import { mkdirSync } from 'node:fs';
import path from 'node:path';
import { authFile } from '../support/auth';

async function logInAndSave(page: Page, username: string, password: string, file: string) {
  mkdirSync(path.dirname(file), { recursive: true });

  await page.goto('index.php');
  await page.locator('#email').fill(username);
  await page.locator('#password').fill(password);
  await page.locator('#login button[type="submit"]').click();

  await expect(page.locator('#page-dashboard')).toBeVisible();
  await page.context().storageState({ path: file });
}

setup('log in as admin', async ({ page }) => {
  await logInAndSave(page, 'admin', 'password', authFile('admin.json'));
});
