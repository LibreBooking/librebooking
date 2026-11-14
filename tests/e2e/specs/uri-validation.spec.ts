import { test, expect, type Page } from '@playwright/test';

// Schedule and Calendar validate the request URI and redirect a rejected
// request to the clean page in the same directory.
// See https://github.com/LibreBooking/librebooking/issues/1590
const pages = [
  { name: 'schedule', script: 'schedule.php', container: '#page-schedule' },
  { name: 'calendar', script: 'calendar.php', container: '#page-calendar' },
];

const cleanUrl = (baseURL: string | undefined, script: string) => new URL(script, baseURL).href;

async function expectCleanPage(page: Page, baseURL: string | undefined, script: string, container: string) {
  await expect(page).toHaveURL(cleanUrl(baseURL, script));
  await expect(page.locator(container)).toBeVisible();
}

for (const { name, script, container } of pages) {
  test.describe(`${name} URI validation`, () => {
    test('loads the clean URL without redirecting', async ({ page, baseURL }) => {
      const response = await page.goto(script);

      expect(response?.status()).toBe(200);
      expect(response?.request().redirectedFrom()).toBeNull();
      await expectCleanPage(page, baseURL, script, container);
    });

    test('redirects a non-numeric schedule id to the clean URL', async ({ page, baseURL }) => {
      await page.goto(`${script}?sid=abc`);

      await expectCleanPage(page, baseURL, script, container);
    });

    test('redirects a script tag in the query to the clean URL', async ({ page, baseURL }) => {
      await page.goto(`${script}?sid=%3Cscript%3Ealert(1)%3C/script%3E`);

      await expectCleanPage(page, baseURL, script, container);
    });

    for (const appended of ['extra', 'extra.php', 'Web/extra.php']) {
      test(`does not render the page for appended path ${appended}`, async ({ page, baseURL }) => {
        const response = await page.goto(`${script}/${appended}`);

        // A web server that does not pass path info to PHP answers 404 itself,
        // which is just as safe as the application redirecting.
        if (response?.status() === 404) {
          await expect(page.locator(container)).toHaveCount(0);
          return;
        }

        await expectCleanPage(page, baseURL, script, container);
      });
    }
  });
}
