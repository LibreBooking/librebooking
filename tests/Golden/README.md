# Golden Template Test Harness

This directory contains the golden-template test infrastructure for the Smarty→Twig migration.
A golden test captures the Smarty-rendered output of a template as a baseline, then asserts
that the converted Twig template renders to a structurally equivalent result.

## How baselines are captured

Baselines are stored as normalized HTML in `baselines/<name>.html`. They are committed to the
repository alongside the fixture data and Twig assertions that use them.

To (re-)write all baselines for the `golden` testsuite, run:

```bash
UPDATE_GOLDEN=1 composer phpunit -- --testsuite golden
```

PHPUnit will write each baseline file and mark the test as **skipped** — that is expected
behavior. On the next run (without `UPDATE_GOLDEN=1`) the tests assert the Twig output
matches the committed baseline.

To regenerate a single baseline, pass the specific test file:

```bash
UPDATE_GOLDEN=1 composer phpunit -- tests/Golden/SomeTemplateTest.php
```

## Normalization contract

`HtmlNormalizer::normalize()` applies structural normalization before comparison so that
cosmetic rendering differences do not cause false failures:

- **Numeric HTML entities** are canonicalized: `&#039;` and `&#39;` are converted to `&apos;`;
  `&#34;` is converted to `&quot;`
- **All whitespace runs** are collapsed to a single space (including between tags, within text content, and in attribute values)
- The result is trimmed

This means two renders are considered equivalent when they produce the same DOM structure
and text content, regardless of indentation or line breaks. Differences in tag names, tag
order, attributes, or text content **will** cause assertion failures.

### Limitations

`HtmlNormalizer` collapses all contiguous whitespace to a single space, including whitespace
within text content (e.g., `Hello  world` becomes `Hello world`), inside attribute values,
and within `<pre>` blocks. The harness will not catch regressions that change only inline
whitespace. This is an intentional trade-off to focus on structural equivalence; Phase 1
authors should be aware when designing test coverage.

## How to add a golden test for a migrated template

1. **Create a fixture** in `tests/Golden/fixtures/` — a PHP file that returns the array of
   template variables needed to render a representative page state:

   ```php
   // tests/Golden/fixtures/login.php
   return [
       'returnUrl' => '/dashboard',
       'errorMessage' => '',
   ];
   ```

2. **Create the test class** extending `GoldenTemplateTestCase`:

   ```php
   // tests/Golden/LoginTemplateTest.php
   <?php

   require_once(__DIR__ . '/GoldenTemplateTestCase.php');

   class LoginTemplateTest extends GoldenTemplateTestCase
   {
       public function testLoginRendersEquivalently(): void
       {
           $vars = require __DIR__ . '/fixtures/login.php';

           // Render with Twig (adjust to use TwigRenderer as needed)
           $html = '...'; // render your Twig template here

           $this->assertMatchesBaseline('login', $html);
       }
   }
   ```

3. **Capture the Smarty baseline** (run once to write the baseline file):

   ```bash
   UPDATE_GOLDEN=1 composer phpunit -- tests/Golden/LoginTemplateTest.php
   ```

4. **Review and commit** the fixture, baseline, and test together.

## Directory structure

```
tests/Golden/
├── README.md                    # This file
├── HtmlNormalizer.php           # Normalization logic
├── GoldenTemplateTestCase.php   # Abstract base class
├── HtmlNormalizerTest.php       # Unit tests for the normalizer
├── baselines/                   # Committed normalized Smarty output
│   └── <name>.html
└── fixtures/                    # Template variable fixtures
    └── <name>.php
```

## Running golden tests

```bash
# Run only golden tests
composer phpunit -- --testsuite golden

# Run golden tests alongside the full suite (they are included in 'all')
composer phpunit

# Regenerate all baselines
UPDATE_GOLDEN=1 composer phpunit -- --testsuite golden
```
