<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Parity tests for the html_options() Twig function vs Smarty's built-in {html_options}.
 *
 * The Twig implementation uses ENT_COMPAT + no double-encode (matching
 * smarty_function_escape_special_chars) and appends "\n" after each </option>
 * for byte-for-byte parity with Smarty's output.
 */
class LibreBookingExtensionHtmlOptionsTest extends TestBase
{
    private \Smarty\Smarty $smarty;

    public function setUp(): void
    {
        parent::setUp();

        $this->smarty = new \Smarty\Smarty();
        $this->smarty->setTemplateDir(sys_get_temp_dir());
        $this->smarty->setCompileDir(sys_get_temp_dir() . '/smarty_compile_html_options');
        $this->smarty->setConfigDir(sys_get_temp_dir());
        $this->smarty->setCacheDir(sys_get_temp_dir() . '/smarty_cache_html_options');
        @mkdir(sys_get_temp_dir() . '/smarty_compile_html_options', 0755, true);
        @mkdir(sys_get_temp_dir() . '/smarty_cache_html_options', 0755, true);
    }

    /** Render {html_options values=... output=... selected=...} with Smarty. */
    private function renderSmartyValuesOutput(array $values, array $output, mixed $selected): string
    {
        $tpl = $this->smarty->createTemplate(
            'string:{html_options values=$v output=$o selected=$s}'
        );
        $tpl->assign('v', $values);
        $tpl->assign('o', $output);
        $tpl->assign('s', $selected);
        return $this->smarty->fetch($tpl);
    }

    /** Render {html_options options=... selected=...} with Smarty. */
    private function renderSmartyOptions(array $options, mixed $selected): string
    {
        $tpl = $this->smarty->createTemplate(
            'string:{html_options options=$opts selected=$s}'
        );
        $tpl->assign('opts', $options);
        $tpl->assign('s', $selected);
        return $this->smarty->fetch($tpl);
    }

    /** Build a Twig environment with LibreBookingExtension registered. */
    private function twigEnv(): \Twig\Environment
    {
        return new \Twig\Environment(
            new \Twig\Loader\ArrayLoader([
                'vo' => '{{ html_options(values=v, output=o, selected=s) }}',
                'opts' => '{{ html_options(options=opts, selected=s) }}',
            ]),
            ['autoescape' => false]
        );
    }

    /** Render html_options(values=..., output=..., selected=...) with Twig. */
    private function renderTwigValuesOutput(array $values, array $output, mixed $selected): string
    {
        $env = $this->twigEnv();
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), 'http://example.com/'));
        return $env->render('vo', ['v' => $values, 'o' => $output, 's' => $selected]);
    }

    /** Render html_options(options=..., selected=...) with Twig. */
    private function renderTwigOptions(array $options, mixed $selected): string
    {
        $env = $this->twigEnv();
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), 'http://example.com/'));
        return $env->render('opts', ['opts' => $options, 's' => $selected]);
    }

    // ── 1. values+output, no selected ────────────────────────────────────────

    public function testNormalListMatchesSmarty(): void
    {
        $values = [1, 2, 3];
        $output = ['One', 'Two', 'Three'];

        $expected = $this->renderSmartyValuesOutput($values, $output, '');
        $actual   = $this->renderTwigValuesOutput($values, $output, '');

        $this->assertSame($expected, $actual);
    }

    // ── 2. values+output, scalar selected (match) ────────────────────────────

    public function testSelectedScalarMatchMatchesSmarty(): void
    {
        $values = [1, 2, 3];
        $output = ['One', 'Two', 'Three'];

        $expected = $this->renderSmartyValuesOutput($values, $output, '2');
        $actual   = $this->renderTwigValuesOutput($values, $output, '2');

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('selected="selected"', $actual);
    }

    // ── 2b. values+output, scalar selected (non-match) ───────────────────────

    public function testSelectedScalarNoMatchMatchesSmarty(): void
    {
        $values = [1, 2, 3];
        $output = ['One', 'Two', 'Three'];

        $expected = $this->renderSmartyValuesOutput($values, $output, '99');
        $actual   = $this->renderTwigValuesOutput($values, $output, '99');

        $this->assertSame($expected, $actual);
        $this->assertStringNotContainsString('selected="selected"', $actual);
    }

    // ── 3. options (assoc), scalar selected ──────────────────────────────────

    public function testAssocOptionsSelectedMatchesSmarty(): void
    {
        $options = ['a' => 'Alpha', 'b' => 'Beta', 'c' => 'Gamma'];

        $expected = $this->renderSmartyOptions($options, 'b');
        $actual   = $this->renderTwigOptions($options, 'b');

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('selected="selected"', $actual);
    }

    // ── 4. options (assoc), no selected ──────────────────────────────────────

    public function testAssocOptionsNoSelectedMatchesSmarty(): void
    {
        $options = ['x' => 'X-ray', 'y' => 'Yankee', 'z' => 'Zulu'];

        $expected = $this->renderSmartyOptions($options, '');
        $actual   = $this->renderTwigOptions($options, '');

        $this->assertSame($expected, $actual);
        $this->assertStringNotContainsString('selected="selected"', $actual);
    }

    // ── 5. selected as array (multi-select) — proves the in_array fix ────────

    public function testSelectedArrayMultiSelectMatchesSmarty(): void
    {
        $values = [1, 2, 3, 4];
        $output = ['One', 'Two', 'Three', 'Four'];
        $selected = ['2', '4'];

        $expected = $this->renderSmartyValuesOutput($values, $output, $selected);
        $actual   = $this->renderTwigValuesOutput($values, $output, $selected);

        $this->assertSame($expected, $actual);
        // Two options must be marked selected
        $this->assertSame(2, substr_count($actual, 'selected="selected"'));
    }

    public function testSelectedArrayAssocOptionsMatchesSmarty(): void
    {
        $options = ['a' => 'Alpha', 'b' => 'Beta', 'c' => 'Gamma'];
        $selected = ['a', 'c'];

        $expected = $this->renderSmartyOptions($options, $selected);
        $actual   = $this->renderTwigOptions($options, $selected);

        $this->assertSame($expected, $actual);
        $this->assertSame(2, substr_count($actual, 'selected="selected"'));
    }

    // ── 6. HTML-special chars in value/label (escaping parity) ───────────────

    public function testHtmlSpecialCharsMatchesSmarty(): void
    {
        // '&' must be escaped in value and label; selected match should work on raw value
        $values = ['a&b', 'c<d', "e'f"];
        $output = ['A&B', 'C<D', "E'F"];

        $expected = $this->renderSmartyValuesOutput($values, $output, 'a&b');
        $actual   = $this->renderTwigValuesOutput($values, $output, 'a&b');

        $this->assertSame($expected, $actual);
        // The selected option's value should be HTML-escaped in the attribute
        $this->assertStringContainsString('value="a&amp;b" selected="selected"', $actual);
        // Single quotes left unescaped (ENT_COMPAT, matching Smarty)
        $this->assertStringContainsString("value=\"e'f\"", $actual);
    }

    public function testHtmlSpecialCharsAssocOptionsMatchesSmarty(): void
    {
        $options = ['a&b' => 'A&B', 'c<d' => 'C<D', "e'f" => "E'F"];

        $expected = $this->renderSmartyOptions($options, 'a&b');
        $actual   = $this->renderTwigOptions($options, 'a&b');

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('value="a&amp;b" selected="selected"', $actual);
    }

    // ── trailing newline per option (byte-parity) ─────────────────────────────

    public function testEachOptionHasTrailingNewline(): void
    {
        $actual = $this->renderTwigValuesOutput([1, 2], ['One', 'Two'], '');
        $lines  = explode("\n", rtrim($actual, "\n"));
        foreach ($lines as $line) {
            $this->assertStringStartsWith('<option', $line);
            $this->assertStringContainsString('</option>', $line);
        }
        // Every option ends with </option>\n — so splitting on \n gives only option lines
        $this->assertCount(2, $lines);
    }
}
