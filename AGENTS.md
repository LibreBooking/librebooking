# LibreBooking - Copilot Agent Instructions

This file contains essential information for AI coding agents working on the LibreBooking repository. Following these guidelines will help you work efficiently and maintain code quality.

## Project Overview

**LibreBooking** is an open-source resource scheduling and booking system written in PHP. It's a fork of Booked Scheduler that has evolved significantly since 2020.

- **Primary Language**: PHP (>=8.2)
- **Database**: MySQL (>=5.5)
- **Architecture**: Model-View-Presenter (MVP) pattern
- **Template Engine**: Smarty (version 5.8+)
- **Frontend**: Bootstrap 5, jQuery
- **Main Branch**: `develop` (not `main` or `master`)
- **License**: GPL-3.0

## Repository Structure

```text
/config                  Application configuration files
/Controls                Reusable page control objects
/database_schema         SQL scripts for database setup and upgrades
/Domain                  Domain entities, repositories, services
  /Access                  Database abstraction layer
  /Events                  Domain events
  /Values                  Value objects
/Jobs                    Scheduled tasks (cron jobs)
/lang                    Translation files (30+ languages)
/lib                     Application supporting objects
  /Application             Application logic (admin, auth, reservations, etc.)
  /Common                  Shared utilities (dates, localization, etc.)
  /Database                Database access and abstractions
  /Email                   Email services
  /external                Third-party libraries (DO NOT MODIFY)
/Pages                   Page binding and workflow logic
/Presenters              Application logic and page population
/plugins                 Plugin architecture (various types)
/tests                   PHPUnit test suite
/tpl                     Smarty templates
/tpl_c                   Template cache (auto-generated, not tracked)
/Web                     User-facing pages and assets
  /scripts                 JavaScript files
  /css                     Stylesheets
/WebServices             REST API implementation
```

## Development Environment Setup

### Prerequisites

- PHP >= 8.2 with extensions: ctype, curl, fileinfo, json, ldap, mbstring, mysqli, openssl, pdo, pdo_mysql, tokenizer, xml
- Optional PHP extensions: bcmath, gd
- Composer for dependency management
- MySQL >= 5.5 for database
- Git for version control

### Initial Setup

1. **Clone the repository**:

   ```bash
   git clone https://github.com/LibreBooking/librebooking.git
   cd librebooking
   ```

2. **Install dependencies**:

   ```bash
   composer install
   ```

   **⚠️ Known Issue - Composer Authentication Error**:
   If you encounter `Could not authenticate against github.com` during
   `composer install`, this is a known issue in certain CI/sandboxed
   environments.

   **Workarounds**:
   - The error typically occurs when Composer tries to download packages from GitHub without proper authentication
   - If in a CI environment, ensure GitHub tokens are properly configured
   - In some cases, packages may already be cached and installation can proceed despite the error
   - If the error persists and blocks progress, note this limitation and proceed with tasks that don't require vendor dependencies

3. **Configure the application**:

   ```bash
   cp config/config.dist.php config/config.php
   # Edit config.php with your database settings
   ```

4. **Set up the database** (if needed):
   - Use Phing tasks: `composer build` (requires MySQL credentials)
   - Or manually run SQL scripts in `/database_schema/`

### Preflight Check

Run the preflight checker to validate your environment:

```bash
composer preflight
# Or: php lib/preflight.php
```

This script checks PHP version, required extensions, permissions, and optional database connectivity.

## Code Quality Tools

### Linting

**PHP Syntax Check**:

```bash
./ci/ci-phplint
```

This uses parallel processing to lint all PHP files (excluding vendor, tmp, node_modules).

**PHP-CS-Fixer** (PSR-12 compliance):

```bash
# Check code style (dry-run, no changes)
composer phpcsfixer:lint

# Fix code style issues automatically
composer phpcsfixer:fix
```

Configuration: `.php-cs-fixer.dist.php`

- Follows PSR-12 standards
- Uses short array syntax
- Enforces single quotes
- Cache: `var/cache/.php-cs-fixer.cache`

### Static Analysis

**PHPStan** (level 2):

```bash
# Run base analysis
composer phpstan

# Run with stricter rules
composer phpstan_next
```

Configuration files:

- `phpstan.neon` - Base configuration (level 2)
- `phpstan_next.neon` - Stricter analysis for progressive improvements

Output format: `prettyJson`
Cache directory: `var/cache/phpstan/`

### Testing

**PHPUnit** (version 11.5+):

```bash
# Run all tests (excludes integration tests)
composer phpunit
# Or: ./vendor/bin/phpunit

# Run specific test suite
composer phpunit -- --testsuite domain
composer phpunit -- --testsuite application

# Run integration tests (requires database)
composer test:integration
```

Configuration: `phpunit.xml.dist`

Test suites available:

- `all` - All tests except integration (default)
- `application` - Application layer tests
- `domain` - Domain layer tests
- `plugins` - Plugin tests
- `presenters` - Presenter tests
- `webservice` / `webservices` - API tests
- `integration` - Integration tests (requires database setup)

**Important**: Integration tests require a configured database. Set up config.php before running.

### Combined Testing

Run both tests and linting:

```bash
composer test
```

### Documentation

Generate API documentation with PHPDocumentor:

```bash
# Install phive tools first
composer install-tools

# Generate docs (output: .phpdoc/build/)
./tools/phpdoc
```

Configuration: `phpdoc.dist.xml`

### Building Releases

Build a distributable release package:

```bash
composer build
# Uses Phing, output: build/librebooking.zip
```

Configuration: `build.xml`

## Coding Standards and Conventions

### PHP Code Style

1. **Follow PSR-12** - The codebase strictly follows PSR-12 standards
2. **Use PHP 8.2+ features** where appropriate (enums, readonly properties, constructor property promotion, etc.)
3. **Type hints** - Use strict typing where possible
4. **Documentation** - Add PHPDoc blocks for classes and complex methods
5. **No trailing whitespace** - EditorConfig enforces this
6. **LF line endings** - Unix-style line endings (see `.editorconfig`)
7. **4-space indentation** for PHP, 2-space for YAML files
8. **Single quotes** for strings (unless interpolation is needed)
9. **Short array syntax** - Use `[]` not `array()`
10. **No magic numbers** - Use named constants, enums, or class constants instead of raw numeric literals. For example, use `CustomAttributeTypes::CHECKBOX` instead of `4`. Code reviews should flag any unexplained numeric literals as magic numbers

### Design Patterns

**Model-View-Presenter (MVP)**:

- **Pages** (`/Pages/*.php`) - Thin abstraction to template engine, minimal logic
- **Presenters** (`/Presenters/*.php`) - Orchestrate interactions, fetch/transform data
- **Domain** (`/Domain/`) - Business logic, entities, repositories

**File Organization**:

- Each page should have a corresponding template in `/tpl`
- Each page should have a class in `/Pages`
- Each page class should have a presenter in `/Presenters`
- Related code grouped in directories with `namespace.php` for easy inclusion

### Naming Conventions

- **Classes**: PascalCase (e.g., `ReservationService`, `UserRepository`)
- **Methods**: camelCase (e.g., `getUserById`, `validateReservation`)
- **Variables**: camelCase
- **Constants**: UPPER_SNAKE_CASE
- **Files**: Match class names (e.g., `ReservationService.php`)

### Database

- Uses MySQL/MariaDB
- Entity Relationship Diagram: `docs/source/ERD.svg`
- Schema management via Phing tasks or manual SQL scripts
- Migration scripts in `/database_schema/upgrades/`

## Git Workflow

### Branch Strategy

**Important**: The main development branch is `develop`, NOT `main` or `master`.

- **develop** - Active development, latest beta code
- **master** - Stable releases only
- Feature branches: `feature/description-of-feature`
- Bugfix branches: `bugfix/issue-number-description`

### Commit Message Format

Follow conventional commits format:

```text
<type>(<scope>): <subject>

<body>

<footer>
```

**Types**:

- `feat` - New feature
- `fix` - Bug fix
- `docs` - Documentation changes
- `style` - Code style changes (formatting, no logic change)
- `refactor` - Code refactoring
- `perf` - Performance improvements
- `test` - Test additions/corrections
- `build` - Build system/CI changes
- `ci` - CI configuration changes
- `chore` - Other changes

**Scopes** (optional but recommended):

- `API` - API changes
- Or describe the affected module/area

**Rules**:

- Header max 72 characters
- Use imperative, present tense ("change" not "changed")
- Reference GitHub issues in footer (e.g., `Closes: #123`)
- Breaking changes: Start footer with `BREAKING CHANGE:`

**Example**:

```text
feat(API): Add new schedules endpoint

Add a new schedules endpoint which allows getting the resources of a schedule.

Closes: #2222
```

### Pull Request Guidelines

1. **Target branch**: PRs should target `develop` (not main/master)
2. **No merge commits**: PRs must not contain merge commits (use rebase)
3. **Linear history**: Maintainers will use "Rebase and merge" or "Squash and merge"
4. **PR title**: Follow commit message header format
5. **Update docs**: If changing interfaces, update README.md and relevant docs
6. **Tests required**: Add tests for new features/bug fixes when applicable
7. **CI must pass**: All lint, analysis, and test checks must pass

## Continuous Integration

### GitHub Actions Workflows

The repository uses several CI workflows:

1. **Linters** (`lint-and-analyse-php.yml`) - Runs on PRs to `develop`:
   - Commitizen (commit message validation)
   - Config checks
   - doc8 (RST documentation)
   - Markdown linting
   - PHP lint (syntax check) on PHP 8.2, 8.3, 8.4, 8.5
   - php-cs-fixer (code style) on PHP 8.2
   - PHPStan analysis on PHP 8.5 (both base and next configs)
   - Validates no merge commits in PR

2. **PHPUnit** (`phpunit.yml`) - Runs on PRs to `develop`:
   - Unit tests on PHP 8.2, 8.3, 8.4, 8.5
   - Creates config.php from config.dist.php
   - Validates test summary output

3. **Integration Tests** (`integration-tests.yml`) - Database integration tests

4. **Documentation** (`docs.yml`) - Builds Sphinx documentation

5. **Release** (`release.yml`, `release-dry-run.yml`) - Automated releases with semantic-release

### Local CI Validation

Before pushing, validate your changes locally:

```bash
# 1. Lint PHP syntax
./ci/ci-phplint

# 2. Check code style
composer phpcsfixer:lint

# 3. Run static analysis
composer phpstan

# 4. Run tests
composer phpunit

# 5. Preflight check
composer preflight
```

## Common Tasks and Patterns

### Adding a New Feature

1. Create feature branch from `develop`:

   ```bash
   git checkout develop
   git pull
   git checkout -b feature/your-feature-name
   ```

2. Implement following MVP pattern:

   - Create/update page in `/Web/`
   - Create page class in `/Pages/`
   - Create presenter in `/Presenters/`
   - Create template in `/tpl/`
   - Add domain logic in `/Domain/` or `/lib/Application/`

3. Add tests in `/tests/` matching the directory structure

4. Update documentation if needed

5. Validate locally:

   ```bash
   composer phpcsfixer:fix  # Fix style issues
   composer phpstan         # Check static analysis
   composer phpunit         # Run tests
   ```

6. Commit with conventional commit message

7. Push and create PR to `develop`

### Adding a Plugin

Plugins are located in `/plugins/` with subdirectories by type:

- `Authentication/` - Authentication providers
- `Authorization/` - Authorization handlers
- `PreReservation/` - Pre-reservation logic
- `PostReservation/` - Post-reservation logic

Each plugin typically has a `*.config.dist.php` file for configuration.

### Working with Templates

Templates use Smarty syntax:

- Variables: `{$variableName}`
- Conditionals: `{if $condition}...{/if}`
- Loops: `{foreach $items as $item}...{/foreach}`
- Functions: `{translate key="some.key"}`

Templates are cached in `/tpl_c/` - this directory is auto-generated.

### Database Changes

1. Never modify `/database_schema/create-schema.sql` directly
2. Create upgrade scripts in `/database_schema/upgrades/`
3. Follow naming convention: `X.Y.Z.sql` (version number)
4. Use Phing tasks to apply upgrades: `composer build` or phing targets

### Localization

- Translation files in `/lang/{language_code}/`
- Each language has separate files for different sections
- Use `translate` function in templates
- Keys use dot notation: `section.subsection.key`

## Known Issues and Workarounds

### 1. Composer Authentication Error in CI/Sandboxed Environments

**Issue**: When running `composer install`, you may encounter:

```text
Could not authenticate against github.com
```

**Cause**: Composer tries to download packages from GitHub without proper
authentication tokens in certain environments.

**Workarounds**:

- Ensure `COMPOSER_AUTH` or GitHub tokens are configured in CI
- Try `composer install --prefer-dist` to use cached packages
- Check if `composer.lock` is up to date and committed
- In some cases, the error is intermittent; retry may succeed
- If working on tasks that don't require vendor dependencies, note this limitation and proceed

**Prevention**: Always commit both `composer.json` and `composer.lock` together.

### 2. Template Cache Issues

**Issue**: Template changes not appearing.

**Workaround**: Clear template cache:

```bash
rm -rf tpl_c/*
```

**Prevention**: In development, ensure proper permissions on `tpl_c/` directory.

### 3. PHPStan Memory Issues

**Issue**: PHPStan may run out of memory on large codebases.

**Workaround**: Increase memory limit:

```bash
composer phpstan -- --memory-limit=2G
```

The CI workflow already uses `--memory-limit 2G` and has debug fallback.

### 4. Permission Issues

**Issue**: Application errors related to file permissions.

**Required Permissions**:

- `/tpl_c/` - Must be writable by web server
- `/tpl/` - Must be writable by web server
- `/uploads/` - Must be writable by web server
- Configured log directory - Must be writable

**Fix**:

```bash
chmod 755 tpl_c tpl uploads
# Ensure web server user owns these directories
```

### 5. PHP Session Auto-start

**Issue**: Application won't work if PHP `session.auto_start` is enabled.

**Fix**: Ensure `session.auto_start = 0` in php.ini.

### 6. Third-Party Code

**Important**: Never modify code in `/lib/external/` - these are third-party libraries maintained separately.

## Testing Strategy

### Unit Tests

- Focus on domain logic and business rules
- Mock dependencies using PHPUnit mocks
- Keep tests fast and isolated
- Test file location should mirror source file structure
- Name test files and test classes after the class under test with a `Test` suffix (for example, `Pages/Admin/ManageUsersPage.php` -> `tests/Pages/Admin/ManageUsersPageTest.php`)
- Prefer one primary test file per source class; add extra files only when a clear separation is needed

### Integration Tests

- Require database setup
- Test actual database interactions
- Located in `/tests/Integration/`
- Run separately with `composer test:integration`

### Test Database Setup

For integration tests:

1. Copy config template: `cp config/config.dist.php config/config.php`
2. Configure test database credentials in config.php
3. Run database setup scripts or use Phing

### Coverage

Generate coverage reports:

```bash
./vendor/bin/phpunit --coverage-html ./var/
```

Output goes to `./var/` directory.

## Documentation

### Developer Documentation

- Main docs: `/docs/source/` (reStructuredText format)
- **DEVELOPER-README.rst** - Developer guide
- **API.rst** - REST API documentation
- **INSTALLATION.rst** - Installation instructions
- **CONFIGURATION.rst** - Configuration guide
- **CONTRIBUTING.md** - Contributing guidelines

### Building Documentation

Documentation uses Sphinx and is hosted on ReadTheDocs.

Build locally:

```bash
pip install -r requirements-docs.txt
cd docs
sphinx-build -n -W --keep-going -b html source build/html
```

Or use tox:

```bash
pip install tox
tox -e docs
```

Output: `docs/build/html/`

### Markdown vs RST

- **Markdown** (`.md`) - Used for: README, CHANGELOG, CONTRIBUTING
- **reStructuredText** (`.rst`) - Used for: Technical documentation in `/docs/source/`

## Security Considerations

1. **Never commit secrets** - Use config files (ignored in git) for credentials
2. **SQL injection** - Use prepared statements (PDO/mysqli)
3. **XSS prevention** - Escape output in templates
4. **CSRF protection** - Use built-in token system
5. **Input validation** - Validate all user input
6. **File uploads** - Validate file types and sizes

## Performance Considerations

1. **Template caching** - Smarty templates are compiled and cached in `/tpl_c/`
2. **Database queries** - Use proper indexing, avoid N+1 queries
3. **Session management** - Configured in `config.php`
4. **Asset optimization** - Minify CSS/JS for production

## API Development

The REST API is in `/WebServices/`:

- RESTful design principles
- Authentication via API keys or tokens
- Version-aware endpoints
- JSON responses
- See `docs/source/API.rst` for detailed API documentation

## Plugin Development

Plugins extend LibreBooking functionality:

1. Create plugin directory in `/plugins/{PluginType}/`
2. Implement required interface for plugin type
3. Add configuration file: `{PluginName}.config.dist.php`
4. Document plugin usage
5. Register plugin in application configuration

## Additional Resources

- **Main Repository**: <https://github.com/LibreBooking/librebooking>
- **Live Demo**: <https://librebooking-demo.fly.dev/>
- **Discord Community**: <https://discord.gg/4TGThPtmX8>
- **Wiki**: <https://github.com/LibreBooking/librebooking/wiki>
- **Docker Image**: <https://github.com/LibreBooking/docker>
- **Issue Tracker**: <https://github.com/LibreBooking/librebooking/issues>

## Quick Reference Commands

```bash
# Setup
composer install                    # Install dependencies
cp config/config.dist.php config/config.php  # Create config
composer preflight                  # Validate environment

# Development
composer phpcsfixer:fix             # Auto-fix code style
composer phpstan                    # Static analysis
composer phpunit                    # Run tests
composer test                       # Run tests + lint
./ci/ci-phplint                     # PHP syntax check

# Building
composer build                      # Package release (Phing)

# Documentation
tox -e docs                         # Build Sphinx docs
./tools/phpdoc                      # Generate API docs

# Database
composer build -- baseline.db       # Create database
composer build -- upgrade.db        # Apply upgrades
```

## When to Ask for Help

- **Unclear requirements** - Ask user for clarification
- **Breaking existing tests** - Investigate why before modifying tests
- **Security concerns** - Consult before implementing security-sensitive features
- **Large architectural changes** - Discuss design before implementing
- **Database schema changes** - Review upgrade process requirements
- **Third-party dependencies** - Check if really needed before adding

## Final Notes

- **Read CONTRIBUTING.md** before submitting PRs
- **Check existing issues** before reporting new ones
- **Test thoroughly** - Both unit and integration tests
- **Document changes** - Update relevant documentation
- **Follow conventions** - Consistency is key for maintainability
- **Ask questions** - Community is helpful on Discord

---

**Version**: 1.0
**Last Updated**: 2026-02-16
**Maintainers**: LibreBooking Community
