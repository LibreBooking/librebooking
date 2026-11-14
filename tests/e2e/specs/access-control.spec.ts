import { test, expect } from '@playwright/test';

// Only /Web is meant to be reachable. The root .htaccess and the
// "Require all denied" .htaccess files in the other top-level directories
// answer 403 for everything else, as defense in depth for installations
// whose document root is the project directory.

const FORBIDDEN = 403;

// Paths are relative to the project directory, the parent of /Web/.
const projectUrl = (baseURL: string | undefined, path: string) => new URL(`../${path}`, baseURL).href;

const deniedByRootRules = [
  '.git/config',
  '.env',
  '.env.example',
  'lib/.hidden',
  'vendor/autoload.php',
  'node_modules/',
  'tpl_c/',
  'var/',
  'composer.json',
  'composer.lock',
  'package.json',
  'package-lock.json',
];

// docs/ is left out: Dockerfile.app.dockerignore keeps it, and so its
// .htaccess, out of the application image.
const deniedByDirectory = [
  'ci/',
  'config/config.dist.php',
  'Controls/',
  'database_schema/create-schema.sql',
  'Domain/',
  'Jobs/',
  'lang/',
  'lib/preflight.php',
  'Pages/',
  'phing-tasks/',
  'plugins/',
  'Presenters/',
  'scripts/',
  'src/',
  'tests/',
  'tpl/globalheader.tpl',
  'uploads/',
  'WebServices/AccountWebService.php',
];

test.describe('access control outside /Web', () => {
  for (const path of [...deniedByRootRules, ...deniedByDirectory]) {
    test(`denies /${path}`, async ({ request, baseURL }) => {
      const response = await request.get(projectUrl(baseURL, path), { maxRedirects: 0 });

      expect(response.status()).toBe(FORBIDDEN);
    });
  }

  // ACME (Let's Encrypt) certificate validation needs /.well-known/. The root
  // .htaccess redirects it to /Web/.well-known/, which ACME follows, so neither
  // may be denied. No token exists here, so this cannot show one being served.
  for (const path of ['.well-known/acme-challenge/token', 'Web/.well-known/acme-challenge/token']) {
    test(`does not deny /${path}`, async ({ request, baseURL }) => {
      const response = await request.get(projectUrl(baseURL, path), { maxRedirects: 0 });

      expect(response.status()).not.toBe(FORBIDDEN);
    });
  }
});
