import { test, expect } from '@playwright/test';

test('dashboard is reachable', async ({ page }) => {
    await page.goto('/dashboard.php');
    await expect(page.locator('#page-dashboard')).toBeVisible();
});
