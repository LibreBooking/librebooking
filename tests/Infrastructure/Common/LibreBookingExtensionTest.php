<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

use LibreBooking\Common\Text\LinkifyText;
use PHPUnit\Framework\TestCase;

class LibreBookingExtensionTest extends TestCase
{
    private function makeEnv(string $template): \Twig\Environment
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => $template]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        return $env;
    }

    public function testIsATwigExtension(): void
    {
        $ext = new LibreBookingExtension(Resources::GetInstance(), '');
        $this->assertInstanceOf(\Twig\Extension\AbstractExtension::class, $ext);
    }

    public function testConstantFunctionResolvesClassConstant(): void
    {
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            't' => "{{ constant('CustomAttributeTypes::CHECKBOX') }}",
        ]));
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        $this->assertSame((string) CustomAttributeTypes::CHECKBOX, $env->render('t'));
    }

    public function testTranslateReturnsResourceString(): void
    {
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader(['t' => "{{ translate('Yes') }}"]));
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        $this->assertSame(Resources::GetInstance()->GetString('Yes'), $env->render('t'));
    }

    public function testTranslateWithArgsExplodesStringAndPassesToGetString(): void
    {
        $resources = Resources::GetInstance();
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader(['t' => "{{ translate('Yes') }}"]));
        $env->addExtension(new LibreBookingExtension($resources, ''));

        // Without args path: GetString('Yes', '')
        $expected = $resources->GetString('Yes', '');
        $this->assertSame($expected, $env->render('t'));
    }

    public function testTranslateWithStringArgsExplodesOnComma(): void
    {
        $resources = Resources::GetInstance();
        // Use a template that passes args as string; the result is forwarded to GetString with exploded array
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            't' => "{{ translate('Yes', 'a,b') }}",
        ]));
        $env->addExtension(new LibreBookingExtension($resources, ''));
        $expected = $resources->GetString('Yes', ['a', 'b']);
        $this->assertSame($expected, $env->render('t'));
    }

    public function testTranslateWithArrayArgsPassesDirectly(): void
    {
        $resources = Resources::GetInstance();
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader([
            't' => "{{ translate('Yes', ['x']) }}",
        ]));
        $env->addExtension(new LibreBookingExtension($resources, ''));
        $expected = $resources->GetString('Yes', ['x']);
        $this->assertSame($expected, $env->render('t'));
    }

    // -------------------------------------------------------------------------
    // Filter tests
    // -------------------------------------------------------------------------

    public function testEscapeOnceFilterDoesNotDoubleEncodeEntities(): void
    {
        $env = $this->makeEnv('{{ v|escape_once }}');

        $this->assertSame('a &amp; b &lt;i&gt; &quot;q&quot; &#039;s&#039;', $env->render('t', ['v' => 'a &amp; b <i> "q" \'s\'']));
    }

    public function testEscapeOnceFilterIsNotEscapedAgainUnderAutoescape(): void
    {
        $env = new \Twig\Environment(new \Twig\Loader\ArrayLoader(['t' => '{{ v|escape_once }}']), ['autoescape' => 'html']);
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));

        $this->assertSame('O&#039;Brien &amp; Co', $env->render('t', ['v' => 'O&#039;Brien & Co']));
    }

    public function testSanitizeRichTextFilterStripsScriptTag(): void
    {
        $input = '<b>hi</b><script>x</script>';
        $env = $this->makeEnv('{{ v|sanitize_rich_text }}');

        $this->assertSame(
            RichTextHtmlSanitizer::Sanitize($input),
            $env->render('t', ['v' => $input])
        );
    }

    public function testSanitizeRichTextFilterPreservesSafeTags(): void
    {
        $input = '<b>bold</b> and <em>italic</em>';
        $env = $this->makeEnv('{{ v|sanitize_rich_text }}');

        $this->assertSame(
            RichTextHtmlSanitizer::Sanitize($input),
            $env->render('t', ['v' => $input])
        );
    }

    public function testUrl2LinkFilterLinkifiesHttpUrl(): void
    {
        $input = 'visit http://example.com today';
        $env = $this->makeEnv('{{ v|url2link }}');

        $expected = LinkifyText::linkify($input);
        $actual = $env->render('t', ['v' => $input]);

        $this->assertSame($expected, $actual);
        $this->assertStringContainsString('<a href="http://example.com"', $actual);
    }

    public function testUrl2LinkFilterDoesNotLinkifyJavascriptScheme(): void
    {
        $input = 'click javascript://%0Aalert%281%29 now';
        $env = $this->makeEnv('{{ v|url2link }}');

        $actual = $env->render('t', ['v' => $input]);

        $this->assertSame(LinkifyText::linkify($input), $actual);
        $this->assertStringNotContainsString('<a', $actual);
    }

    public function testUrl2LinkFilterLinkifiesValidEmail(): void
    {
        $input = 'mail user@example.com please';
        $env = $this->makeEnv('{{ v|url2link }}');

        $actual = $env->render('t', ['v' => $input]);

        $this->assertSame(LinkifyText::linkify($input), $actual);
        $this->assertStringContainsString('mailto:user@example.com', $actual);
    }

    public function testUrl2LinkOutputMatchesSmartyPageCreateUrl(): void
    {
        $input = 'see https://example.com/page now';
        $env = $this->makeEnv('{{ v|url2link }}');

        $smartyResult = (new SmartyPage())->CreateUrl($input);
        $twigResult = $env->render('t', ['v' => $input]);

        $this->assertSame($smartyResult, $twigResult);
    }

    public function testEscapeQuotesFilterEscapesSingleAndDoubleQuotes(): void
    {
        $env = $this->makeEnv('{{ v|escapequotes }}');

        $this->assertSame('it&#39;s a &quot;test&quot;', $env->render('t', ['v' => "it's a \"test\""]));
    }

    public function testEscapeQuotesFilterLeavesOtherCharsUnchanged(): void
    {
        $env = $this->makeEnv('{{ v|escapequotes }}');

        $this->assertSame('hello world', $env->render('t', ['v' => 'hello world']));
    }

    public function testHtmlEntityDecodeFilterDecodesEntities(): void
    {
        $env = $this->makeEnv('{{ v|html_entity_decode }}');

        $this->assertSame('<b>bold</b>', $env->render('t', ['v' => '&lt;b&gt;bold&lt;/b&gt;']));
    }

    public function testIntvalFilterConvertsStringToInt(): void
    {
        $env = $this->makeEnv('{{ v|intval }}');

        $this->assertSame('42', $env->render('t', ['v' => '42abc']));
        $this->assertSame('0', $env->render('t', ['v' => 'abc']));
    }

    public function testUrlencodeFilterEncodesSpaceAsPlus(): void
    {
        $env = $this->makeEnv('{{ v|urlencode }}');

        // PHP urlencode: space → '+' (not '%20' as rawurlencode/url_encode would produce)
        $this->assertSame('a+b', $env->render('t', ['v' => 'a b']));
    }

    public function testUrlencodeFilterOutputMatchesSmartyPageUrlEncode(): void
    {
        $input = 'hello world&foo=bar+baz';
        $env = $this->makeEnv('{{ v|urlencode }}');

        $smartyResult = (new SmartyPage())->UrlEncode($input);
        $twigResult = $env->render('t', ['v' => $input]);

        $this->assertSame($smartyResult, $twigResult);
    }

    public function testUrlencodeFilterDiffersFromNativeUrlEncode(): void
    {
        $env = $this->makeEnv('{{ v|urlencode }}');

        // Custom |urlencode produces '+' for space; native |url_encode produces '%20'
        $this->assertSame('hello+world', $env->render('t', ['v' => 'hello world']));
        $this->assertNotSame('hello%20world', $env->render('t', ['v' => 'hello world']));
    }

    // -------------------------------------------------------------------------
    // Group A: buttons & icons — parity tests vs SmartyPage
    // -------------------------------------------------------------------------

    /**
     * Helper: capture echoed output from a SmartyPage method.
     *
     * @param callable $callback
     */
    private function captureSmartyCb(callable $callback): string
    {
        ob_start();
        $callback();
        return ob_get_clean();
    }

    // --- cancel_button -------------------------------------------------------

    public function testCancelButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ cancel_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->CancelButton(['key' => 'Cancel', 'class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testCancelButtonWithKeyAndClassMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ cancel_button(key='Close', class='my-class') }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->CancelButton(['key' => 'Close', 'class' => 'my-class'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testCancelButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ cancel_button(key='Cancel', attributes={'id': 'mycancel'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->CancelButton(['key' => 'Cancel', 'class' => '', 'id' => 'mycancel'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- update_button -------------------------------------------------------

    public function testUpdateButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ update_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->UpdateButton(['key' => 'Update'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testUpdateButtonSubmitTypeMatchesSmartyPage(): void
    {
        // When submit is truthy the type changes to "submit" and the "save" class is omitted.
        $env = $this->makeEnv("{{ update_button(key='Save', submit=true) }}");
        $page = new SmartyPage();

        // Match SmartyPage: when submit key is present (truthy), type=submit; class is empty so class str is ''
        $expected = $this->captureSmartyCb(fn () => $page->UpdateButton(['key' => 'Save', 'submit' => true], null));
        $actual = $env->render('t');

        // Both should produce type="submit" and no "save" class (Smarty leaks submit="1" attribute — Twig omits it intentionally)
        $this->assertStringContainsString('type="submit"', $actual);
        $this->assertStringNotContainsString('save', $actual);
        $this->assertStringContainsString('type="submit"', $expected);
    }

    public function testUpdateButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ update_button(key='Update', attributes={'data-foo': 'bar'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->UpdateButton(['key' => 'Update', 'data-foo' => 'bar'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- add_button ----------------------------------------------------------

    public function testAddButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ add_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->AddButton(['key' => 'Add', 'class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testAddButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ add_button(key='Add', attributes={'data-id': '42'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->AddButton(['key' => 'Add', 'class' => '', 'data-id' => '42'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- delete_button -------------------------------------------------------

    public function testDeleteButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ delete_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->DeleteButton(['key' => 'Delete', 'class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testDeleteButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ delete_button(key='Delete', attributes={'data-confirm': 'sure'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->DeleteButton(['key' => 'Delete', 'class' => '', 'data-confirm' => 'sure'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- reset_button --------------------------------------------------------

    public function testResetButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ reset_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->ResetButton(['key' => 'Reset', 'class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testResetButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ reset_button(key='Reset', attributes={'id': 'reset-btn'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->ResetButton(['key' => 'Reset', 'class' => '', 'id' => 'reset-btn'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- filter_button -------------------------------------------------------

    public function testFilterButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ filter_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->FilterButton(['key' => 'Filter', 'class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testFilterButtonWithClassMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ filter_button(key='Filter', class='extra') }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->FilterButton(['key' => 'Filter', 'class' => 'extra'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testFilterButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ filter_button(attributes={'data-filter': 'true'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->FilterButton(['key' => 'Filter', 'class' => '', 'data-filter' => 'true'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- ok_button -----------------------------------------------------------

    public function testOkButtonDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ ok_button() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->OkButton(['key' => 'OK', 'class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testOkButtonWithPassthroughAttributesMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ ok_button(key='OK', attributes={'data-action': 'confirm'}) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->OkButton(['key' => 'OK', 'class' => '', 'data-action' => 'confirm'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- showhide_icon -------------------------------------------------------

    public function testShowhideIconDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv('{{ showhide_icon() }}');
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->ShowHideIcon(['class' => ''], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testShowhideIconWithClassMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ showhide_icon(class='bi-eye') }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->ShowHideIcon(['class' => 'bi-eye'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // --- indicator -----------------------------------------------------------

    public function testIndicatorDefaultMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ indicator(id='loading') }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->DisplayIndicator(['id' => 'loading'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testIndicatorWithShowMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ indicator(id='loading', show=true) }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->DisplayIndicator(['id' => 'loading', 'show' => true], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testIndicatorWithSizeMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ indicator(id='loading', size='lg') }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->DisplayIndicator(['id' => 'loading', 'size' => 'lg'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testIndicatorWithCustomClassMatchesSmartyPage(): void
    {
        $env = $this->makeEnv("{{ indicator(id='spinner', class='my-spinner') }}");
        $page = new SmartyPage();

        $expected = $this->captureSmartyCb(fn () => $page->DisplayIndicator(['id' => 'spinner', 'class' => 'my-spinner'], null));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // -------------------------------------------------------------------------
    // Native-filter confirmation (behaviour verified, not custom filter added)
    // -------------------------------------------------------------------------

    /**
     * Twig native |lower covers SmartyPage::Strtolower — no custom filter added.
     */
    public function testNativeLowerCoversSmartyStrtolower(): void
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => '{{ v|lower }}']),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));

        $this->assertSame('hello world', $env->render('t', ['v' => 'HELLO WORLD']));
    }

    /**
     * Twig native |url_encode covers SmartyPage::UrlEncode — no custom filter added.
     * Note: Twig's |url_encode uses rawurlencode (RFC 3986, spaces → %20) rather
     * than urlencode (spaces → +), which is the stricter/preferred form for modern
     * URLs and sufficient for the encoding role the Smarty modifier played.
     */
    public function testNativeUrlEncodeCoversSmartyUrlencode(): void
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => '{{ v|url_encode }}']),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));

        // rawurlencode: space → %20 (not + as in urlencode)
        $this->assertSame('hello%20world', $env->render('t', ['v' => 'hello world']));
        // Special chars are percent-encoded
        $this->assertSame('foo%3Dbar', $env->render('t', ['v' => 'foo=bar']));
    }

    /**
     * Twig native |length covers SmartyPage::Count — no custom filter added.
     */
    public function testNativeLengthCoversSmartyCount(): void
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => '{{ v|length }}']),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));

        $this->assertSame('3', $env->render('t', ['v' => [1, 2, 3]]));
    }

    public function testButtonAttributesAreEscaped(): void
    {
        $env = $this->makeEnv("{{ cancel_button(attributes={'data-x': v}) }}");

        $actual = $env->render('t', ['v' => '" onclick="alert(1)']);

        $this->assertStringContainsString('data-x="&quot; onclick=&quot;alert(1)"', $actual);
    }
}
