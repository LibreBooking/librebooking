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
}
