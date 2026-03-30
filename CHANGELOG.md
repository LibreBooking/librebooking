# CHANGELOG
<!-- markdownlint-configure-file { "blank_lines": { "maximum": 2 } } -->

<!-- version list -->

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
