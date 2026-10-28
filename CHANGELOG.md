# CHANGELOG
<!-- markdownlint-configure-file { "blank_lines": { "maximum": 2 } } -->

<!-- version list -->

## v7.1.0 (2026-10-13)

### Bug Fixes

- **auth**: Persist only login data when a user logs in
  ([`5d722d2`](https://github.com/LibreBooking/librebooking/commit/5d722d2ec617dfad5b391a76a0741968ad163436))

- **auth**: Stop the resource display from logging users in
  ([`5e47d6f`](https://github.com/LibreBooking/librebooking/commit/5e47d6f8f191c60bdad09d8bcf19ea8000cbc844))

- **database**: Substitute query parameters in a single pass
  ([`98480ae`](https://github.com/LibreBooking/librebooking/commit/98480aedb6c8a6dc38d4c6f8da10ab0a618eb2ea))

- **schedule**: Skip empty days in wide view time range
  ([`6433570`](https://github.com/LibreBooking/librebooking/commit/64335703927fc73160ab6ff543d02db8ac4014a3))

- **security**: Only book display-enabled resources from the tablet view
  ([`265aca3`](https://github.com/LibreBooking/librebooking/commit/265aca30dbc4f47736f8ba6a1c21ea1c4ab6c192))

- **security**: Remove admin exemptions from kiosk bookings
  ([`fa454d0`](https://github.com/LibreBooking/librebooking/commit/fa454d019a98e67d70a57b44efd8f5b403eacca0))

### Documentation

- Provide a notice about upcoming security release
  ([`3ac590f`](https://github.com/LibreBooking/librebooking/commit/3ac590fd2019a29a235af5d5566d71567ea558c1))

- Render GitHub alerts as admonitions in Sphinx
  ([`8f04a8e`](https://github.com/LibreBooking/librebooking/commit/8f04a8ef2031dcc987ae35d8c94b1177ec0ed4af))

- Replace upcoming security notice with 7.1.0 upgrade notice
  ([`1707c7b`](https://github.com/LibreBooking/librebooking/commit/1707c7b45b23d7e3c295d07e1d338c40790dc1a4))

- **contributing**: Describe automated versioning and releases
  ([`f4ebfae`](https://github.com/LibreBooking/librebooking/commit/f4ebfae5b7354948f8abddadd287136d2eec0569))

### Features

- **security**: Remove remember-me login
  ([`ddfd63f`](https://github.com/LibreBooking/librebooking/commit/ddfd63f7fe169e43eef5c2c74671bdfed4e6f436))

### Testing

- **integration**: Allow a custom sample data file in the setup script
  ([`5894584`](https://github.com/LibreBooking/librebooking/commit/589458411655feb470fe5cbc7521dea19cbb5145))


## v7.0.0 (2026-10-06)

### Bug Fixes

- **i18n**: Correct German check-in/check-out translations
  ([`d134686`](https://github.com/LibreBooking/librebooking/commit/d1346868c603d5dc474ce03e5fc7d62446d3f9ec))

- **install**: Remove the web-based phpScheduleIt migration
  ([`c9a9297`](https://github.com/LibreBooking/librebooking/commit/c9a92971ff3dbe1b4fdcfad7b19e200e87b69960))

- **schedule**: Decode entity-encoded names in resource filter
  ([`76b4cef`](https://github.com/LibreBooking/librebooking/commit/76b4ceff560650e2d768fda42b862a6c87d7d60e))

### Chores

- **AGENTS.md**: Remove mention of master as stable branch
  ([`26bfe22`](https://github.com/LibreBooking/librebooking/commit/26bfe22ef57943579a032c7efc11a71ea5eba68b))

### Documentation

- Clarify commit line length limit
  ([`de1ff8a`](https://github.com/LibreBooking/librebooking/commit/de1ff8aaf872cae21c3abc5b7d0c96748cb4e915))

- **security**: Clarify supported release and fix policy
  ([`d9d07bf`](https://github.com/LibreBooking/librebooking/commit/d9d07bf032d73effb873bbf39f3f34addb282112))

### Refactoring

- **import**: Remove Quartzy import and import landing pages
  ([`06207bb`](https://github.com/LibreBooking/librebooking/commit/06207bb35f071cacf681fda57988b79a64ef89e0))

### Breaking Changes

- **import**: Admin/quartzy_import.php and admin/import.php are removed; only admin/ics_import.php
  remains.

- **install**: The web migration from phpScheduleIt 1.2 is no longer available.


## v6.1.0 (2026-10-03)

### Bug Fixes

- **calendar**: Honor resource group filter when the group empty
  ([`c9920ea`](https://github.com/LibreBooking/librebooking/commit/c9920ea272d6212e87cb6ada7544914bb559ec91))

- **csv**: Close header and add max concurrent column in resources export
  ([`2c8772c`](https://github.com/LibreBooking/librebooking/commit/2c8772c03487591a4d0f0154eafcc3a6c396c3b5))

- **csv**: Drop trailing comma from report CSV
  ([`6fd7f1f`](https://github.com/LibreBooking/librebooking/commit/6fd7f1f515c8081cdbd3dfba8f93a9414eea3655))

- **csv**: Escape double quotes correctly in user and resource exports
  ([`209564f`](https://github.com/LibreBooking/librebooking/commit/209564ff2d71852c194bfbd8c4919dea94cdd34f))

- **csv**: Escape group and reservation exports correctly
  ([`40ae66b`](https://github.com/LibreBooking/librebooking/commit/40ae66bef9c4a6a97491038914aa489d5bbf1f65))

- **csv**: Escape report CSV values and headers for CSV
  ([`c6fe0e1`](https://github.com/LibreBooking/librebooking/commit/c6fe0e126c117fd9bf63326ff3a0c38e91262759))

- **csv**: Fix quote escaping and avoid null admin group in exports csv
  ([`9935df0`](https://github.com/LibreBooking/librebooking/commit/9935df0e6f3371698d3738920dfcea7c85f76dba))

- **csv**: Separate custom attribute headers in users export
  ([`1b51550`](https://github.com/LibreBooking/librebooking/commit/1b5155039ec8637bd8f534810a5a9581c2c7b9ca))

- **embed**: Restore embed.css
  ([`5f9e886`](https://github.com/LibreBooking/librebooking/commit/5f9e886d6d3dc7d83c5ce0a5cb54262989231c73))

- **install**: Require install password before install and upgrade
  ([`ab8b8cd`](https://github.com/LibreBooking/librebooking/commit/ab8b8cd23cd1d05a9ae8f13c358fd5e1e134b914))

### Features

- **database**: Add ExecuteAffectedRows() to database layer
  ([`d561a22`](https://github.com/LibreBooking/librebooking/commit/d561a22bea116f570cb98536de95a95519e864f8))

### Refactoring

- **auth**: Move UserSession building into UserSessionBuilder
  ([`ccb9b07`](https://github.com/LibreBooking/librebooking/commit/ccb9b077e163f575a15bef53b8e5960ff0b68862))

- **calendar**: Simplify CalendarSubscriptionPresenter::PageLoad
  ([`6e81e38`](https://github.com/LibreBooking/librebooking/commit/6e81e385a1c9d539b01624ec70c39ded0ee18954))

- **csv**: Reformat group and reservation CSV templates
  ([`d6fb663`](https://github.com/LibreBooking/librebooking/commit/d6fb6632bf9869cc1ec647a5ee76103d9d16be41))

- **csv**: Reformat report CSV template
  ([`ef3b61d`](https://github.com/LibreBooking/librebooking/commit/ef3b61d24ff800e95efc58a1eec7bc231940d583))

- **csv**: Reformat user and resource CSV templates for readability
  ([`523f775`](https://github.com/LibreBooking/librebooking/commit/523f77580624660cfdea5fb3fc13a7776afcde11))

### Testing

- **csv**: Add tests for group and reservation export templates
  ([`2c9cba0`](https://github.com/LibreBooking/librebooking/commit/2c9cba0935d4da174264639b379067d2d7f8e9f6))

- **csv**: Add tests for the report CSV export template
  ([`5670f50`](https://github.com/LibreBooking/librebooking/commit/5670f501b4a86296d2f384833928080eabd1c3b9))

- **csv**: Add tests for user and resource CSV export templates
  ([`024b04f`](https://github.com/LibreBooking/librebooking/commit/024b04f9872df326e652470a498682eabfabce3f))

- **integration**: Add database tests runnable locally and in CI
  ([`5bf1f57`](https://github.com/LibreBooking/librebooking/commit/5bf1f57e612fda23abba153091c5ac8567fbeedb))


## v6.0.0 (2026-10-01)

### Bug Fixes

- **calendar**: Disable subscription link during loading
  ([`b0036e0`](https://github.com/LibreBooking/librebooking/commit/b0036e022a99899963ff91b40b3daee97b91c8e1))

- **email**: Clear attachments before each send to stop them leaking
  ([`8c74172`](https://github.com/LibreBooking/librebooking/commit/8c741725e1b1a6b730448c6d27437850d4c6e42b))

- **i18n**: Extend and correct the Russian translation
  ([`0439874`](https://github.com/LibreBooking/librebooking/commit/043987478b4739c8bd827bbe47db2555f9c12a30))

- **ics**: Fix ICS subscription key generation if empty
  ([`ccaa611`](https://github.com/LibreBooking/librebooking/commit/ccaa6116a1d26834644e793e4178e86f43eebdbd))

- **lang**: Update HTML lang codes for multiple language files
  ([`a156fc1`](https://github.com/LibreBooking/librebooking/commit/a156fc1e320ea6609233486557ac801d29a2c5c0))

- **schedule**: Correct schedule style icons on mobile devices
  ([`11f81db`](https://github.com/LibreBooking/librebooking/commit/11f81db494193615cd90e5b26f55bd37b0f8e29a))

- **schedule**: Fix anonymous slots in SlotFactory for ics
  ([`b676b6f`](https://github.com/LibreBooking/librebooking/commit/b676b6f86b52647cf95dd3018f413a4baf45e311))

- **schedule**: Normalize phone schedule styles
  ([`e40db27`](https://github.com/LibreBooking/librebooking/commit/e40db274ee2863cc78f0646e50455b4694e68a70))

- **schedule**: Remove empty query strings
  ([`7606a05`](https://github.com/LibreBooking/librebooking/commit/7606a054d9d407c77af777223d3a94398391851a))

### Chores

- Update DataTables suite to 3.0.1 and pdfmake 0.2.7
  ([`cbeb5d5`](https://github.com/LibreBooking/librebooking/commit/cbeb5d52dc1f1d0da856b8650f13d5cd13127fd5))

- Update select2 to 4.1.0
  ([`dec67b9`](https://github.com/LibreBooking/librebooking/commit/dec67b92d8d06278f65de83a751a495b6d481e22))

### Documentation

- **security**: Direct vulnerability reports to private form
  ([`3f92ba2`](https://github.com/LibreBooking/librebooking/commit/3f92ba22be6b3e0b1deeb3504851cd37dda6dab3))

### Features

- **calendar**: Show a descriptive name in ICS subscription feeds
  ([`d4fbb90`](https://github.com/LibreBooking/librebooking/commit/d4fbb900064ad64f9784be21fdec1f8cfb321c96))

- **email**: Enhance attachment handling with MIME type support
  ([`913581c`](https://github.com/LibreBooking/librebooking/commit/913581c2c6f819d1d3b5f61fb03d80d1a69fb8d2))

- **password**: Enhance password complexity requirements and validation
  ([`d1d27d1`](https://github.com/LibreBooking/librebooking/commit/d1d27d1db7e42f37a9cbf5a1445a6312f1f99f8f))

- **password**: Rename password minimum letters to minimum length
  ([`c811eb1`](https://github.com/LibreBooking/librebooking/commit/c811eb1caed012dc45c968d9a0875bf6753a7b1f))

### Refactoring

- Restore GetPageSize method implementation
  ([`01f8eef`](https://github.com/LibreBooking/librebooking/commit/01f8eef5549d1fc789a7462aca50c50f4f8dcee7))

- **calendar**: Replace subscription link with button in calendars
  ([`0c43a41`](https://github.com/LibreBooking/librebooking/commit/0c43a41d03a3779d40b9f150bc4c60e4114f80f4))

### Breaking Changes

- **password**: Config.php 'password.minimum letters' has been renamed to 'password.minimum length'


## v5.3.0 (2026-08-03)

### Bug Fixes

- **admin**: Restrict group role assignment to application administrators
  ([`12f3d01`](https://github.com/LibreBooking/librebooking/commit/12f3d0163f7224a207fdd3cec99d997458cfc12f))

- **admin**: Restrict remaining user management actions to application admins
  ([`2fc994e`](https://github.com/LibreBooking/librebooking/commit/2fc994ecff06b28b545f62a292a5acefbb85c7f8))

- **admin**: Restrict user and group CSV export to application admins
  ([`e15fabd`](https://github.com/LibreBooking/librebooking/commit/e15fabd79fa457eb2c0d8e3b0a82ca6487ee84bb))

- **admin**: Restrict user creation to application administrators
  ([`fe65f2a`](https://github.com/LibreBooking/librebooking/commit/fe65f2a0486a882e90762c73ed809cd01b3d9b77))

- **admin**: Restrict user deletion to application administrators
  ([`0f7e90a`](https://github.com/LibreBooking/librebooking/commit/0f7e90a8e44d6d5f6619e5868d0e318705cd456a))

- **admin**: Restrict user editing to application administrators
  ([`0a34834`](https://github.com/LibreBooking/librebooking/commit/0a348344cb1dc61f1e3c923f804be282f863ea3b))

- **admin**: Restrict user password changes to application administrators
  ([`eb54acb`](https://github.com/LibreBooking/librebooking/commit/eb54acbe1195c13a3946ab469dee4212e20d6879))

- **admin**: Validate user reservation colors
  ([`72fbeca`](https://github.com/LibreBooking/librebooking/commit/72fbeca380ba3f108773cd355ab31b62446be72c))

- **apache**: Use Apache 2.4 authorization directives
  ([`5461ae4`](https://github.com/LibreBooking/librebooking/commit/5461ae44fa2c11ca597f64753c8533b4cf32e518))

- **auth**: Limit and classify Microsoft OAuth callback logging
  ([`c95630c`](https://github.com/LibreBooking/librebooking/commit/c95630c8e15e228851f2e0450776cebae4cf55af))

- **auth**: Preserve redirect target through Microsoft OAuth login
  ([`2911ee3`](https://github.com/LibreBooking/librebooking/commit/2911ee3402092c875f41f61c01bbeba2a2af5712))

- **auth**: Refactor microsoft-auth to make it testable
  ([`105a051`](https://github.com/LibreBooking/librebooking/commit/105a051425ffbf71173bd82c03307f51b50fd6f7))

- **config**: Correct suggested script URL ports
  ([`9b9fd8a`](https://github.com/LibreBooking/librebooking/commit/9b9fd8acce3f7ade44af110db85e9b066d9c972d))

- **config**: Warn all users when script.url is empty
  ([`97c43ad`](https://github.com/LibreBooking/librebooking/commit/97c43addc3dfcd8fe4daee46c2798caf37d13595))

- **dashboard**: Handle schedule availability windows
  ([`dcf1f99`](https://github.com/LibreBooking/librebooking/commit/dcf1f99315756f8cf9431383d69daf61e1aeab69))

- **email**: Render resource description/notes as rich text in confirmation email
  ([`191545f`](https://github.com/LibreBooking/librebooking/commit/191545fff20bbf61b305f71f4fe2fa8d982933c7))

- **iCalendar**: Log error if private organizer email is not configured
  ([`bf7a42c`](https://github.com/LibreBooking/librebooking/commit/bf7a42c5053a0b007ee046c9fa42e95214b49aa9))

- **language**: Fix missing French strings
  ([`507da30`](https://github.com/LibreBooking/librebooking/commit/507da30229c059e1358ee4dea8ee8efdad08e29d))

- **language**: Fix numerous French typos and formatting errors
  ([`2b38301`](https://github.com/LibreBooking/librebooking/commit/2b383013b90990ccd1ee3aa117b4971c474ce67b))

- **reservations**: Pass start/end dates to Reservation.init()
  ([`eba0de8`](https://github.com/LibreBooking/librebooking/commit/eba0de8fe77048d34baa0fcd7ecd418a9c2ec293))

- **reservations**: Stop browser freeze from unbounded date-advance loop
  ([`4b079de`](https://github.com/LibreBooking/librebooking/commit/4b079dee4417d0fa71ce4b716e87d4669165730c))

### Code Style

- Update schedule links and individualDates spacing
  ([`6ba60b1`](https://github.com/LibreBooking/librebooking/commit/6ba60b136e34c5f911546472d065d0634fa34c3d))

### Continuous Integration

- Added manual triggers for some CI jobs.
  ([`88530f7`](https://github.com/LibreBooking/librebooking/commit/88530f7e07c678d2d5e92ffe323e62cd03217b39))

- Stop running CI on push to develop/master for two CI checks
  ([`a124720`](https://github.com/LibreBooking/librebooking/commit/a124720de8d2abdc98237aecb7bdc3cf6c1b3c1f))

- **commitlint**: Validate pull request commit messages
  ([`13098d0`](https://github.com/LibreBooking/librebooking/commit/13098d0db70925ea6b7240a0f9c31d57fa85b9a9))

- **composer**: Validate optimized PSR autoloading
  ([`e076411`](https://github.com/LibreBooking/librebooking/commit/e076411af08cb6c911dccd4257206aaaae43cb07))

### Documentation

- Add administration guide ported from project wiki
  ([`4feb9e0`](https://github.com/LibreBooking/librebooking/commit/4feb9e07e73d1e7c5f5d16a1a203d29aeaa85d8b))

- Correct documentation about some of the upload directories
  ([`add7ebf`](https://github.com/LibreBooking/librebooking/commit/add7ebfbe23114f94720bb23ea41774a4f74af1b))

- Describe each background job in the installation guide
  ([`ca63799`](https://github.com/LibreBooking/librebooking/commit/ca63799f45db7f5050f93d2bc2ecfc1bd1308490))

- Group the documentation table of contents by audience
  ([`e7adb1a`](https://github.com/LibreBooking/librebooking/commit/e7adb1a548c18a4290ed76436439154b7b46dfa3))

- Replace dead GitHub wiki links with ReadTheDocs
  ([`496621d`](https://github.com/LibreBooking/librebooking/commit/496621d48718cab1ffc3e03ce0604c37dd8a82b5))

- **admin**: Backtick config section names and boolean values
  ([`3ccb7d6`](https://github.com/LibreBooking/librebooking/commit/3ccb7d6cda47e1f3207f8cf1370b45fbd542c039))

- **admin**: Fix grammar and typos in ADMINISTRATION.rst
  ([`ec52a1f`](https://github.com/LibreBooking/librebooking/commit/ec52a1fc9eaae90d584e3c315d1922cd24a1e26a))

- **admin**: Nest related sections under their parent heading
  ([`8c3294e`](https://github.com/LibreBooking/librebooking/commit/8c3294e8ec966f7e92d2e004b76e8457f64a4ea2))

- **admin**: Normalize quote style and config-key formatting
  ([`c59d4c7`](https://github.com/LibreBooking/librebooking/commit/c59d4c7b01313eedef66ff99a97373cb2211f9d8))

- **admin**: Replace en dashes with ASCII characters
  ([`755ed61`](https://github.com/LibreBooking/librebooking/commit/755ed61e0196e2af0f21a79cc50503985a410acc))

- **config**: Document required script URL setting
  ([`bd3710c`](https://github.com/LibreBooking/librebooking/commit/bd3710c313f427464940105a90335f36a4396ed1))

### Features

- **calendar**: Migrate iCalendar export to sabre/vobject
  ([`9c8bf6b`](https://github.com/LibreBooking/librebooking/commit/9c8bf6ba0d79b12c4132b10d49b5d3250db844cb))

- **clipboard**: Add copy URL to clipboard functionality and toast notifications for ics and atom
  links
  ([`f2d0496`](https://github.com/LibreBooking/librebooking/commit/f2d0496318dc08f85da69cd7a843431ba3754189))

- **config**: Warn when script.url is missing the required /Web suffix
  ([`a829297`](https://github.com/LibreBooking/librebooking/commit/a82929761626c7f69b3764b92a5fd8f516d0290d))

- **demo**: Add demo landing page with cold-start spinner
  ([`85288a0`](https://github.com/LibreBooking/librebooking/commit/85288a0721b7853c28373bad13a5fbc976b45c0a))

### Refactoring

- **autoload**: Add initial PSR-4 support
  ([`125c9ea`](https://github.com/LibreBooking/librebooking/commit/125c9eabc04501acdb54def4701f2b7f79e2b587))

- **config**: Build suggested script URLs with URI library
  ([`57a6a85`](https://github.com/LibreBooking/librebooking/commit/57a6a85a69b562ae2dec7ce31cc9d3efe4943284))

- **reservations**: Initialize reservation with rendered $StartDate and $EndDate
  ([`2c79e5f`](https://github.com/LibreBooking/librebooking/commit/2c79e5f3c8fb77407abd7b023541af64a1894096))


## v5.2.0 (2026-07-03)

### Bug Fixes

- Allow custom plugin class names in plugin configuration
  ([`61e34d9`](https://github.com/LibreBooking/librebooking/commit/61e34d96a86023f4574af695ee023715cde20a3d))

- **calendar**: Apply resource event colors and fix create reservation button display
  ([`2ed1348`](https://github.com/LibreBooking/librebooking/commit/2ed1348ed198736b15e879f3aab94bd3010935dc))

- **deps**: Improve Dependabot rebase workflow reliability
  ([`85e9ba5`](https://github.com/LibreBooking/librebooking/commit/85e9ba59a938a0fee38a97e54c766fd4cef14029))

- **ics**: Escape calendar text fields for RFC 5545
  ([`6b26ed3`](https://github.com/LibreBooking/librebooking/commit/6b26ed33c510dfffc90db293da43cf7c6d079300))

- **ics**: Use reservation notes as description
  ([`b1ee07f`](https://github.com/LibreBooking/librebooking/commit/b1ee07f3608a3fef7c4726ab9b1be894b4a7aba5))

- **pages**: Delegate RedirectResume in page decorators to fix TypeError
  ([`8766086`](https://github.com/LibreBooking/librebooking/commit/876608621b3b281d312869b4e920392bc85bf187))

- **payments**: Require php-intl extension for currency formatting
  ([`ccd9023`](https://github.com/LibreBooking/librebooking/commit/ccd90236dc64645f3889e4efb684493dde68c949))

- **reservations**: Prevent false success for no-op updates
  ([`e00354b`](https://github.com/LibreBooking/librebooking/commit/e00354b2147006bf7da35576014074cb643b8b84))

- **x-editable**: Prevent invalid JSON in data-source for custom attributes
  ([`99ce7dd`](https://github.com/LibreBooking/librebooking/commit/99ce7dd4ce81e2054e76894621b59e108e6e3a82))

### Continuous Integration

- **deps**: Add hourly workflow to rebase out-of-date Dependabot PRs
  ([`1c74e0a`](https://github.com/LibreBooking/librebooking/commit/1c74e0ae48765e14abe5d17d8d2099784c334037))

- **deps**: Remove the dependabot rebase workflow
  ([`d5341fb`](https://github.com/LibreBooking/librebooking/commit/d5341fbebc19105c70f7c0167640c5cc30302dfa))

### Documentation

- Add custom plugin development guide
  ([`3a9ef5e`](https://github.com/LibreBooking/librebooking/commit/3a9ef5ef8cc87af5f4e1a02736d8ec206dfc2dc5))

- **config**: Enhance privacy settings descriptions and comments for clarity
  ([`cdfc3fd`](https://github.com/LibreBooking/librebooking/commit/cdfc3fd18593eb247d95da11bb88b004b68501b8))

### Features

- Move custom logo & favicon to configurable upload directory
  ([`e715182`](https://github.com/LibreBooking/librebooking/commit/e7151825002eedec106e1eb3e37a4733d8fe3a56))

- **config**: Add ConfigKey readonly class for typed config definitions
  ([`2b0c560`](https://github.com/LibreBooking/librebooking/commit/2b0c5608266f51ac77d416262f9011d13e5da6ee))

- **ics**: Add global config switch to enable/disable ICS/Atom feeds
  ([`6be95ef`](https://github.com/LibreBooking/librebooking/commit/6be95efde1c932aad9ffb5be4c7185f945fc1fbf))

### Refactoring

- **config**: Add ConfigKey::fromArray() and accept it in registries
  ([`8c28d0d`](https://github.com/LibreBooking/librebooking/commit/8c28d0d8ea0fce3797fd57b7acc0caff16dca47e))

- **config**: Drop dead array branches from the typed boundary
  ([`b26019b`](https://github.com/LibreBooking/librebooking/commit/b26019be6af4e0cad06f7eff839107fe594f4611))

- **config**: Emit typed ConfigKey from the read boundary
  ([`b6ff323`](https://github.com/LibreBooking/librebooking/commit/b6ff3237ed535adc56cfada5305e3000c7246a15))

- **config**: Support ConfigKey instances in AbstractConfigKeys
  ([`1273993`](https://github.com/LibreBooking/librebooking/commit/1273993c1b8b198f42b7367b048546f5535a6a76))

- **custom-attributes**: Replace ID column with name and prefixed identifier
  ([`3520a03`](https://github.com/LibreBooking/librebooking/commit/3520a032ab2b839960cdcd691915ccafa46128cd))

- **ics**: Extract shared subscription page base
  ([`a22c30b`](https://github.com/LibreBooking/librebooking/commit/a22c30b67b38fd3b228d3f2d57f890bbfb109249))


## v5.1.0 (2026-06-07)

### Bug Fixes

- **harden-email-templates**: Reject path traversal in template name
  ([`cb9b7ad`](https://github.com/LibreBooking/librebooking/commit/cb9b7ad9da0243bd105809f6a4a8a6b9147c71ea))

- **harden-rich-text**: Sanitize announcement and resource output
  ([`7ed2b9c`](https://github.com/LibreBooking/librebooking/commit/7ed2b9cf7e2a2fd2cb70536f5e7c22d4fd861736))

- **harden-rich-text**: Sanitize announcement email rich text
  ([`cc30b15`](https://github.com/LibreBooking/librebooking/commit/cc30b1549e18faaa72955da64840e7b890148b58))

- **harden-rich-text**: Sanitize rich text in admin views
  ([`688e6aa`](https://github.com/LibreBooking/librebooking/commit/688e6aa7064f6555a19ef1a5125418ae25818429))

- **harden-url2link**: Only linkify safe http(s) URLs and valid emails
  ([`fa75dba`](https://github.com/LibreBooking/librebooking/commit/fa75dba84bd135632597b48e73802aced166e054))

- **reservations**: Prevent attachment filename stored XSS
  ([`51a9ffc`](https://github.com/LibreBooking/librebooking/commit/51a9ffcd8890462ebb756f994589174d58c7f604))

### Build System

- **composer**: Add Symfony HTML sanitizer dependency
  ([`ee27dd0`](https://github.com/LibreBooking/librebooking/commit/ee27dd057de308908f97bf6cd9f2c7cb99592489))

### Documentation

- **agents**: Document rich text sanitization guidance
  ([`6d7b554`](https://github.com/LibreBooking/librebooking/commit/6d7b55427f10c6cb7cfe0b29b73193cf71cb5d1f))

- **agents**: Update guidance from recent project patterns
  ([`e48f2f5`](https://github.com/LibreBooking/librebooking/commit/e48f2f58aeb5e345086c88fa1ff3977c714c243c))

- **security**: Clarify trust boundary for admin-authored content
  ([`437e3c8`](https://github.com/LibreBooking/librebooking/commit/437e3c8c01866ebe5f2594a96e0bcb9637dcd699))

### Features

- **security**: Add rich text HTML sanitizer
  ([`c1a59b5`](https://github.com/LibreBooking/librebooking/commit/c1a59b50beed35407d936791d599efedb1bd0251))

- **security**: Add sanitize_rich_text Smarty modifier
  ([`b510537`](https://github.com/LibreBooking/librebooking/commit/b510537487bc039d3e6eb84f1d46ded85320b6b0))


## v5.0.3 (2026-06-03)

### Bug Fixes

- 404 on plus icon in condensed week schedule display
  ([`c02df1a`](https://github.com/LibreBooking/librebooking/commit/c02df1aed1184475082b509c0d6f38da92fbf5b4))

- Changes to the date formats for the german translation
  ([`1486841`](https://github.com/LibreBooking/librebooking/commit/1486841378fd1be6879ee3c6ac50cf09e6a9b0d1))

- Grammar and spelling errors fixes for german localization
  ([`5a5d5a1`](https://github.com/LibreBooking/librebooking/commit/5a5d5a127bfa24e4495a3d7abc583fe2200e7240))

- Updated and added german translation for E-Mail-Templates
  ([`cc9bcc6`](https://github.com/LibreBooking/librebooking/commit/cc9bcc67a85e0192519deee4d194fa3750b74596))

- **auth**: Block unsafe external redirect targets
  ([`813c232`](https://github.com/LibreBooking/librebooking/commit/813c232cf72aecf95848d01b36bf4faaf72415ef))

- **DeprecatedPHP8.5**: Transforming null values ​​to an empty string
  ([`d4e2d44`](https://github.com/LibreBooking/librebooking/commit/d4e2d442b2b6fcc866c95f02fe5ecc1576a4949c))

- **reservation.label**: Avoid null replacement in attribute label formatting
  ([`6ff0ecc`](https://github.com/LibreBooking/librebooking/commit/6ff0eccd2c039964115d6f4eb8b881330a850194))

- **reservations**: Validate reservation owner on create
  ([`bd087cd`](https://github.com/LibreBooking/librebooking/commit/bd087cd215b077d1a55132bf354ef07a50b947b5))

- **schedule**: Centralize render completion and auto-scroll
  ([`2c58149`](https://github.com/LibreBooking/librebooking/commit/2c581490e0a4881c0f57ae526ee48259079f16a4))

- **schedule**: Improved accessibility in schedule labels
  ([`adcc7a9`](https://github.com/LibreBooking/librebooking/commit/adcc7a971ad20e3120acefb8defcd0d5c9a2587f))

### Code Style

- Add missing block data-bs-theme for cake_blue theme
  ([`65e50c9`](https://github.com/LibreBooking/librebooking/commit/65e50c91f0e932c7b952519864da8ee6a25ac149))

- Remove an accidental trailing Z in the last line
  ([`47acb61`](https://github.com/LibreBooking/librebooking/commit/47acb61dbeddb2686444ce8b04af40e2a6e8f8e1))

### Documentation

- **security**: Clarify vulnerability reporting policy
  ([`4e2467b`](https://github.com/LibreBooking/librebooking/commit/4e2467b42dd3b4d63aded34f0011453b9e4eaf05))

- **security**: Document administrator trust model
  ([`0c84051`](https://github.com/LibreBooking/librebooking/commit/0c84051f2a801a259f7e89e6f3b461335f66beeb))

### Refactoring

- **reservation**: UI improvements
  ([`6ce75dc`](https://github.com/LibreBooking/librebooking/commit/6ce75dc3e7e688861487f11e9ca83ea7a85eead0))


## v5.0.2 (2026-05-04)

### Bug Fixes

- **theme**: Enhance color contrast for better accessibility
  ([`5cf0301`](https://github.com/LibreBooking/librebooking/commit/5cf0301d02f713d68087632bd9b027ccdf50db83))


## v5.0.1 (2026-05-03)

### Bug Fixes

- **accessibility**: Enhance UI elements with improved ARIA labels and visual hierarchy
  ([`da4f60e`](https://github.com/LibreBooking/librebooking/commit/da4f60e98677819842ab64dcbba854da35f3cb73))


## v5.0.0 (2026-05-03)

### Bug Fixes

- Improve 'Info' string for `en_us`
  ([`6773f92`](https://github.com/LibreBooking/librebooking/commit/6773f925fc2e9fb8b3116aeffd6a644ca334a6de))

- **config**: Fix the default 'x-xss' value in config.dist.php
  ([`a3a9414`](https://github.com/LibreBooking/librebooking/commit/a3a9414dfeba83a13a7f1f0c9b9f77654361eca5))

- **config**: Update default config.dist.php
  ([`3e5b0aa`](https://github.com/LibreBooking/librebooking/commit/3e5b0aa225d2a0ddc64831ed3634f06ba769492c))

- **config**: Use section-qualified env names for plugin config
  ([`54bcd2c`](https://github.com/LibreBooking/librebooking/commit/54bcd2c2cbcc5a2240bb01c57fd392d7a165cf92))

- **datatables**: Add data-order attributes for date in various templates
  ([`b77a9d7`](https://github.com/LibreBooking/librebooking/commit/b77a9d725b748f32853f86b52df46454b44e0f7f))

- **datepicker**: Fix Flatpickr 1px calendar width on hidden date pickers
  ([`259bc36`](https://github.com/LibreBooking/librebooking/commit/259bc36043b0d7cc1785edc3e55d4ec388f5f0bd))

- **ldap**: Suppress net_ldap2 deprecations during connect
  ([`70e3b12`](https://github.com/LibreBooking/librebooking/commit/70e3b12d595a61ff2e7350106aa52bdc8afee8c0))

- **reservation-extensions**: Update default list of allowed extensions
  ([`03731ea`](https://github.com/LibreBooking/librebooking/commit/03731ea5eeb8a472732d1ccac392390b3dda89c8))

- **resource-types**: Load custom attribute values without entity join
  ([`f211492`](https://github.com/LibreBooking/librebooking/commit/f211492cae59143a39ab2d732499d8224e4ccae9))

- **schedule**: Replace html2canvas with html2canvas-pro to support color-mix()
  ([`740bee0`](https://github.com/LibreBooking/librebooking/commit/740bee0950380c2d4ea11cc0a312ee05fc82e201))

- **security**: Remove obsolete X-XSS-Protection header support
  ([`f594942`](https://github.com/LibreBooking/librebooking/commit/f59494294db1e13072dfed759a8bb8fd238201c2))

- **ui**: Align schedule toolbar icons and update icon images
  ([`6ea3ff1`](https://github.com/LibreBooking/librebooking/commit/6ea3ff15ba89c853e9eeb512ff6e6791abd3ccd8))

### Documentation

- Add AI attribution policy and fix target branch
  ([`ec377c4`](https://github.com/LibreBooking/librebooking/commit/ec377c43c5faba7ef7507deb74473110755e1caa))

- Document maintenance mode
  ([`ae583d8`](https://github.com/LibreBooking/librebooking/commit/ae583d8a78207dbaeefb48291f27a6e73f6aacb1))

- Update README images for new icons and reports page
  ([`59eaf32`](https://github.com/LibreBooking/librebooking/commit/59eaf32814f809d740054e47c0b20bec0923388c))

- **config**: Document language string overrides
  ([`678202e`](https://github.com/LibreBooking/librebooking/commit/678202eae2d1b8d8021c6b74bdd85beceabab511))

- **lang-overrides**: Add an example config file and update docs
  ([`3077dcd`](https://github.com/LibreBooking/librebooking/commit/3077dcd0ebb02d3e0235fc84639750bf6bbd9be4))

### Features

- **api**: Add groupId filter to GET Resources endpoint
  ([`37273ad`](https://github.com/LibreBooking/librebooking/commit/37273ad3bc3965e96beb68da0d182ddbb079cc84))

- **api**: Return 404 for non-existent scheduleId in GET Resources endpoint
  ([`ccb5d3d`](https://github.com/LibreBooking/librebooking/commit/ccb5d3db2befe97c8676de12d8b74419ccbcb46e))

- **config**: Add .env.example generator from ConfigKeys metadata
  ([`3375f28`](https://github.com/LibreBooking/librebooking/commit/3375f28566bd1cba2268f417bab03d4425de7565))

- **config**: Add config.dist.php generator from ConfigKeys metadata
  ([`d4805e6`](https://github.com/LibreBooking/librebooking/commit/d4805e6f6461dcd6bbb9d184660b535423897a32))

- **config**: Add DeprecatedConfigKeys registry for removed config keys
  ([`909f7f0`](https://github.com/LibreBooking/librebooking/commit/909f7f0187d175a86a0081d3ef8ca8ed6c8ec765))

- **DatePickerSetupControl**: Improved resolution of the alternative format and documentation.
  ([`e9e6a3f`](https://github.com/LibreBooking/librebooking/commit/e9e6a3fb7f95bd828ab9a1078fcb342341231365))

- **i18n**: Add per-language string overrides
  ([`67a22b8`](https://github.com/LibreBooking/librebooking/commit/67a22b8a0caf782f102f6351b2a0fbf864341996))

- **schedule**: Improve default schedule notification and highlight current calendar
  ([`a3cc88c`](https://github.com/LibreBooking/librebooking/commit/a3cc88c950020c9742f2eb8c096e4ac6e03f8322))

### Refactoring

- **api**: Extract helper to parse comma-separated positive integer IDs
  ([`1e58e1d`](https://github.com/LibreBooking/librebooking/commit/1e58e1d4c3c6189212da1db18729c01476b6cadb))

- **availability**: Simplify template using section config loop
  ([`82f14da`](https://github.com/LibreBooking/librebooking/commit/82f14da1ab5abfd2af0702c2e26c69509ed44b1e))

- **config**: Extract ConfigKeysMeta into its own file
  ([`26c7219`](https://github.com/LibreBooking/librebooking/commit/26c72192db924ec1687ab542aeaebe990ae0b951))

- **config**: Remove app version from ConfigKeys
  ([`1c1f63e`](https://github.com/LibreBooking/librebooking/commit/1c1f63e65645a86dc8be78ca3ba043289c14151c))

- **date-helper**: Use dateHelper.formatDate for date formatting in reservation.js
  ([`4088480`](https://github.com/LibreBooking/librebooking/commit/408848000eb3091bc01424437073e883f0b424f3))

- **FullCalendar**: Update FullCalendar to v6.1
  ([`661db3d`](https://github.com/LibreBooking/librebooking/commit/661db3d5964ed59219a794faf99c0cf4bad0c2dd))

- **inlineAttributeEdit**: Simplify inline DATETIME editing with native Flatpickr
  ([`14c3c95`](https://github.com/LibreBooking/librebooking/commit/14c3c952599853d61049360ad5c0ec55788b14e2))

- **ldap**: Switch LDAP server config from host/port to uri
  ([`bd01c04`](https://github.com/LibreBooking/librebooking/commit/bd01c04149e5ae58a9da1fdf1a0a1a25efa43614))

- **reservation**: Build PDF config in PHP
  ([`5d74666`](https://github.com/LibreBooking/librebooking/commit/5d7466680fbda177a2c4f3ad3e5c38fc4e1704f5))

- **reservation**: Migrate PDF generation to pdfmake
  ([`10a2db0`](https://github.com/LibreBooking/librebooking/commit/10a2db09388469d10a1325f9dc7a742d85c8eb83))

- **schedule**: Use of color-mix, legacy cleanup, and visual improvements
  ([`ffebae5`](https://github.com/LibreBooking/librebooking/commit/ffebae5c2639dcdab724b9cf5e6f26e8918db86a))


## v4.3.0 (2026-04-03)

### Bug Fixes

- Allow special characters in passwords for user creation
  ([`a903be1`](https://github.com/LibreBooking/librebooking/commit/a903be1c2b48237aab856babb3b73c5ea6791aae))

- Correct check-in status display in resource details popup
  ([`ed3dcbc`](https://github.com/LibreBooking/librebooking/commit/ed3dcbcf1115bc108a193c33c2d96bbfd3ca4bb7))

- Improve tooltip rendering and fix Smarty issues
  ([`3eb0179`](https://github.com/LibreBooking/librebooking/commit/3eb01796d7732ce92b200f1c0df240b014c98a92))

- Show the Custom Attribute label name when deleting
  ([`b1b06e5`](https://github.com/LibreBooking/librebooking/commit/b1b06e55b3ba7d4b733c2eeeae8084110ac09495))

- Update the reservation email templates for various languages
  ([`667ddc3`](https://github.com/LibreBooking/librebooking/commit/667ddc342eabcd14788d128b4fd64cfc59b29ca8))

- Update the reservation email templates for various languages
  ([`e94ad11`](https://github.com/LibreBooking/librebooking/commit/e94ad114e34d9c8aad37a41515e2be716d389d1b))

- **admin**: Prevent permission loss when DataTables paginates resources
  ([`cec8823`](https://github.com/LibreBooking/librebooking/commit/cec8823be81946b47d82b8d3695d09a19037bac5))

- **auth**: Use per-user database language when no language cookie is set
  ([`b2437db`](https://github.com/LibreBooking/librebooking/commit/b2437db9302d5c8a6bb8e62294b8beaabb561293))

- **blackouts**: Require repeat-until in recurrence UI
  ([`8840ec0`](https://github.com/LibreBooking/librebooking/commit/8840ec0ad38a7be9f98c452101345ece3188aa7f))

- **blackouts**: Require termination date for recurring blackouts
  ([`4cda32d`](https://github.com/LibreBooking/librebooking/commit/4cda32d33c8c27aa825129a9c086358a7321f3e3))

- **calendar**: Generate correct reservation detail URLs server-side for guest views
  ([`25e2391`](https://github.com/LibreBooking/librebooking/commit/25e239185fd128ba00b2673de89c0b9b9439f4b9))

- **calendar**: Pass correct groupSeriesByResource boolean instead of privacyFilter object
  ([`5a7d9ee`](https://github.com/LibreBooking/librebooking/commit/5a7d9ee5c32b78b0ecdacc49ba561b7391e6952a))

- **config**: Avoid empty buckets when rewriting legacy keys
  ([`e091b3f`](https://github.com/LibreBooking/librebooking/commit/e091b3f6268bbd1abddc532742f2a649269872c1))

- **config**: Make config key lookup case-insensitive
  ([`822ccfe`](https://github.com/LibreBooking/librebooking/commit/822ccfeeffa4ac9ad73ff319fe974e8dab370fec))

- **config**: Preserve existing config values when merging new keys from config.dist.php
  ([`e2006d1`](https://github.com/LibreBooking/librebooking/commit/e2006d1b4d9751ff1013abfbd2279450f13df9fe))

- **config**: Reject case-insensitive key collisions
  ([`eeea6f9`](https://github.com/LibreBooking/librebooking/commit/eeea6f947ebbf096f894d5be8a0de72baae06097))

- **config**: Restore legacy config key mappings
  ([`74db95a`](https://github.com/LibreBooking/librebooking/commit/74db95a277a2bbc1342ae6b761e2dd480774b5b7))

- **config**: Rewrite legacy section keys into canonical sections
  ([`64964cb`](https://github.com/LibreBooking/librebooking/commit/64964cbd0c29b9ac8ecf616a2af424e3b47a4080))

- **config**: Treat empty-string section as unsectioned in GetKey
  ([`7241c59`](https://github.com/LibreBooking/librebooking/commit/7241c5910293e5015748c882cb14398c530ce673))

- **database**: Make 2.9+ upgrades idempotent and raise DB minimums
  ([`3f632d4`](https://github.com/LibreBooking/librebooking/commit/3f632d4e1314c7a0759216c4140e4d5019e21b18))

- **dataTable**: Add default page size configuration and update DataTable length menu
  ([`a959247`](https://github.com/LibreBooking/librebooking/commit/a959247325f2c2d623c88dc24c5e29d802d8e444))

- **email**: Correctly render reservation attributes in created emails
  ([`0d2bce9`](https://github.com/LibreBooking/librebooking/commit/0d2bce906340259d7e11f4d9b2c92a53ab1ff0a9))

- **email**: Honor secondary category in reservation email attributes
  ([`42c98d5`](https://github.com/LibreBooking/librebooking/commit/42c98d53437e8e00137160b7ab8500d5a6b2b4e3))

- **email**: Populate resource custom attribute values when loading reservation from DB
  ([`5a9756e`](https://github.com/LibreBooking/librebooking/commit/5a9756e6928b992a4307272bc1ffb9bdcaa12042))

- **email**: Restore submitter language after sending approval notification emails
  ([`e33a6ab`](https://github.com/LibreBooking/librebooking/commit/e33a6ab61a432ec041c802e40209f9d10685f55f))

- **en_gb**: Show only time in popup
  ([`5999309`](https://github.com/LibreBooking/librebooking/commit/59993090ecba499135823d7558487b4302caddef))

- **i18n**: Add English fallback for 3 languages missing parent translation calls
  ([`e202d44`](https://github.com/LibreBooking/librebooking/commit/e202d448a818f0cd2dfe0a1a2f7585b15e270452))

- **i18n**: Add English fallback for 8 languages missing parent translation calls
  ([`4ee1ab8`](https://github.com/LibreBooking/librebooking/commit/4ee1ab87d7e1d5b3dfa5d115e3a8948c8c56c6a0))

- **i18n**: Add missing embedded_datetime date format to en_gb locale
  ([`a4e45d6`](https://github.com/LibreBooking/librebooking/commit/a4e45d63c93dcdf7ff2e15bbcf1bbb850d582987))

- **i18n**: Replace HTML entities with UTF-8 characters in Italian locale
  ([`a0c0f46`](https://github.com/LibreBooking/librebooking/commit/a0c0f46eaca71068427ad3dabf0a16efc7466a1a))

- **js**: Add no-unused-vars rule, remove dead code, and fix bugs
  ([`f009211`](https://github.com/LibreBooking/librebooking/commit/f009211c0876266e888bb3f724f8020679cb89b7))

- **lang/es.php**: Spanish translations
  ([`1b91ad3`](https://github.com/LibreBooking/librebooking/commit/1b91ad33a5a2ecaac09c9070e6071d5d87ef95c5))

- **ldap**: Install the 'pear/net_ldap2' library by default
  ([`f3d225e`](https://github.com/LibreBooking/librebooking/commit/f3d225e38f63d430a3627cb9d32e985e44bf78d6))

- **ldap**: Require 'ext-ldap'
  ([`e55e63a`](https://github.com/LibreBooking/librebooking/commit/e55e63ad6b9d98fe9c1d421e6a4cfa752f93f2cc))

- **registration**: Resolve a server error when more than one attributes in the register page
  ([`71a3f73`](https://github.com/LibreBooking/librebooking/commit/71a3f73ac68ec1c4568aaf99fde3ca54eb09b08a))

- **reports**: Prevent chart modal from hanging on render errors
  ([`e81f465`](https://github.com/LibreBooking/librebooking/commit/e81f465864db9ac5c8534d84a6d51ce8888abafc))

- **reservation**: Correct guest reservation URI validation from calendar and schedule view
  ([`9aca6a1`](https://github.com/LibreBooking/librebooking/commit/9aca6a1b06d158184740dee5ea92827cc25ab0d3))

- **reservation**: Correct guest reservation URI validation from calendar and schedule view
  ([`1f9a739`](https://github.com/LibreBooking/librebooking/commit/1f9a739409b9cf9596e7e6c2e894b0bf6f3ee56a))

- **reservation**: Resolve a crash when the email field is not an array
  ([`618a2f4`](https://github.com/LibreBooking/librebooking/commit/618a2f414a0fd8362c64c93a4b6c63a404eea50d))

- **resource-display**: Update date input handling in resource display
  ([`fbee303`](https://github.com/LibreBooking/librebooking/commit/fbee30394383b4c2ccffb3e694295744795dce6e))

- **schedule**: Avoid recursive buffer item ids
  ([`b139461`](https://github.com/LibreBooking/librebooking/commit/b1394612e9e5311d0b6662cffa8e2e528667d6c3))

- **schedule**: Correct tall view rendering for reservations with hidden blocked periods
  ([`7e80933`](https://github.com/LibreBooking/librebooking/commit/7e80933cbc7d194e23db5aa7b5eed9c29b463815))

- **schedule**: Use correct StartDate field in reservation sort
  ([`a83246c`](https://github.com/LibreBooking/librebooking/commit/a83246cb711cc8a3705032f23b9661c2791fe0ff))

- **spanish-translations**: Update Spanish terms for consistency and clarity
  ([`698cbec`](https://github.com/LibreBooking/librebooking/commit/698cbecca02ea29742674c8d98f1218ba6030a95))

- **time**: Make Time parsing/formatting DST-safe for time-only values
  ([`6bef7db`](https://github.com/LibreBooking/librebooking/commit/6bef7dbd642d22426d1ecbe6e96397e82bcfd9d7))

- **translation**: Fix date formats for italian
  ([`44ceb2b`](https://github.com/LibreBooking/librebooking/commit/44ceb2b661579475e429d503f18ee1e5588dab9c))

- **UI**: Standardized the size of remaining form inputs and buttons to sm across the interface.
  ([`cab32b6`](https://github.com/LibreBooking/librebooking/commit/cab32b6b28fb0a6a6771a96bfa5ae054b544255f))

- **validators**: Allow empty schedule filter params
  ([`9aca6a1`](https://github.com/LibreBooking/librebooking/commit/9aca6a1b06d158184740dee5ea92827cc25ab0d3))

- **validators**: Remove redundant EXISTS from combined route validators
  ([`9aca6a1`](https://github.com/LibreBooking/librebooking/commit/9aca6a1b06d158184740dee5ea92827cc25ab0d3))

- **validators**: Remove redundant EXISTS from combined route validators
  ([`47860f1`](https://github.com/LibreBooking/librebooking/commit/47860f150610e57c15028997099be04e7441e8f2))

- **view-reservation**: Correctly display the duration of the reservation
  ([`af1e2ce`](https://github.com/LibreBooking/librebooking/commit/af1e2ce5cb6fd85b6844983a40e98086ba644787))

- **webservice**: Respect configured inactivity timeout per session
  ([`3da795d`](https://github.com/LibreBooking/librebooking/commit/3da795dfda1f28e42c2703c4f6c2e62fa7876b31))

### Build System

- **assets**: Vendor Hind font for local frontend usage
  ([`22b283a`](https://github.com/LibreBooking/librebooking/commit/22b283afe027e9b95be911b9484c8459d81d3ea3))

### Chores

- Correct the location of jquery-migrate
  ([`6e828e8`](https://github.com/LibreBooking/librebooking/commit/6e828e8ce86753d6c7ab60d0a5932d1ea4de3835))

- Remove bootstrapValidator CSS includes
  ([`7079d8e`](https://github.com/LibreBooking/librebooking/commit/7079d8e4db404b8d4f759d104574d04384fa6490))

- Remove unused wysihtml5 assets
  ([`ccaf27b`](https://github.com/LibreBooking/librebooking/commit/ccaf27b6da78afaed30844dadf7588080698b909))

- Upgrade jQuery UI assets to 1.14.2
  ([`4a0818e`](https://github.com/LibreBooking/librebooking/commit/4a0818ecc8642a5cd429c28dd4105fa01100a185))

- Upgrade jquery-migrate from 3.0.1 to 3.6.0
  ([`74802ea`](https://github.com/LibreBooking/librebooking/commit/74802ea5bf12033ff650c123b2304bb578319228))

- **api**: Remove trailing slash in Authentication POSTs
  ([`e69e636`](https://github.com/LibreBooking/librebooking/commit/e69e63600b0e71b6d94464ee034f1e5a023a43c8))

- **dependabot**: Add a 7 day cooldown for updates
  ([`7154bac`](https://github.com/LibreBooking/librebooking/commit/7154bac5e383a8ba3bab2dc008e1e6d7cc64eed8))

- **phpcsfixer**: Add 'node_modules/' to the ignore list
  ([`4b9600d`](https://github.com/LibreBooking/librebooking/commit/4b9600ddd933ed09b61e2d926f9752810905e285))

- **vendor**: Remove unused jquery-dynamiccontextmenu asset
  ([`670f143`](https://github.com/LibreBooking/librebooking/commit/670f14346dd104bfe3a151047d90d3f474b7b942))

- **vendor**: Upgrade select2 library to 4.1.0-rc.0
  ([`8700159`](https://github.com/LibreBooking/librebooking/commit/8700159472c3bae3089c4d182b7601c3675b416c))

### Code Style

- Have AI agents flag "magic number" usage in code reviews
  ([`8cfd27b`](https://github.com/LibreBooking/librebooking/commit/8cfd27bb782489ee7fa66201def0d090a45564c7))

- **blackouts**: Stylize the presentation of Blackouts and reservations when there are conflicts
  ([`cedce2f`](https://github.com/LibreBooking/librebooking/commit/cedce2f275e8d429cd91082c8a0441e9afb58d7b))

- **changelog**: Add 'v' prefix to version headings for consistency
  ([`d4447d3`](https://github.com/LibreBooking/librebooking/commit/d4447d34e9477baeb7b20acf07d871f0c6229950))

- **jqtree**: Add Bootstrap form-check-input to missing checkboxes
  ([`45e88df`](https://github.com/LibreBooking/librebooking/commit/45e88dff070511102d5810af0a0e473e49262641))

### Continuous Integration

- For some CI failures provide info on how to fix
  ([`4bd036f`](https://github.com/LibreBooking/librebooking/commit/4bd036ff935b549231d9a3d6ab3f046d7b13598a))

- **frontend**: Have the prettier check run first
  ([`cb9a937`](https://github.com/LibreBooking/librebooking/commit/cb9a937a0cc3759d71c9a689d1afdec970cd00bb))

- **integration**: Switch from MySQL to MariaDB and use Docker mirror
  ([`f499fbd`](https://github.com/LibreBooking/librebooking/commit/f499fbd8dce036efe86cc12a00e04ad3d26ecd0c))

- **lint**: Add Node.js setup hint to frontend lint failure messages
  ([`ee32d8f`](https://github.com/LibreBooking/librebooking/commit/ee32d8feffd23f39c2e3b12bfef327830b2a4c74))

- **phpcsfixer**: When it fails, tell how to fix it
  ([`b534c4e`](https://github.com/LibreBooking/librebooking/commit/b534c4e3294d55ddae561f2ee8862c749c468707))

- **release**: Change monthly release schedule to 17:00 UTC
  ([`4e3cc08`](https://github.com/LibreBooking/librebooking/commit/4e3cc08073aa123527071e027880bbdfa147850b))

### Documentation

- Update description of schedule.fast.reservation.load
  ([`f323b16`](https://github.com/LibreBooking/librebooking/commit/f323b16315524c7a9eb38ab3addb06fbe6b88a78))

- **api**: Add some curl examples to the API docs
  ([`1465819`](https://github.com/LibreBooking/librebooking/commit/1465819914420d4b583504a0a0f0984a4e0c84f9))

- **api**: Change the Authentication docs to use sample values
  ([`05dce48`](https://github.com/LibreBooking/librebooking/commit/05dce48f30f0746fa5544d71f992be9c9aa58e2b))

- **api**: Correct the responses for account actions
  ([`89f6ec2`](https://github.com/LibreBooking/librebooking/commit/89f6ec26311ec0ed32a51ef6e55409ec2f7c598e))

- **API**: Document retryParameters and skipconflicts for reservation endpoints
  ([`6f10d2f`](https://github.com/LibreBooking/librebooking/commit/6f10d2fd654e1e744887826f33a8701b842d8a91))

- **api**: Emphasize to not use a / for authentication endpoint
  ([`5294edc`](https://github.com/LibreBooking/librebooking/commit/5294edcc5dcadf77bf95d4d0b28e023ba265f818))

- **api**: Put the 'request' before the 'response'
  ([`7cdb4da`](https://github.com/LibreBooking/librebooking/commit/7cdb4da3c0dbb3fd0e3674171c39dc0daaa6b976))

- **api**: Use the term "Endpoints" instead of "Services"
  ([`d306a41`](https://github.com/LibreBooking/librebooking/commit/d306a419c9d9057d0f3824217a47ac00764169c5))

- **config**: Document admin exemption from start.time.constraint
  ([`e1b10da`](https://github.com/LibreBooking/librebooking/commit/e1b10da64a5395653fcf888bd45e7fceae2a93a3))

- **css**: Improve the documentation for `css.extension.file` config
  ([`a6d00b6`](https://github.com/LibreBooking/librebooking/commit/a6d00b63d7c2c3a4620cea649a1f7e4d091169f5))

- **faq**: Add an initial FAQ document
  ([`2b54b66`](https://github.com/LibreBooking/librebooking/commit/2b54b66e21219ed65a998b5616126e4e9f00bf6e))

- **ldap**: Add note about using the LDAP schema for the host
  ([`8015721`](https://github.com/LibreBooking/librebooking/commit/8015721455db48a6336a787daad35aec937b816f))

- **oauth2**: Document the `oauth2.strip.trailing.slash` config
  ([`2c361b7`](https://github.com/LibreBooking/librebooking/commit/2c361b7762a6dccaa42e1ea7c528b7f12ee39af1))

- **oauth2**: Update config examples for new config format
  ([`5110088`](https://github.com/LibreBooking/librebooking/commit/51100880fc29582201d7be87b46c218f97e347a7))

### Features

- Add large sample database and setup script
  ([`9b202d2`](https://github.com/LibreBooking/librebooking/commit/9b202d25741350c6fd6f95e9c11044972214e04a))

- Add option CAS_VERSION_3_0 for config key CAS_VERSION in CAS Authentication plugin
  ([`7d6c3d4`](https://github.com/LibreBooking/librebooking/commit/7d6c3d420bbc3cc772494b9aa25de91796da4526))

- Enable use of local JavaScript libraries by default
  ([`c2d663e`](https://github.com/LibreBooking/librebooking/commit/c2d663e214bb9a7776afb3fae83f608d13f8aac5))

- Enhance reservation emails with resource metadata and details table
  ([`57497bc`](https://github.com/LibreBooking/librebooking/commit/57497bca8e11a54349d76b68b59fad48880328e5))

- OAuth trailing slash variable
  ([`662fa90`](https://github.com/LibreBooking/librebooking/commit/662fa905f0445fc884fc446e96642e6461254599))

- **api**: Add scheduleId filter to GET Resources endpoint
  ([`6ef6011`](https://github.com/LibreBooking/librebooking/commit/6ef6011fbfdfaee3d4c1cc7299810cca3389afd4))

- **ci**: Add initial minimal frontend linting with ESLint and Stylelint
  ([`4fe45d5`](https://github.com/LibreBooking/librebooking/commit/4fe45d53e193a281181ff9ecb398b5a3162edd9a))

- **ci**: Add Prettier for standardized frontend formatting
  ([`5346f52`](https://github.com/LibreBooking/librebooking/commit/5346f5251e6c0a04d7b732f25f23d835480dcd41))

- **ci**: Promote all ESLint rules to error and block warnings
  ([`d3f9c4b`](https://github.com/LibreBooking/librebooking/commit/d3f9c4b9170a234e34dfaa1ca9d4208fa65bc565))

- **config**: Add config migration script and documentation
  ([`2371fea`](https://github.com/LibreBooking/librebooking/commit/2371fea4ed380599220a07ee43c97ec3470c4402))

- **config**: Support custom footer version override
  ([`157a4cb`](https://github.com/LibreBooking/librebooking/commit/157a4cb7c830ed0d9ae3c274889f953e8a927b16))

- **github**: Convert issue templates to YAML forms with required fields
  ([`996d654`](https://github.com/LibreBooking/librebooking/commit/996d654b0c3e1c40764acbcd6e6fb3bc2e5b49d0))

- **i18n**: Add enabled.languages config to restrict language selector
  ([`7063f26`](https://github.com/LibreBooking/librebooking/commit/7063f26c36ca81e631cc82298569d7698f88738a))

- **i18n**: Add language selector to navbar
  ([`138fecc`](https://github.com/LibreBooking/librebooking/commit/138fecc35d8761cb5ebe1c8203c5f836acfd6fb0))

- **install**: Add option to load large sample data in web installer
  ([`3cafcdb`](https://github.com/LibreBooking/librebooking/commit/3cafcdb2dc5e94561734777d1ed58830a319a947))

- **schedule**: Add tooltips to schedule action buttons
  ([`27dcd39`](https://github.com/LibreBooking/librebooking/commit/27dcd397d1687dc137ece2ceeaa1aa9ac858132d))

- **UI**: Add noscript warning for users with JavaScript disabled
  ([`334eeb7`](https://github.com/LibreBooking/librebooking/commit/334eeb7a0e64e665aeb390d9ddb9ba9ec7b4c763))

### Refactoring

- Move 3rd party Javascript and CSS code
  ([`0bdff5a`](https://github.com/LibreBooking/librebooking/commit/0bdff5a61df29e60fef6e77bb31a03fd4d70a11f))

- Remove legacy qTip references
  ([`f73a717`](https://github.com/LibreBooking/librebooking/commit/f73a717a5b22efb931f8ed5fae066b274631f3eb))

- Remove qTip assets and related files
  ([`12e187a`](https://github.com/LibreBooking/librebooking/commit/12e187a581e5135c371a70d706ef944ef23e9942))

- Reservation email template context for multi-resource emails
  ([`0ae98b6`](https://github.com/LibreBooking/librebooking/commit/0ae98b61f1a615fa3a33008d42245af3feeb049d))

- **config**: Clarify config key schema dispatch
  ([`88b1350`](https://github.com/LibreBooking/librebooking/commit/88b13509c433e1132672ae05f072b8554825c0bd))

- **config**: Extract shared config key base class
  ([`27e7e31`](https://github.com/LibreBooking/librebooking/commit/27e7e3123cf387682db16f3f241f57dc39b015d6))

- **dashboard-tooltip**: Show reservation tooltip only on .reservationTitle
  ([`d6afdec`](https://github.com/LibreBooking/librebooking/commit/d6afdecca026914d99f0a53ce65907e8322146f9))

- **domain**: Rename User::GetAllowedResourceIds() to GetFullAccessResourceIds()
  ([`0acb94e`](https://github.com/LibreBooking/librebooking/commit/0acb94e621b49ccc0dd5e02071792c43f06f2c84))

- **domain**: Rename User::GetAllowedViewResourceIds() to GetViewAccessResourceIds()
  ([`2081bc6`](https://github.com/LibreBooking/librebooking/commit/2081bc61a03668c1ef983229a0dbce67c99cc696))

- **js**: Add prefer-const ESLint rule and fix violations
  ([`84cd017`](https://github.com/LibreBooking/librebooking/commit/84cd017426154a7e068b1c0517f46125d0ae755b))

- **manage-blackouts**: Tooltips to appear only on .reservationTitle
  ([`9ad945e`](https://github.com/LibreBooking/librebooking/commit/9ad945e3470ac03195be40b5bf0d2cf94ef2249c))

- **manage-reservations**: Tooltips to appear only on .reservationTitle
  ([`e6ed697`](https://github.com/LibreBooking/librebooking/commit/e6ed697c3d28deb719d71ebae7718528d4893549))

- **participation**: Tooltips to appear only on .reservationTitle
  ([`b408344`](https://github.com/LibreBooking/librebooking/commit/b408344a9ad491130f6f939d424dc213c57e8968))

- **recurrence**: Replace native date time input with flatpickr
  ([`0f1071e`](https://github.com/LibreBooking/librebooking/commit/0f1071e2efbc7f297bbb08782af82e75bbbdd77f))

- **reports**: Add charts.js assets
  ([`3c27c04`](https://github.com/LibreBooking/librebooking/commit/3c27c0471088c06b44cca9742042f9ff9dc4da52))

- **reports**: Remove dead code and standardize jQuery event handling
  ([`0b65c4e`](https://github.com/LibreBooking/librebooking/commit/0b65c4e51107bf68d9e2924f6da9df4c9881aaf0))

- **reports**: Remove jqPlot assets
  ([`fa0f2bd`](https://github.com/LibreBooking/librebooking/commit/fa0f2bd1112bd8bf4ab0a4acfc17135ba950eac7))

- **reports**: Switch Reports from jqPlot to Chart.js
  ([`afec9c0`](https://github.com/LibreBooking/librebooking/commit/afec9c0374abab16ca5c5a43037e2dbd111583c4))

- **schedules**: Replace qTip with Bootstrap tooltips for reservation tooltip
  ([`9283493`](https://github.com/LibreBooking/librebooking/commit/92834939d612f169d8990848379548269aca2ffb))

- **tooltip**: Tooltips to appear only on .reservationTitle (search-reservations)
  ([`9a5875e`](https://github.com/LibreBooking/librebooking/commit/9a5875e6dbed67747737f69182baeed5ef864d7e))

- **tooltips**: Standardize popup template names and update references
  ([`3fcedd6`](https://github.com/LibreBooking/librebooking/commit/3fcedd634ae28ddda47321419f9cf5b52d274bd4))

- **validators**: Replace regex param extraction with parse_url/parse_str
  ([`c74bdf6`](https://github.com/LibreBooking/librebooking/commit/c74bdf61577f4255cf1d326a9ce1d992c7bd1675))

- **validators**: Simplify XSS guard with early return and add PHPDoc comments
  ([`691e91e`](https://github.com/LibreBooking/librebooking/commit/691e91e1f70081c0e203ceee0c41eb45a3328379))

### Testing

- Resolve issues with tests and crossing a DST boundary
  ([`539acc3`](https://github.com/LibreBooking/librebooking/commit/539acc32b5435b8a782104cc18cf8eb630287f96))

- **config**: Cover parsing of historical legacy config files
  ([`07dc573`](https://github.com/LibreBooking/librebooking/commit/07dc5732cf9ff7935145b7445d8aa94366cd9dc0))

- **reservation**: Fix issue with DST causing test failure
  ([`6850403`](https://github.com/LibreBooking/librebooking/commit/6850403922e52da7df0905c286ec2ee468ee2664))


## v4.2.0 (2026-03-03)

### Bug Fixes

- Add `composer.lock` to the repository
  ([`febdc12`](https://github.com/LibreBooking/librebooking/commit/febdc12f66307e7059ac90155c4a158d69c26284))

- Add null-safety for PHP 8.1+ string function deprecations
  ([`b817269`](https://github.com/LibreBooking/librebooking/commit/b8172695ce1a66fa0ef668974a0595661f35754d))

- Issue with GetHeader called with non-existing header
  ([`0f387cc`](https://github.com/LibreBooking/librebooking/commit/0f387ccc6482883c11e4a2a8161c9cf11a59edce))

- Null parameter error in debug mode
  ([`f1719d8`](https://github.com/LibreBooking/librebooking/commit/f1719d82b596c03e8cefa719a8286846f47dcfa5))

- Pdf generation for on the reservation page
  ([`4d84247`](https://github.com/LibreBooking/librebooking/commit/4d842479b92a3799e03051b91c875b7cfb3bf20c))

- When an unknown error happens use HTTP 500
  ([`20521ac`](https://github.com/LibreBooking/librebooking/commit/20521acec257615a1f5d41d90286c1c1cc3b3b02))

- **admin**: Correct bulk checkbox handling in blackouts and clean up user script
  ([`a1fa73d`](https://github.com/LibreBooking/librebooking/commit/a1fa73df54eec05bb898bc813e8d5ffe34f724ce))

- **admin**: Fetch reservation deletion checkboxes when needed instead of from a cache
  ([`0224527`](https://github.com/LibreBooking/librebooking/commit/02245276fe74d47b814cf4a55f06c72d4f66ac90))

- **admin**: Make delete button visible on all user table pages
  ([`e285c95`](https://github.com/LibreBooking/librebooking/commit/e285c953e7dd44f711bf9f05fab4e72a25c3cc44))

- **auth**: Repair MoodleAdv config access and add regression coverage
  ([`109ac86`](https://github.com/LibreBooking/librebooking/commit/109ac86b18c9ce3256f06d33ce9ed9c943222646))

- **authentication**: Preserve guest sessions during cookie login
  ([`a0b96e4`](https://github.com/LibreBooking/librebooking/commit/a0b96e48ad881265bbe6601308ca5f81e4aa80ff))

- **config**: Time constraint 'same_day' config not working
  ([`772ad0e`](https://github.com/LibreBooking/librebooking/commit/772ad0ef8fd66d6abf3d677d1e564c316851ad2f))

- **db**: Give a more helpful error if can't connect to database
  ([`48f5818`](https://github.com/LibreBooking/librebooking/commit/48f5818273a8685783f561e28e7781e3f6d1d002))

- **documentation**: Start and end reminders can only be minutes, hours and days
  ([`e8105b0`](https://github.com/LibreBooking/librebooking/commit/e8105b09ed11f7a3f3c4cce2541c837c0a777180))

- **documentation**: Update reservation start time constraint documentation
  ([`0d15ca3`](https://github.com/LibreBooking/librebooking/commit/0d15ca3ca09bb85c80af7de07d0a717f8ef5eaf3))

- **htaccess**: Correct possible redirect loop
  ([`411e787`](https://github.com/LibreBooking/librebooking/commit/411e7876816dbca430e059fd755c52977ccf8ada))

- **htaccess**: Infinite redirect to base URL
  ([`e42cb4a`](https://github.com/LibreBooking/librebooking/commit/e42cb4a6644c33b2c5774262a314e9a331cc7f57))

- **preflight**: Resolve phpstan issue with PHP 8.5
  ([`93fef16`](https://github.com/LibreBooking/librebooking/commit/93fef16b453ff290bb348e4636000511d93b0c87))

- **shibboleth**: Use correct config key names
  ([`937cfb2`](https://github.com/LibreBooking/librebooking/commit/937cfb2e87fa21a3af017105923ca74f231f45bf))

### Chores

- Add some agents files
  ([`8933f86`](https://github.com/LibreBooking/librebooking/commit/8933f86f6cf1cce553f3c0e9876e2491bd0ceb0d))

- Add the .github/copilot-instructions.md symbolic link
  ([`14541f9`](https://github.com/LibreBooking/librebooking/commit/14541f9e9ae2e73f267f7b371c085dc0ad36db34))

- Fix malformed test file
  ([`a707a92`](https://github.com/LibreBooking/librebooking/commit/a707a92f182b93995953e3d63a4b99dc3484332b))

- Have 'php-cs-fixer' be composer installed
  ([`13e26c9`](https://github.com/LibreBooking/librebooking/commit/13e26c97194fba04f326f1f6a0d5bac6f8f594d7))

- Remove dev dependency 'kint' as unused
  ([`c27d335`](https://github.com/LibreBooking/librebooking/commit/c27d335852b4946e6875476074f34ddca0da940b))

- Run php-cs-fixer on the code base
  ([`1c7e576`](https://github.com/LibreBooking/librebooking/commit/1c7e576478b5e90d5c8a6f5753a881898a924f3d))

- Update flatpickr.min.js version 4.6.13
  ([`06654c5`](https://github.com/LibreBooking/librebooking/commit/06654c5fadf478917571282ec0a400c5850ac396))

- **dependabot**: Increase version in composer.json
  ([`94bfb2a`](https://github.com/LibreBooking/librebooking/commit/94bfb2af7e9e9a81862a0a02b23a5e0b29681add))

- **dependabot**: Run updates each day for composer
  ([`aebf8d8`](https://github.com/LibreBooking/librebooking/commit/aebf8d8e88afa27cf198d9a0f0d15174f0164d8e))

- **monolog**: Change use of deprecated variables
  ([`0d0692e`](https://github.com/LibreBooking/librebooking/commit/0d0692e6f7d156b1fbceab23ae10f893883afe04))

- **phpstan**: Add comments to `phpstan.neon`
  ([`b053d4c`](https://github.com/LibreBooking/librebooking/commit/b053d4cc82313108dee39852bc6315c20e127989))

- **phpstan**: Enable level 2 on tests with phpstan-phpunit extension
  ([`b30b1ab`](https://github.com/LibreBooking/librebooking/commit/b30b1ab8105e98889609716e68a4f442bb8ef5a9))

- **phpstan**: Enable level 2 without a baseline (excluding tests)
  ([`051f21c`](https://github.com/LibreBooking/librebooking/commit/051f21c79d1727f57078719647f2d68964a054f3))

- **stripe**: Handle non-existing `invoice` attribute
  ([`d54cb07`](https://github.com/LibreBooking/librebooking/commit/d54cb072d9502c4337d56c8ccfad1653db404a63))

- **translations**: Updates to French translation
  ([`e34fdda`](https://github.com/LibreBooking/librebooking/commit/e34fdda6146ea4394d22f74a3c5d453337b4af48))

### Code Style

- **data-cleanup**: Enhance admin interface with responsive card design
  ([`60c0e73`](https://github.com/LibreBooking/librebooking/commit/60c0e73605174b743a8aeac7c984633a3cfadfac))

### Continuous Integration

- Remove duplicate 'setup-php' in GitHub CI
  ([`9540596`](https://github.com/LibreBooking/librebooking/commit/9540596716514d25c5d1ae701f14a36b31d4a6c6))

- Run the phpstan and phpcsfixer job on push
  ([`0221450`](https://github.com/LibreBooking/librebooking/commit/0221450f8b030b32caf98f8453ff26f763a992f7))

- Start testing with PHP 8.5
  ([`bbdd167`](https://github.com/LibreBooking/librebooking/commit/bbdd1674bbe17cee524079e6f60fbfd3fca3a864))

- **dependabot**: Initial setup of dependabot
  ([`537620c`](https://github.com/LibreBooking/librebooking/commit/537620ce596e8d7d1dcb952f5d34d1382f7502e0))

- **php-cs-fixer**: Enforce php-cs-fixer in CI
  ([`59e8b9b`](https://github.com/LibreBooking/librebooking/commit/59e8b9be0d48288b73f8eeaa80f0914c7bc5f6e6))

- **phplint**: Parallelize the phplint run
  ([`aa0739a`](https://github.com/LibreBooking/librebooking/commit/aa0739a395d53b1e6600103832d7ef0bc6b6acd8))

- **phpstan**: Add an initial 'phpstan_next' check
  ([`55b9cb1`](https://github.com/LibreBooking/librebooking/commit/55b9cb1bc1c499545de89f534e4ac97e3c9e686d))

- **phpstan**: Add caching to speed up the CI
  ([`daf5166`](https://github.com/LibreBooking/librebooking/commit/daf516677e5e6f52da02896bbb0926a39ff95a38))

- **phpstan**: If phpstan fails, run again with verbose/debug
  ([`bcb3fdc`](https://github.com/LibreBooking/librebooking/commit/bcb3fdcf6d8c209a94b7dd8a88d4a8744ff54a91))

- **phpstan**: Use a matrix for phpstan jobs
  ([`c15bd82`](https://github.com/LibreBooking/librebooking/commit/c15bd82b3c10aaa07f079c183fc1a8ab7fe7b524))

- **refactor**: Move phpunit to its own YAML file
  ([`cd5e596`](https://github.com/LibreBooking/librebooking/commit/cd5e5963f680ebaa87391b802f86f78d82fd9c8d))

### Documentation

- Create comprehensive copilot-instructions.md file
  ([`098b48f`](https://github.com/LibreBooking/librebooking/commit/098b48fa5505f778355b65a33fade8ec6493ed46))

- Repository renamed to 'Librebooking/librebooking'
  ([`b8e7751`](https://github.com/LibreBooking/librebooking/commit/b8e775127ac98eb7b77c94a64d80a4a28a650464))

- **api**: Add note to API web page about slash characters
  ([`032fea7`](https://github.com/LibreBooking/librebooking/commit/032fea70a2c12ebcace9052794f9bb020f493790))

- **API**: Add warning about trailing slash characters
  ([`d5bda61`](https://github.com/LibreBooking/librebooking/commit/d5bda61819438d2cd57ce700b9e3436866b27d94))

- **changelog**: Add the CHANGELOG to the documentation site
  ([`d083950`](https://github.com/LibreBooking/librebooking/commit/d0839509a703c04a1384d900d5710a8fd99e4da3))

- **changelog**: Update CHANGELOG.md to use PSR formatting style
  ([`84e8038`](https://github.com/LibreBooking/librebooking/commit/84e8038c42b525683870b1667a4cda2144f1ce6c))

- **ci**: Use 'doc8' to check ReStructuredText files
  ([`4efd995`](https://github.com/LibreBooking/librebooking/commit/4efd9950f483ca29a2de94a472052a822b43c731))

- **config**: Clarify prevent.participation setting description
  ([`93b2f02`](https://github.com/LibreBooking/librebooking/commit/93b2f0252ac97a7a0126581171936be7fcb1de77))

- **config**: Correct the `app.debug` help text
  ([`96d0ea6`](https://github.com/LibreBooking/librebooking/commit/96d0ea611cb02844a1afbb094f44af4aea2cf765))

- **cron**: Add documentation on how to setup cron jobs
  ([`75d7539`](https://github.com/LibreBooking/librebooking/commit/75d7539b6ab06fec208f127ce91d3b1548de7df8))

- **logging**: Correct LB_LOGGING_* names
  ([`88551cf`](https://github.com/LibreBooking/librebooking/commit/88551cf8a3de66a94ab9bac7f80a6e6ed9d0cebe))

- **README**: Update the demo site link to have the `/Web/` path
  ([`e005a61`](https://github.com/LibreBooking/librebooking/commit/e005a6103fe22d205643101b2c2860173b9a03f1))

- **saml**: Minor cleanup of SAML docs
  ([`3952a26`](https://github.com/LibreBooking/librebooking/commit/3952a26d7eb99339cc8a574ead02a558d6454bcb))

- **translation**: Add missing Finnish lang strings
  ([`4468d76`](https://github.com/LibreBooking/librebooking/commit/4468d767384a2d45307029e387f3c58393f2b4cf))

- **translation**: Add missing Finnish language email templates
  ([`2b928db`](https://github.com/LibreBooking/librebooking/commit/2b928db6858d2a2f23fc3237613c7d851a70d817))

- **translation**: Finnish lang typos and small rewording
  ([`416f027`](https://github.com/LibreBooking/librebooking/commit/416f027895ad812db8e3350cc4cb88cd65404266))

### Features

- Display friendly error messages for LDAP authentication failures
  ([`cc320fa`](https://github.com/LibreBooking/librebooking/commit/cc320fa301d2771b5e0a8a1cf156683e3a8784b9))

- Generate a helpful web page if missing composer
  ([`c996aa5`](https://github.com/LibreBooking/librebooking/commit/c996aa57193e5cb68361174f867c83a1a8902d55))

- Provide a helpful error message if no DB access
  ([`4f48ec2`](https://github.com/LibreBooking/librebooking/commit/4f48ec2290ca85cdea0f242219df1e5738251311))

- **assets**: Load Flatpickr globally with themed month dropdown styles
  ([`ff4ee81`](https://github.com/LibreBooking/librebooking/commit/ff4ee8118fd675355512840a88a74ebc062015f1))

- **config**: Support optional footer version suffix
  ([`6cc8a4c`](https://github.com/LibreBooking/librebooking/commit/6cc8a4cfa2957c3e7d76ce561edf8b958042ec4a))

- **email**: Add SMTP AutoTLS configuration option
  ([`381ad2b`](https://github.com/LibreBooking/librebooking/commit/381ad2b571c86819e258c341f9eea0f31ad61706))

- **flatpickr**: Replace legacy datepicker with modern Flatpickr integration
  ([`d407ccc`](https://github.com/LibreBooking/librebooking/commit/d407ccc77a36d8095352b49b37b1dc297dd9cbe0))

- **preflight**: Add an initial preflight check
  ([`0d9b0ce`](https://github.com/LibreBooking/librebooking/commit/0d9b0ce1393d387af03155f5bec4743d1b985e9d))

### Refactoring

- **announcement**: Replace native date inputs with flatpickr
  ([`46db645`](https://github.com/LibreBooking/librebooking/commit/46db645bff1203d135fcc13f596b7946b18bd3e2))

- **data-cleanup**: Replace native date inputs with flatpickr
  ([`0110ab2`](https://github.com/LibreBooking/librebooking/commit/0110ab20e22527e6e845cfb96f34613ea07bf4ef))

- **date-attribute**: Replace native date time input with flatpickr
  ([`63465be`](https://github.com/LibreBooking/librebooking/commit/63465be6f94a1290f40c541aa13b4d21fed61a24))

- **generate-reports**: Replace native date inputs with flatpickr
  ([`b80aabc`](https://github.com/LibreBooking/librebooking/commit/b80aabc51211b85cbb4a66e9897135c3c95db7ea))

- **ldap**: Migrate to using the `pear/net_ldap2` package
  ([`2bbdc83`](https://github.com/LibreBooking/librebooking/commit/2bbdc839af52c8442270411f3c7891ee5cdecc89))

- **manage-reservations**: Replace native date time input with flatpickr
  ([`22d7b7d`](https://github.com/LibreBooking/librebooking/commit/22d7b7d6278ca605f02c8d8b0eedaccf003ab16a))

- **manage-schedules**: Replace native date inputs with flatpickr
  ([`c3fa374`](https://github.com/LibreBooking/librebooking/commit/c3fa3741757530007a2dbdbca903108efb42a044))

- **ScheduleStyle**: Convert ScheduleStyle to an enum
  ([`f6e8404`](https://github.com/LibreBooking/librebooking/commit/f6e84041eaedd4468ac2b098c1e41a97ed415d1a))

- **search-reservations**: Replace native date inputs with flatpickr
  ([`77b512c`](https://github.com/LibreBooking/librebooking/commit/77b512cd511edf644c564e5d0b400c69060cf01b))

### Testing

- Enable `strict_types=1` for `tests/`
  ([`c46536b`](https://github.com/LibreBooking/librebooking/commit/c46536b807e42863633d874d843dc576c41ffa75))

- Enable `strict_types=1` for `tests/Application/`
  ([`31f78bc`](https://github.com/LibreBooking/librebooking/commit/31f78bc275b4f19f403b02f4a5b962524d24bfe3))

- Enable `strict_types=1` for `tests/Application/Authentication/`
  ([`22826e0`](https://github.com/LibreBooking/librebooking/commit/22826e0ea6e1f328eb55a5cef6b87201e0e29d83))

- Enable `strict_types=1` for `tests/Domain/`
  ([`042d284`](https://github.com/LibreBooking/librebooking/commit/042d284268b4b8b458f2ab50805dac9b566042e2))

- Enable `strict_types=1` for `tests/Infrastructure/`
  ([`9404c15`](https://github.com/LibreBooking/librebooking/commit/9404c15ab02653ce8466cadbd3c10995b8ce54a7))

- Enable `strict_types=1` for `tests/Plugins/`
  ([`cb871d4`](https://github.com/LibreBooking/librebooking/commit/cb871d4fce6b9d71f52fd504c7603b61671194a9))

- Enable `strict_types=1` for `tests/Presenters/`
  ([`8fd1fc7`](https://github.com/LibreBooking/librebooking/commit/8fd1fc786bdd9b6ab262fe0129e6006756667da5))

- Enable `strict_types=1` for `tests/WebService/`
  ([`84b454a`](https://github.com/LibreBooking/librebooking/commit/84b454a9a18e6479360ff8edf34919c5eb37b58f))

- Enable `strict_types=1` for `tests/WebServices/`
  ([`074917a`](https://github.com/LibreBooking/librebooking/commit/074917ab98a03749612dbfb54afabf732cd0e919))

- Fix date/time dependent test failure
  ([`3151445`](https://github.com/LibreBooking/librebooking/commit/3151445dee6f543d7c758be563b656a092347299))

- Fix date/time dependent test failure (again)
  ([`9d46a88`](https://github.com/LibreBooking/librebooking/commit/9d46a88080e895e9f703e2d2da375d5c6ef243a2))

- Setup a basic integration in the CI
  ([`6dfaf06`](https://github.com/LibreBooking/librebooking/commit/6dfaf0609c0cc2b1bc17764c5487435b649f00d1))

- **phpstan**: Enable PHPStan level 3 for `Pages/Ajax/`
  ([`58917c1`](https://github.com/LibreBooking/librebooking/commit/58917c15bb65f4cceae6da3b40d60383c8e295d1))

- **phpstan**: Enable PHPStan level 3 for `WebServices/`
  ([`481f700`](https://github.com/LibreBooking/librebooking/commit/481f700a4b41957d7ff4b29e789d5f02ce43ee5e))

- **phpstan**: Enable PHPStan level 3 for some of `Pages/`
  ([`43cb19e`](https://github.com/LibreBooking/librebooking/commit/43cb19eb3aa3e91266a67bdfe9f54f06d67a7689))

- **phpstan**: Enable phpstan level 3 typing in `Controls/`
  ([`77f1cea`](https://github.com/LibreBooking/librebooking/commit/77f1cea73ba293e5c7ad614f1b4dd9c989b93090))

- **phpstan**: Fix level 3 typing issues in `tests/fakes/`
  ([`2c15873`](https://github.com/LibreBooking/librebooking/commit/2c158730f1395fad35153f6ef6a880978afa8380))

- **phpstan**: Fix remaining level 3 typing issues in `tests/`
  ([`c1dd848`](https://github.com/LibreBooking/librebooking/commit/c1dd848a20ae0d69116c5aecaf5d0c939688cdca))

- **phpstan**: Fix some level 3 typing issues in `tests/`
  ([`1c5d6d7`](https://github.com/LibreBooking/librebooking/commit/1c5d6d7b56abe111673f0710d2e21d329362902a))

- **plugins**: Use correct directory name
  ([`7f735d9`](https://github.com/LibreBooking/librebooking/commit/7f735d9b5c4a1d4956059a3e151040e25c55e8cb))

- **reservation**: Stabilize full-series recurrence test
  ([`ede46c2`](https://github.com/LibreBooking/librebooking/commit/ede46c2fefa24e25598596e03ec35346145fb305))


## v4.1.0 (2026-02-05)

### Bug Fixes

- Add command-line usage instructions for CombineDbFilesTask and UpgradeDbTask causing phpstan issue
  ([`bea840d`](https://github.com/LibreBooking/librebooking/commit/bea840dd2ff194ad4316b7677b1d5bc3306cc708))

- Add default value handling in ConfigurationFile::GetKey method
  ([`0f785cb`](https://github.com/LibreBooking/librebooking/commit/0f785cb08f37486879dd79626bb7e8ccbe1fd2b0))

- Add null check in EnsureNull method
  ([`1bda603`](https://github.com/LibreBooking/librebooking/commit/1bda603569bc903eb097405eabff13f4cf75cb52))

- Api group update will create a new group rather than updating the group
  ([`b939389`](https://github.com/LibreBooking/librebooking/commit/b9393897362f25b1ac1933c5ea0a3d4c7dcb2fa1))

- Availability edit button disappears after editing schedule
  ([`f12d65f`](https://github.com/LibreBooking/librebooking/commit/f12d65f5190fcc2f6daaefea67ef796f06c031b0))

- Cannot access offset type on ShibbolethConfigKeys
  ([`a853e09`](https://github.com/LibreBooking/librebooking/commit/a853e0945f027369a83e1e0deac75685559bf73a))

- Changed wrong auth details response code to 401
  ([`da4d633`](https://github.com/LibreBooking/librebooking/commit/da4d633be39e614addb450cd620bd77aff8b96a6))

- Database migration for 4.0
  ([`0b6c844`](https://github.com/LibreBooking/librebooking/commit/0b6c844824e1ca70afc8b21145ead1c2e575dacc))

- Display current reservation on tablet view and refine layout
  ([#803](https://github.com/LibreBooking/librebooking/pull/803),
  [`570b889`](https://github.com/LibreBooking/librebooking/commit/570b889ac2799d9c3314cb6e0ab0d73ec42fde98))

- Edited plugin config example to use nested layout
  ([`1b14b35`](https://github.com/LibreBooking/librebooking/commit/1b14b35504da7c0548a87cb2da0cf0cb73ce33c9))

- Error in keycloak/oauth url generation
  ([`eb548ff`](https://github.com/LibreBooking/librebooking/commit/eb548ff7319e0ec2c2d291face166087829e4871))

- Error in reservation.start.time.constraint
  ([`98b72b4`](https://github.com/LibreBooking/librebooking/commit/98b72b4c4546045f947c1da957a4a22c67a76951))

- Exporter page broken after config validation
  ([`f7c6c3d`](https://github.com/LibreBooking/librebooking/commit/f7c6c3db240030122a0377cedf3a1aeab3ee1e8d))

- Flatpickr week start day ignored for Starts Today schedules
  ([`a46c31a`](https://github.com/LibreBooking/librebooking/commit/a46c31a991942dcf69b357512b479edbfa965a4d))

- GetConfigGroup and ConfiKeys in API
  ([`12fc5dc`](https://github.com/LibreBooking/librebooking/commit/12fc5dc360b4da7a5f7e5ab95e6e18a3cf5030a1))

- Ignore invalid configs in ManageConfiguration
  ([`711e01e`](https://github.com/LibreBooking/librebooking/commit/711e01e8319843cd7d8b3266e5dc9bd33e08bdf0))

- Include conditionally displaying title and description
  ([#941](https://github.com/LibreBooking/librebooking/pull/941),
  [`46d3069`](https://github.com/LibreBooking/librebooking/commit/46d3069cf6184c4eb6596d6d8be60ebfaa0f958d))

- Informational log message changed to more appropriate level (DEBUG instead of ERROR)
  ([`dadca6b`](https://github.com/LibreBooking/librebooking/commit/dadca6b11259e976e2868a2910d1576b87806e34))

- Null error on unknown key
  ([`a8363f1`](https://github.com/LibreBooking/librebooking/commit/a8363f15835b988026268093b1b53be9f1d362a5))

- Reorder PSR12 rule
  ([`7c3473a`](https://github.com/LibreBooking/librebooking/commit/7c3473a2404ce1b40f77b485c01899a9b4f2ba14))

- Show option key rather than values on config wrong choice
  ([`e35a030`](https://github.com/LibreBooking/librebooking/commit/e35a0301a30b41fa9c4d95b65f59c9ee32881770))

- Standardize log messages and improve error handling in configuration tests
  ([`0214c6d`](https://github.com/LibreBooking/librebooking/commit/0214c6da38a3db8bc82e833797a95ed637b3897f))

- Trumbowyg fails to load when use.local.js.libs is set to true
  ([`ccaba0b`](https://github.com/LibreBooking/librebooking/commit/ccaba0b1cb03eaf8f3dc2cb6a374115831c5f79b))

- Update environment variable keys and add resource options in config files
  ([`5cbd1a1`](https://github.com/LibreBooking/librebooking/commit/5cbd1a191ca457b5ffb8e1a565e78666dae1db44))

- Update manual database setup documentation
  ([`9c175bf`](https://github.com/LibreBooking/librebooking/commit/9c175bf985b06934675e98454f913e2cf169ccb4))

- Use BooleanConverter for TABLET_VIEW_ALLOW_RESERVATIONS
  ([`d941886`](https://github.com/LibreBooking/librebooking/commit/d941886d7bc32d96aefeea3105f5b5a8413a3edc))

- Use ConfigKey instead of hard-coded name
  ([`62a1e9b`](https://github.com/LibreBooking/librebooking/commit/62a1e9b0a4903c93813f2bafaa5edc091d7189a5))

- Use default logging level of 'error'
  ([`0e850d3`](https://github.com/LibreBooking/librebooking/commit/0e850d3e20769bb9d1b62f7c9ca7f9f07e96f272))

- Use lower-case log_level
  ([`44ca668`](https://github.com/LibreBooking/librebooking/commit/44ca668cb4b4cb2a1a4cbbcf057deb2c8e5ca6fb))

- Wrong section for slack token
  ([`f407878`](https://github.com/LibreBooking/librebooking/commit/f407878ae7fdf16c63d1a7de33fa8b1895209f77))

- **auth**: Prevent auto-registration when self-registration is disabled
  ([`9f24a5a`](https://github.com/LibreBooking/librebooking/commit/9f24a5adf7a9b4817718960ebfa3bfcd5c16a8b6))

- **auth**: Updated plugin configuration keys into nested structures
  ([`1cfe196`](https://github.com/LibreBooking/librebooking/commit/1cfe196eeea3ca3dc9624a315df6a86a4f0d22d8))

- **AutocompleteUser**: Handle potential null values
  ([`d90b7ab`](https://github.com/LibreBooking/librebooking/commit/d90b7ab6d37241c74d048c8e5c224cd3b334189c))

- **chore**: Resolve many html escape issues
  ([`c8a6396`](https://github.com/LibreBooking/librebooking/commit/c8a6396831ebcc8d4bf9dadd9bf26da79a92b79f))

- **config**: Preserve unknown subkeys in original structure for validation and improve error
  logging for invalid config values
  ([`723f238`](https://github.com/LibreBooking/librebooking/commit/723f238289f4bac69883b5763e5ee7f7f92592c1))

- **config**: Update configurator to new plugin config
  ([`3fe962b`](https://github.com/LibreBooking/librebooking/commit/3fe962b020787e3239e4451805fd05c88b2c0244))

- **htaccess**: Prevent redirect loop for /Web path without trailing slash
  ([`ad8bde2`](https://github.com/LibreBooking/librebooking/commit/ad8bde2acbfbe5f678f12b67c76cbeec5d4a5bca))

- **image-upload**: Use correct directory for uploading image
  ([`88cb94a`](https://github.com/LibreBooking/librebooking/commit/88cb94a07ec03f679cec1e0f94f68f332bdc68e6))

- **ldap**: Rename debug configuration key for consistency
  ([`f8efae3`](https://github.com/LibreBooking/librebooking/commit/f8efae3fc87b510040bfc987a1a43171ee3a1c79))

- **ldap**: Update default search filter to be optional with improved description
  ([`7c17c7c`](https://github.com/LibreBooking/librebooking/commit/7c17c7c6f39bc693298e220ca8c0022740305308))

- **pdf**: Enhance PDF generation error handling and improve table formatting
  ([`b165000`](https://github.com/LibreBooking/librebooking/commit/b16500067725385863bdb20ebed2c93897a8c931))

- **pdf**: Handle default values for repeat options and reservation details in PDF generation
  ([`fda8a76`](https://github.com/LibreBooking/librebooking/commit/fda8a76967f090bd25896d220cf47455fba782ca))

- **profile**: Resolve loading the profile page when multiple attributes
  ([`e5e423f`](https://github.com/LibreBooking/librebooking/commit/e5e423f6b122c0e8c20c80137ba5eb29fbedb306))

- **profile**: Resolve saving of unchecked checkbox in the profile
  ([`2672584`](https://github.com/LibreBooking/librebooking/commit/26725844ff9eec2f9bf921f0859c432a1b7c7aa5))

- **reservation**: Resolve html rendering in announcement emails
  ([`1fad3be`](https://github.com/LibreBooking/librebooking/commit/1fad3bee7afbb5ad8c9d9b24843d3e6b5703b0ac))

- **reservation**: Resolve weekly series checkbox status on load
  ([`d7a62b4`](https://github.com/LibreBooking/librebooking/commit/d7a62b4f00fba49068a23bd03f46d0e9d70da173))

- **Resources**: Improve string retrieval logic
  ([`7e27ac5`](https://github.com/LibreBooking/librebooking/commit/7e27ac552e2d583c238f18d98edfb0abdc557458))

- **schedule**: Correct date display and layout issues
  ([`d684695`](https://github.com/LibreBooking/librebooking/commit/d684695996e5f509c9a095974a7f624370e1fc04))

- **templates**: Replace regex check with empty check in Italian email templates
  ([`1e71f81`](https://github.com/LibreBooking/librebooking/commit/1e71f81dc77ddb1477252c2d07f7113fb090af7b))

- **test**: Update configuration key test
  ([`3444e6c`](https://github.com/LibreBooking/librebooking/commit/3444e6c8449bd7f33f7e8ca7634ff76da1997071))

- **tests**: Update symbolic link creation and improve PHPUnit error handling
  ([`ae628bc`](https://github.com/LibreBooking/librebooking/commit/ae628bcce7f4fe82dcf77c5b42b27d51c1d0cf23))

### Chores

- Update phpstan-baseline.neon
  ([`200517a`](https://github.com/LibreBooking/librebooking/commit/200517a5fe8c6f7451559c980579bdf9378d6a59))

- **git**: Enforce LF line endings
  ([`85a929f`](https://github.com/LibreBooking/librebooking/commit/85a929f93753111d36bf1f45a1ce54b210ccc024))

- **git**: Normalize all line endings to LF
  ([`b211a9a`](https://github.com/LibreBooking/librebooking/commit/b211a9a5f9b6cf10c28a27fbc55d2a56d675490a))

- **phpstan**: Update for 2.1.25 release
  ([`86594c1`](https://github.com/LibreBooking/librebooking/commit/86594c140cf09b96585ae7907ce7f82b46b804d8))

- **phpstan**: Update phpstan-baseline.neon
  ([`79ac102`](https://github.com/LibreBooking/librebooking/commit/79ac1027a52cc52d603f5428831564762f16fc3e))

- **scripts**: Remove jQuery Timepicker plugin files
  ([`1632653`](https://github.com/LibreBooking/librebooking/commit/16326535d894ea6948e75f335595a3873af55993))

- **templates**: Remove unused Timepicker includes
  ([`9d25543`](https://github.com/LibreBooking/librebooking/commit/9d25543cb568a33077f82a6bbc88778dab18918d))

### Code Style

- Enhance PHP-CS-Fixer rules with Symfony standards
  ([`a42d5f8`](https://github.com/LibreBooking/librebooking/commit/a42d5f8be34ba7fc0496b55301aa7688d030c190))

- Redesign API help page with Bootstrap and improved UI
  ([`3f13add`](https://github.com/LibreBooking/librebooking/commit/3f13add6f5b2c98d92a23b2933025685237f8a20))

- **vscode**: Add initial `.vscode/settings.json`
  ([`afae984`](https://github.com/LibreBooking/librebooking/commit/afae98455c2e7f6f572703e1085d7176761f0556))

### Continuous Integration

- Mark the 'develop' branch as a release branch
  ([`02c4e20`](https://github.com/LibreBooking/librebooking/commit/02c4e20673fabe7e7d2889c506006b3aec6cbbb6))

- Prevent merge-commits in a PR
  ([`ebe5589`](https://github.com/LibreBooking/librebooking/commit/ebe5589ae5f33749f205060f6c484f8d9b60219d))

- **cz-lint**: Give a more helpful message when 'cz' fails
  ([`4a3a105`](https://github.com/LibreBooking/librebooking/commit/4a3a1050d9f1d7d3dbdbf8b760640f5c50b6a906))

- **release**: Add the 'id-token' permissions
  ([`9b6b28a`](https://github.com/LibreBooking/librebooking/commit/9b6b28a14d8a013b3032c3003a7d6337f537858e))

- **release**: Setup an automated release system
  ([`464e117`](https://github.com/LibreBooking/librebooking/commit/464e117967ee33917f7147fed1d79a394cdf814d))

- **release**: Use the 'release' environment
  ([`397e1bf`](https://github.com/LibreBooking/librebooking/commit/397e1bf9edc60a8ed591ea1c9a4cd7f72d7470e9))

- **release**: Use the release token for the git checkout action
  ([`bd1e881`](https://github.com/LibreBooking/librebooking/commit/bd1e8815023a41c354da2cd9ca3de4d63ee3c3f1))

- **release**: Use the RELEASE_GITHUB_TOKEN
  ([`1bdd93d`](https://github.com/LibreBooking/librebooking/commit/1bdd93d5c4638d62609694348aa0c1a54391740a))

### Documentation

- Add info on privacy.view.schedules config option
  ([`9fed6b2`](https://github.com/LibreBooking/librebooking/commit/9fed6b2c4d436f1349a9ea14f0b97aa58a9f6b73))

- Update comment for privacy->view.schedules
  ([`8ae0a5f`](https://github.com/LibreBooking/librebooking/commit/8ae0a5f23f5ec8696ac8c9e9a3c13e5c09041b0c))

- **auth**: Add detailed configuration instructions for LDAP and Active Directory authentication
  ([`40b00f6`](https://github.com/LibreBooking/librebooking/commit/40b00f6d7ced8b2166265538fbda9a66e82de34d))

- **auth**: Enhance description for Admin Username in Active Directory configuration
  ([`c150e2f`](https://github.com/LibreBooking/librebooking/commit/c150e2fa4442be385019d416a858d1d1aa94fd70))

- **auth**: Enhance description for properties in plugins
  ([`12dfeea`](https://github.com/LibreBooking/librebooking/commit/12dfeeaff9c9bbbdd45af05ae3c2ce4b587131ea))

### Features

- Improved error handling for missing configkey and api
  ([`adc4637`](https://github.com/LibreBooking/librebooking/commit/adc463791ed0e3c0eb8bf7cb3476840b04184fc7))

- **reservation**: Highlight negative durations
  ([`6c5a229`](https://github.com/LibreBooking/librebooking/commit/6c5a229be23ca68319f0321fbbeedff9e77f7086))

- **resource-display.php**: Add allow-reservations config for tablet view
  ([`ba22074`](https://github.com/LibreBooking/librebooking/commit/ba2207434dcd18936bdc18871f7a11d46e3d180b))

### Refactoring

- Pageload in Login presenter
  ([`2a554b1`](https://github.com/LibreBooking/librebooking/commit/2a554b1b94d29b226e0ae9b3c888decc5abc10a0))

- Replaced die for proper server response
  ([`4c75a91`](https://github.com/LibreBooking/librebooking/commit/4c75a918eb523620ba0d68af9bd944c9fca46d11))

- Unify time formats using period_time
  ([`35f6295`](https://github.com/LibreBooking/librebooking/commit/35f629520bc503cc0646d516dd4eacd3cec1e10e))

- Url generation for external login
  ([`26511e3`](https://github.com/LibreBooking/librebooking/commit/26511e3f245230454bbee4804df3d6cecc55cff2))

- **date-helper**: Drop moment.js for native dates
  ([`0533579`](https://github.com/LibreBooking/librebooking/commit/053357954a8b06294c0f558c8eaa8ff91451c870))

- **date-helper.js**: Centralize midnight handling in time-range validation
  ([`c85fb34`](https://github.com/LibreBooking/librebooking/commit/c85fb34e8ef29d5dcd9676d2c093b27160a74560))

- **manage_blackouts**: Use dateHelper for timepickers
  ([`3fe1f8a`](https://github.com/LibreBooking/librebooking/commit/3fe1f8a3bda3247396e139cc09b9dc3d00d42b24))

- **manage_peak_times**: Use raw times and centralize formatting
  ([`f099d00`](https://github.com/LibreBooking/librebooking/commit/f099d00c9938b99bdf66326d6d91fc0688af4de7))

- **manage_quotas**: Integrate dateHelper & collapse in UI
  ([`63b1782`](https://github.com/LibreBooking/librebooking/commit/63b178277830ddc8a159770e083d051a2d89ad12))

- **manage_schedules**: DateHelper for peak pickers & validation
  ([`348329b`](https://github.com/LibreBooking/librebooking/commit/348329bab3e83aaccf6cc6944044b3d0ea7895ce))

- **search-availability**: Use select pickers for time inputs
  ([`a87100a`](https://github.com/LibreBooking/librebooking/commit/a87100aa1bd103ffb249f630dd0b35e1aae3997b))

### Testing

- Allow unit tests to be run without setup
  ([`d1221ff`](https://github.com/LibreBooking/librebooking/commit/d1221ff4f0251ee3d3a3a90cb0100e04e4979e3a))

- Prevent flaky tests caused by midnight boundary issues
  ([`c004670`](https://github.com/LibreBooking/librebooking/commit/c004670124e8d947efb02143825de4457460c202))

- Update AuthenticationWebServiceTest to expect response codes
  ([`9c690f6`](https://github.com/LibreBooking/librebooking/commit/9c690f65d33b42ceda739136d6bb366b5c5c0ca4))

- **auth**: Add comprehensive tests for authentication plugin configuration loading and validation
  ([`5522764`](https://github.com/LibreBooking/librebooking/commit/55227647df5e936f88f5baf7f81e398a1c45dfdb))

- **config**: Changed expected test values to reflect intended behavior of expecting a default value
  ([`9f253ef`](https://github.com/LibreBooking/librebooking/commit/9f253ef596940f87034ae449a50ad2016c3b046b))

- **timezone**: Replace deprecated US/* timezones with IANA equivalents
  ([`8e7645b`](https://github.com/LibreBooking/librebooking/commit/8e7645b8bac818a82d2e32b604becf70a6ff8fab))


## v4.0.0 - 2025-08-06

### Highlights

- New configuration file format. Please read the documentation for more
  details. Thanks to @lucs7 for all of their work.
- Initial work on getting PHPStan setup and working. We have level 1 working
  with no baseline and are currently using level 2 with a baseline.
- Add configuration option to be able to choose the resource contact from a
  drop-down list of registered users.
- Language selector now working on the login page.
- New date selector library used, thanks @labmecanicatec

### What's Changed

- style(manage_resources): don't default collapse CustomAttributes by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/682>
- fix: EmailMessage.php variable defined after use by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/683>
- fix: captchas not working by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/684>
- fix(participation): add missing ParticipationNotification import by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/686>
- chore: ensure all config variables are in both config files by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/689>
- fix(reservation): fix the delete reason not submitted when a reservation is not approved. by @belcirelk in <https://github.com/LibreBooking/librebooking/pull/696>
- fix(reservation): pdf generation is not working in French by @belcirelk in <https://github.com/LibreBooking/librebooking/pull/697>
- fix(API): stop storing multiple custom attributes of same type for Reources by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/681>
- chore: Replace jQuery UI datepicker with flatpickr by @labmecanicatec in <https://github.com/LibreBooking/librebooking/pull/756>
- fix(login): Language selector is not working due to httponly cookie by @belcirelk in <https://github.com/LibreBooking/librebooking/pull/763>
- fix: allow setting language for non-HTTPS by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/764>
- chore: remove exec permission from some files by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/765>
- chore: add text to the "More Resource Actions" drop-down by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/766>
- refactor: timezone handling and remove jstz library by @labmecanicatec in <https://github.com/LibreBooking/librebooking/pull/769>
- feat: optional: resource contact may be chosen via a drop-down list of users by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/770>
- Fix for reminders on cancelled reservations by @cgutteridge in <https://github.com/LibreBooking/librebooking/pull/773>
- Fix for phpunit tests and additional error check by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/774>
- fix: error in WebAuthenticationTest and Facebook login by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/781>

### New Contributors

- @belcirelk made their first contribution in <https://github.com/LibreBooking/librebooking/pull/696>

**Full Changelog**: <https://github.com/LibreBooking/librebooking/compare/v3.0.3...v4.0.0>

## v3.0.3 - 2025-07-09

### Highlights

- Improvements to the Installation guide at
  <https://librebooking.readthedocs.io/en/latest/INSTALLATION.html>

### What's Changed

- fix(API): add endpoint to get resource types by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/663>
- remove old docs by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/665>
- fix(schedule): datepicker conflicts with url validation by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/661>
- Fix for #671 / Adjusting css to enable datepicker styling by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/673>
- doc: remove trailing whitespace from *.rst files by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/674>
- fix(CustomAttribute): handle invalid data by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/662>
- fix: allow guests to book reservations by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/668>
- update(docs): update installation documentation: cloning, composer setup, and database config by @labmecanicatec in <https://github.com/LibreBooking/librebooking/pull/675>
- docs: add link to API docs, use notes, update PHP version by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/676>

**Full Changelog**: <https://github.com/LibreBooking/librebooking/compare/v3.0.2...v3.0.3>

## v3.0.2 - 2025-07-07

### Highlights

- Now have a documentation/home page website at <https://librebooking.readthedocs.io/>

### What's Changed

- docs: add a `.readthedocs.yml` file by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/647>
- docs: remove 'beta' designation of the `develop` branch by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/649>
- docs: fix theme options list by @ClemCordier in <https://github.com/LibreBooking/librebooking/pull/650>
- Fix date and autogrow issues by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/657>
- fix: correct datepicker issue introduced in e0cfcbc by @lucs7 in <https://github.com/LibreBooking/librebooking/pull/658>
- fix: don't attempt to reserve view-only resource by @JohnVillalovos in <https://github.com/LibreBooking/librebooking/pull/656>

**Full Changelog**: <https://github.com/LibreBooking/librebooking/compare/v3.0.1...v3.0.2>

## v3.0.1 - 2025-06-25

Update the version number in the code base. This was missed in the v3.0.0
release.

**Full Changelog**: <https://github.com/LibreBooking/librebooking/compare/v3.0.0...v3.0.1>

## v3.0.0 - 2025-06-24

### Highlights

- Bootstrap 5 Framework and Bootstrap icons now used.
- Modernization of the user interface.
- Integration of libraries such as Datatables and Trumbowyg.
- Migrate external libraries to be pulled in via composer instead of storing the code for those projects in the repository. Also upgraded versions of external libraries used.
- Improvements to the CI system.
- Thanks to @labmecanicatec and @lucs7 for all their work this release.

**Full Changelog**: <https://github.com/LibreBooking/librebooking/compare/v2.8.6.2...v3.0.0>

## v2.8.6.2 - 2024-08-18

See all the changes at <https://github.com/LibreBooking/librebooking/commits/develop>

## v2.8.6.1 - 2023-09-26

Mainly Bug fixes, special mention for the ldap plugin, more details at <https://github.com/LibreBooking/librebooking/commits/develop>

## v2.8.6 - 2023-04-18

Librebooking now has PHP8 support
Many bugs, updates and even new features were added but the list is a bit long so for further details please check the commit history <https://github.com/LibreBooking/librebooking/commits/develop>

## v2.8.5.5 - 2022-02-11

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/librebooking](https://github.com/LibreBooking/librebooking)  
Fork this repo, contribute and help keep it alive

Small update to fix a security issue

## v2.8.5.4 - 2021-09-03

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/librebooking](https://github.com/LibreBooking/librebooking)  
Fork this repo, contribute and help keep it alive

Way too many changes, bugfixes and improvements to list them all here, so please take a look at: [https://github.com/LibreBooking/librebooking/commits/master](https://github.com/LibreBooking/librebooking/commits/master)

## v2.8.5.3 - 2021-03-10

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/librebooking](https://github.com/LibreBooking/librebooking)  
Fork this repo, contribute and help keep it alive

- Added translation: Greek
- Updated jsPDF
- Bugfixes

## v2.8.5.2 - 2021-01-25

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/librebooking](<https://github.com/LibreBooking/librebooking>)  
Fork this repo, contribute and help keep it alive - Bugfixes

## v2.8.5.1 - 2020-11-11

**This version is no longer developed by Twinkle Toes Software (<https://www.bookedscheduler.com>)**
Based on the original open source version of Booked, now available at: [https://github.com/LibreBooking/librebooking](<https://github.com/LibreBooking/librebooking>)

Fork this repo, contribute and help keep it alive - Added intial support for generating pdf's on the reservation page

- Added two plugins (Moodle Advanced Authentication and Admin Check-in/out Only)
- Updated portuguese translation
- Bugfixes

## v2.8.5

- Added import and export of groups
- Updated Danish translation
- Allow lower level administrators edit in-progress reservations
- Added optional email to be sent to users when changing resource status
- Added setting to show week numbers on calendars
- Added settings to require phone, position, and organization during registration
- Bugfixes

## v2.8.4

- Allow reservations on the schedule to be filtered by owner or participant
- Include participant list in reports output
- Add resource concurrency to resource import and export
- Bugfixes

## v2.8.3

- Do not require logging in to set up resource tablet display
- Bugfixes

## v2.8.2

- Added the ability to set a limit on the number of concurrent reservations per resource
- Removed the ability to set a schedule as allowing unlimited concurrent reservations per resource
- Bugfixes

## v2.8.1

- Added ability to limit the total number of concurrent reservations for a schedule
- Added ability to limit the number of resources per reservation for a schedule

## v2.7.8

- Added ability to repeat a reservation on non-sequential dates
- Updated PayPal API to version 2
- Added option to sync group membership when logging in via SAML
- Updated Portuguese, German, and Spanish translations
- Updated PhpCAS to 1.3.8
- MySQL 8+ compatibility
- Bugfixes

## v2.7.7

- Added a configuration option to show whether a reservation is new or updated for a period of time
- Added Hungarian translation
- Bugfixes

## v2.7.6

- Added email notifications when participants of a reservation accept or decline invites
- Added reservation waitlist signup on view reservation page
- Added ability to restrict guests from using tablet view
- Notify users if the creation of a blackout time deletes their reservation
- Updated Portuguese and Finnish translations
- Bugfixes

## v2.7.5

- Added utilization reports
- Added ability to find a specific time
- Added recurring reservation series ending emails
- Added credits to reservation emails
- Added link to add to Google Calendar to reservation emails
- Bugfixes

## v2.7.4

- Added availability view to reservation page
- Added participant list to reservation emails
- Redesign of resource tablet display
- Added ability to search for reservations that missed checkin/checkout
- Bugfixes

## v2.7.3

- Added ability to set user status on CSV import
- Added ability to share reservation details via email
- Added ability set the resources, groups, and schedules a group can administer from Groups tool
- Bugfixes

## v2.7.2

- Added monitor display view
- Resolved accessibility issues
- Added Serbian
- Bugfixes

## v2.7.1

- Added ability to purchase credits
- Added credit usage to the reservation page
- Added ability to set comma or semicolon delimited admin.email configuration setting to allow multiple admin emails
- Added ability to send a reservation to Google Calendar
- Added ability to select a resource image while adding
- Added ability to begin a reservation directly from Slack
- Added ability to set default group membership
- Added ability to require terms of service acknowledgment
- Added ability to set login page announcements
- Added ability to set schedule availability dates
- Added ability to configure different minimum notice rules for reservation add, edit and delete
- Added ability to allow multiple reservations on the same resource at the same time for a schedule
- Added ability to set multiple resource images
- Added ability to set view-only resource permissions
- Added ability to sync group membership from LDAP and CAS
- Added ability to set fully custom layout slots
- Added blackouts to schedule and resource calendar view
- Added view calendar page
- Added ability to embed a Booked calendar view on an external website
- Added ability to require reservation title and description
- Added user groups to report output
- Added ability to set custom favicon
- Added ability to customize email messages
- Added ability to bulk delete resources
- Resource QR code will open ongoing reservation if it requires check in
- Added ability to find an open recurring time
- Upgraded jQuery to latest
- Bugfixes

## v2.6.8

- Added ability to see real time availability when selecting additional resources
- Added the ability to set a delete/reject reason
- Added the ability to update users and resources on import from CSV
- Allow setting phone, organization and position when creating a user from the admin section
- Better highlight pending reservations on Dashboard and popups
- Optimize JavaScript file loading for better page rendering times
- Bugfixes

## v2.6.7

- Added real-time indication of additional resource availability in reservation screen
- Added ability to search for reservations
- Added ability to send user an email when an account is created for them
- Added option to show captcha on login
- Updated reCaptcha to use nocaptcha
- If recurring start and end dates are not the same, then include both in the emails
- Added Basque language
- Added Thai language
- Bugfixes

## v2.6.6

- Added ability to set default start and end reminders
- Added ability to import resources from CSV
- Added ability to export resources to CSV
- Added ability to export users to CSV
- Added ability to include custom attributes in user CSV import
- Added ability to import reservations from CSV
- Added ability to bulk delete users
- Added ability to bulk delete reservations
- Added ability to bulk delete blackouts
- Added ability to drag and drop reservations from calendar views
- Added ability to select multiple options for most report filters
- Added password update API
- Added ability to set number of past and future days to include for Atom and iCalendar subscriptions
- Added ability to apply configured default homepage to existing users
- Saved reports and exported reports will use same columns
- Added credits to manage reservations and reports
- Show if a reservation is pending approval on popups and edit page
- Added config option to notify users if they missed their reservation check in time
- Numerous security fixes
- Bugfixes

## v2.6.5

- Ensure only one reminder email is sent per reservation when multiple resources are booked
- Added Vietnamese
- Added ability to automatically fill in blocked time slots based on gaps in available slots
- Added ability to update a reservation before approving it
- Added resource type filter to reports
- Bugfixes

## v2.6.4

- Use resource color on availability dashboard
- Display reservations for multiple resources as one item on dashboard
- Better handling of dates on the reservation page when an entire day is unavailable
- Allow view schedule to be changed to alternate schedule views
- Upgrade PHPMailer
- Bugfixes

## v2.6.3

- Include resource name in all email subjects
- Added 'Today' link to schedule navigation
- Added real time accessory quantity availability
- Added ability to include email and phone in reservation popup
- Added support for MySQL 5.7+
- Added use sso flag for Active Directory authentication
- Added user available credits to the reservation page
- Added ability to copy a resource
- Added Russian
- Bugfixes and security updates

## v2.6.2

- Added ability to invite users to join Booked
- Added ability to repeat multi-day reservations
- Added additional columns to reports
- Bugfixes
- Updated French language pack

## v2.6.1

- Bugfixes

## v2.6

- Mobile first, fully responsive user interface
- Allow guests to book and be invited to reservations
- Allow users to join wait list if requested time not available
- Control resource usage with credits
- Ability to request that users check in and out of reservations, optionally auto-releasing the reservation
- Allow users to sign in using Facebook or Google
- Require users to register with an email address from a known domain
- Set specific days and hours which quotas are enforced
- Allow quotas to exclude completed reservations
- Added ability to search for an available time rather than browsing schedule
- Require minimum and maximum number of accessories when specific resources are booked
- Ability to restrict announcements to certain groups or users with access to certain resources
- Added ability to book around conflicting reservations
- Added ability to set reservation color by user, resource, or custom attribute value
- Added tablet view that can be used to display resource schedule and allow sign ups
- Added private custom attributes
- Added admin-only custom attributes
- Added resource-aware custom reservation attributes
- Invites are attached to reservation emails as .ics file
- On mobile, allow a picture to be taken for resource image
- The first user to register will automatically be setup as the primary admin
- Numerous minor enhancements and bug fixes

## v2.5.21

- Added ability to duplicate a reservation
- Added ability to move reservations by dragging to new slot
- Added ability to blackout around existing reservations
- Added delete confirmation to reservation window
- Fix API bugs
- Fix bug not showing custom user attributes on manage user page
- Fix for account deleted email

## v2.5.20

- Added multi-date selection to bookings page
- Added ability to send announcements as emails
- Added ability to send email to all users when reservation is cancelled
- Added ability to filter on multiple resources on bookings page
- Added ability to allow cross origin requests for API
- Added ability to import ICS files
- Fixed click and drag on condensed week view
- Fixed problem showing hidden resources on dashboard

## v2.5.19

- Fixed some packaging issues from 2.5.18
- Added ability to filter multiple resources on the schedule
- Updated Japanese language files

## v2.5.18

- Fixed bugs with CSRF checks
- Changed the manage reservation search filter to be inclusive of reservations spanning filtered time
- Fixed issue that didn't maintain selected date in schedule calendar popup
- Fixed double html encode issue for custom attributes
- Fixed issue filtering on custom attributes on manage reservations page
- Added fix to allow larger datasets returned when using group_concat
- Fixed the 'deleted by' name in the account deletion email

## v2.5.17

- Fixed bug preventing schedule view switching on Chrome and IE
- Fixed bug with reports showing no results when searching on accessories
- Fixed issue displaying schedule dates even when no slots are defined
- If register or forgot password urls open in external site, open in new window
- Include total hours in reports
- Changed reservation email message to come from whoever made the reservation
- Added ability to override language strings
- Fixed missing homepageid upon registration
- Fixed missing email address in reservation reminders
- Properly custom attribute regex format if user supplied version is incorrect
- Added ability to remove all assigned permissions for resource
- Added ability to include all reservation attributes in display labels
- Save calendar expand/collapse on schedule page
- Fixed bug determining when to send notification emails
- Fixed bug with PR language
- Changed resource availability web service to use same logic as dashboard
- Fixed issue displaying reservations when date had no slots
- Fixed bug that prevented cookies from being written properly in IE
- Fixed warning when path property is not found in the url
- Removed CSRF check on registration page
- Ensure session is started when rendering captcha
- Fixed syntax issue on PHP 5.3 and lower

## v2.5.16

- Added datetime custom attribute type
- Added ability to import a list of users
- Added ability to manage custom attributes through the API
- Added ability to customize report columns
- Added a yearly quota
- Added API for getting resource types and ability to set resource type in add/update
- Added ability to restrict showing user details to simply on/off or past/future reservations
- Added user deleted email notification
- When a reservation is created on behalf of another user, the user taking action is included in the email notifications
- When a user is created on behalf of another user, the user taking action is included in the email notification
- Improved rendering of schedule when being printed
- Resource details are now shown even if user does not have permission
- Added ability to include Google Analytics
- Fixed bug which prevented joining or canceling a recurring reservation instance if it violated a notice rule
- Fixed resource availability dashboard timeout issues
- Fixed bug with creating and updating reservations through the API
- Fixed bug which over-counted accessories when reservation contained multiple resources
- Fixed bug loading resource type attributes when managing custom attributes
- Fixed bug requiring user to uncheck removed resources from all groups
- Fixed bug for resource groups when they are returned from the db sorted incorrectly
- Fixed bug with upcoming reservations dashboard
- Changed cookies to be scoped to Booked root path
- Implemented CSRF checks (thank you Netsparker)
- Updated French language pack
- Updated Croatian language pack

## v2.5.15

- Added ability for users to join reservations without being invited
- Upgraded CAS library to 1.3.3
- Added Active Directory option to sync group membership into Booked
- Added user details popup
- Added ability to manage user and group permissions from resource management page
- Fixed bug preventing recurring reservations from being deleted in management page
- Fixed bug incorrectly grouping recurring reservations on calendar views
- Updated Italian language
- Updated Spanish language

## v2.5.14

- Added notice to schedule when no resources have been added
- Added emails to participants and invitees when a reservation is updated
- Added resource image to reservation email
- Added ability to set default homepage for new users
- Added dashboard item for current resource availability
- Fixed bug displaying wrong reservation dates on reservation save confirmation message
- Fixed bug on view schedule page when using daily layouts
- Fixed bug preventing individual reservations from being added to external calendars
- Fixed bug which did not check Sunday checkbox on recurring reservations
- Fixed bug on dst change preventing all reservations on that day
- Fixed bug causing permission updates performed by schedule admins to wipe out certain permissions
- Updated Italian language pack
- Updated Spanish language pack

## v2.5.13

- Fixed bug preventing reservations from being added to Outlook
- Fixed bug preventing accessories from showing in reservation popup
- Fixed bug preventing resource filter from working on view schedule
- Added Drupal authentication plugin (Drupal 7.x with MySQL only)
- Added ability to display participant and invitee lists in the reservation label
- Applied patch for HTTP security headers
- Updated Italian language

## v2.5.12

- Fixed English admin help page

## v2.5.11

- Fixed issue that was sending approval request emails on every reservation create/update if approval emails were enabled

## v2.5.10

- Fixed issue sending email from \*nix servers

## v2.5.9

- Added custom attributes to reports
- Added resource groups to calendar views
- Added ability to enter maintenance mode
- Added ability manage user groups through API
- Added more options for customizing the reservation slot label, including using custom attributes
- Added ability to customize reservation label for My Calendar, Resource Calendar, ICS feeds, RSS feeds and the reservation popup
- Added list of dates and resources to reservation confirmation message
- Added ability to receive reservation approval request emails
- Added API to get schedule slots
- Added finer-grained control over what profile values can be managed through Booked when using an authentication plugin
- Reduced the size of the bookings page
- Fixed bug graying out resources and dates when user and schedule timezone don't match
- Fixed bug handling non-UTC dates in API
- Fixed bug performing case sensitive match when checking if user is admin
- Fixed bug for GetAvailability API
- Updated German language files
- Updated Portuguese language files

## v2.5.8

- Added schedule and resource filter to My Calendar
- Fixed bug displaying week in calendar views
- Reduced the size of the bookings page by \~35%
- Updated German language files
- Updated Japanese language files
- Updated Portuguese language files

## v2.5.7

- Fixed potential XSS vulnerability on login page

## v2.5.6

- Fixed problem navigating to reservation details from tall schedule view
- Fixed problem rendering resource group management page

## v2.5.5

- Fixed problem updating plugin config files through UI
- Fixed date parsing in web services

## v2.5.4

- Fixed error updating resources

## v2.5.3

- Fixed manage reservations/resources custom attribute filter when multiple attributes are provided
- Fixed javascript error when recaptcha is disabled during registration
- Fixed error updating usage configuration of resources
- Fixed installer to handle the case when the database exists but no tables have been created
- Changed installer to use mysqli
- Fixed error filtering blackouts by resource
- Fixed error creating recurring reservation which sometimes picked the wrong week of the month

## v2.5.2

- Added ability for admins to filter reservations by custom attributes
- Added ability for admins update reservation custom attributes inline on manage reservations page
- Added paging and filtering on Manage Resources
- Added bulk update on Manage Resources
- Added admin dashboard for all upcoming reservations
- Added ability to leave protocol off script.url setting to auto-detect http vs https
- Fixed bug failing to display error message when invalid daily layout is being created
- Fixed missing HTML tags on print report page
- Added Croatian translation
- Updated Czech translation
- Fixed overly restrictive password validator
- Changed reservation confirmation screen to notify when the reservation requires approval
- Updates to Italian language pack

## v2.5.1

- Updated German language files
- Changed reservations web service to not default to current user if no user is provided
- Added resource availability web service
- Added reservation approval web service
- Fixed bug creating a opening new reservation window without a selected resource id
- Fixed bug where reservations ending at midnight would show on the next day for condensed view
- Fixed bug where role restricted pages could not be opened up to everyone
- Fixed bug when a hidden resource belongs to a group
- Fixed bug with schedule admin being able to see reservation list and see blackout list
- Fixed bug where readonly schedule page failed to render
- Fixed bug adding/removing resource images
- Fixed sample data import
- Cleaned up sample post-reservation plugin example

## v2.5

- Application renamed from phpScheduleIt to Booked Scheduler [(why?)](http://www.bookedscheduler.com/phpscheduleit)
- Added ability to reserve resource groups
- Added ability to filter schedule resources
- Added ability to specify resource type
- Added enhanced resource status management
- Added ability to specify buffer time between reservations (per resource)
- Custom attributes now appear on all reservation emails and balloons
- Added ability set custom attributes for an individual resource, user or resource type
- Added ability manage config files for all plugins through the UI
- Added ability to set reservation colors per user
- Added ability to subscribe to reservation Atom feeds
- Added ability update blackouts
- Added ability attach multiple items to a reservation
- Added Shibboleth authentication plugin (thank you to the folks at UCSF)
- Added ability to email admin for all new account creations
- Updates and cleanup on the API
- Removed password regex setting in favor of password complexity settings
- Changed schedule drop downs to exclude schedules if the user does not have permission to any of the resources belonging to it
- Added wide and condensed booking page views
- Added option to allow all users access to reports
- Added setting for default 'from' email address
- Changed the reservation page to default to the minimum resource reservation time
- Changed reservation update to grant permissions to all users if auto-assign permissions is being turned on
- Fixed showing 'Private' when the current user is the reservation owner
- Fixed bug where recurring reservations across daylight savings time boundaries were not being updated to the correct time
- Fixed bug where schedule would freeze on certain daylight savings boundaries
- Fixed pagination bug on manage reservations page
- Fixed bug allowing invitees to join a reservation that was already at capacity
- Fixed bug not enforcing resource cross day reservation constraint
- Fixed bug where quota rules were being enforced cumulatively for resources on a schedule
- Fixed bug where reminders were being sent for deleted reservations
- Updated all mysql_\* calls to mysqli_\*
- Numerous other minor fixes and updates

## v2.4.2

- Added ability to click and drag to create reservations
- Added ability hide blocked slots on schedule
- Added ability to view reservation participation on schedule
- Changed migration process to be asynchronous
- Fixed bug preventing reminders from running on some servers
- Fixed bug hiding labels for periods less than 1 hour
- Fixed bug in configuration management escaping special characters
- Fixed bug when changing start date/end date on reservation page
- Fixed bug selecting wrong start time when user and schedule timezones are different
- Updated German, Portuguese and Hebrew languages

## v2.4.1

- Changed periods spanning less than an hour to display tick marks instead of times
- Fixed bug when displaying vertical schedule when reservation title contained special characters
- Fixed bug in migration script not copying legacy password correctly
- Fixed bugs generating API documentation

## v2.4

- Added restful API
- Added ability to set different layouts for each day of the week
- Added ability to set reminders for reservation beginning and end
- Added UI management page for changing configuration
- Added ability for users to set default schedule
- Added ability to display schedules vertically
- Text for slot labels is now tokenized
- Added WordPress authentication plugin
- Added ability to use reCAPTCHA instead of built in captcha
- Added ability to set logo and custom css files
- Added configurable home page and logout urls
- Added ability to manage user groups from user management page
- Added Bulgarian and Flemisch language packs
- Localized the installation and configuration pages
- Fixed issues with accessory and reservation migration
- Added ability to disable password reset
- Numerous bug fixes and minor enhancements

## v2.3

- Added ability for administrators of all levels to create reports
- Added ability to create a reservation from the schedule and resource calendar views
- Added ability to create recurring blackout dates
- Added schedule admin role
- Added setting to disable recurring reservations for non-admins
- Added setting to automatically subscribe users to all emails
- Added setting to prevent reservation invitations and participation
- Added setting to load jQuery from CDN
- Added setting to return reservation to pending when updated
- Added Swedish translation
- Added full resource and accessory list to reservation emails
- Added ability to set resource order
- Added email address to user autocomplete
- Numerous minor enhancements added and defects fixed

## v2.2

- Breaking change: For Active Directory authentication, please set your authentication plugin to ActiveDirectory. Ldap plugin is now targeted at non-Active Directory.
- Added ability to create custom attributes
- Rewrote CAPTCHA functionality
- Added account activation emails
- Added ability to upload reservation attachments
- Made post-registration action pluggable
- Added Saml SSO Authentication plugin
- Made configuring resource image directories easier
- Added ability to start schedules on Today
- Numerous minor enhancements added and defects fixed

## v2.1

- Added resource administrator role
- Added configurable ability for application admins, resource admins and group admins to recieve reservation activity emails
- Added configuration options for user name formatting, resource editing rules, privacy settings and CSS extension file
- Added ability to subscribe to schedule, resource and personal calendars
- Added option for owner to receive emails when reservations are deleted
- Added participant email notifications when reservations are deleted
- Added ability use full HTML in announcements and resource descriptions/notes
- Many bug fixes, including: reservation approval, reservation admin delete, resource configuration, admin user creation, group user management, registration CAPTCHA
- Added Dutch, Spanish, Italian, Japanese, Polish, Catalan languages

## v2.0.2

- Fix and additional logging for migration
- Minor UI cleanup of validation group error div
- Fixed defect with captcha
- Fixed defect not translating full day names properly when using date formatting
- Fixed some IE7 display problems
- Updated install instructions to be more clear for cPanel users
- Dashboard now shows upcoming reservations for owned/invited/participating
- Fixed defect on quotas which was not working for non English
- Fixed defect where accessories with unlimited quantities were being rejected
- Fixed defect on manage blackouts
- Added pre-reservation plugin example
- Ajax reservation now displays errors
- Fixed defect selecting first period instead of last period when reservation ends at start time of first period
- Fixed defect displaying reservation on first period of the day if it ends at the first period's start time
- Fixed bug adding users from the admin tool
- Fixed javascript single quote bugs

## v2.0.1

- Perfomance improvements on bookings page
- Added Spanish and Dutch translations
- Added ability to view reservation details from view schedule page
- Fixed defect loading translated emails
- Fixed defect approving reservations
- Fixed defects when using IE
- Fixed defect showing an error during log out when using LDAP

## v2.0

- Fully rewritten from scratch with a focus on testability, extensibility and maintainability
- All new, more intuitive and friendly user interface
- Pluggable authentication, authorization, permissions, pre/post reservation actions
- Ability to reserve multiple resources at one time
- Flexible layout configuration and time slot labeling
- Quotas
- Roles
- Better Microsoft Outlook integration
- Easier installation process
