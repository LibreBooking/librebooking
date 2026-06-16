import { test, expect } from '@playwright/test';

const BASE_URL = 'http://localhost:8080';

test('dashboard is reachable', async ({ page }) => {
    const response = await page.goto(`${BASE_URL}/dashboard.php`);

    expect(response!.status()).toBeLessThan(400);

    const title = await page.title();
    console.log(`Page title: "${title}"`);
    console.log(`Final URL: ${page.url()}`);

    await expect(page).toHaveTitle('LibreBooking - My Dashboard');
});
