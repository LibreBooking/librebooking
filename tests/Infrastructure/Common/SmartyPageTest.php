<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class SmartyPageTest extends TestBase
{
    public function testGlobalHeaderDisplaysWarningWhenScriptUrlIsEmpty(): void
    {
        $page = new SmartyPage();
        $page->assign('ScriptUrl', '');

        $output = $page->fetch('globalheader.tpl');

        $this->assertStringContainsString('id="script-url-warning"', $output);
        $this->assertStringContainsString('ScriptUrlNotConfigured', $output);
    }

    public function testGlobalHeaderDoesNotDisplayWarningWhenScriptUrlIsConfigured(): void
    {
        $page = new SmartyPage();
        $page->assign('ScriptUrl', 'https://librebooking.example/Web');
        $page->assign('ScriptUrlMissingWebSuffix', false);

        $output = $page->fetch('globalheader.tpl');

        $this->assertStringNotContainsString('id="script-url-warning"', $output);
        $this->assertStringNotContainsString('ScriptUrlNotConfigured', $output);
        $this->assertStringNotContainsString('id="script-url-web-suffix-warning"', $output);
        $this->assertStringNotContainsString('ScriptUrlMissingWebSuffix', $output);
    }

    public function testGlobalHeaderDisplaysWarningWhenScriptUrlIsMissingWebSuffix(): void
    {
        $page = new SmartyPage();
        $page->assign('ScriptUrl', 'https://librebooking.example');
        $page->assign('ScriptUrlMissingWebSuffix', true);

        $output = $page->fetch('globalheader.tpl');

        $this->assertStringContainsString('id="script-url-web-suffix-warning"', $output);
        $this->assertStringContainsString('ScriptUrlMissingWebSuffix', $output);
    }

    public function testGlobalHeaderPrefersEmptyWarningOverWebSuffixWarning(): void
    {
        $page = new SmartyPage();
        $page->assign('ScriptUrl', '');
        $page->assign('ScriptUrlMissingWebSuffix', true);

        $output = $page->fetch('globalheader.tpl');

        $this->assertStringContainsString('id="script-url-warning"', $output);
        $this->assertStringNotContainsString('id="script-url-web-suffix-warning"', $output);
    }

    public function testCreateUrlLinkifiesHttpAndHttpsUrls(): void
    {
        $page = new SmartyPage();

        $this->assertStringContainsString(
            '<a href="http://example.com"',
            $page->CreateUrl('visit http://example.com today')
        );
        $this->assertStringContainsString(
            '<a href="https://example.com/path"',
            $page->CreateUrl('visit https://example.com/path today')
        );
    }

    public function testCreateUrlForcesNoopenerNoreferrerOnGeneratedLinks(): void
    {
        $page = new SmartyPage();

        $this->assertStringContainsString(
            'rel="noopener noreferrer nofollow"',
            $page->CreateUrl('visit https://example.com today')
        );
    }

    public function testCreateUrlDoesNotLinkifyJavascriptScheme(): void
    {
        $page = new SmartyPage();

        $actual = $page->CreateUrl('click javascript://%0Aalert%281%29 now');

        $this->assertStringNotContainsString('<a', $actual);
        $this->assertStringNotContainsString('href=', $actual);
    }

    public function testCreateUrlDoesNotLinkifyNonHttpSchemes(): void
    {
        $page = new SmartyPage();

        $this->assertStringNotContainsString('<a', $page->CreateUrl('x ftp://host/file y'));
        $this->assertStringNotContainsString('<a', $page->CreateUrl('x data://text/html,x y'));
    }

    public function testCreateUrlLinkifiesValidEmail(): void
    {
        $page = new SmartyPage();

        $this->assertStringContainsString(
            'mailto:user@example.com',
            $page->CreateUrl('mail user@example.com please')
        );
    }
}
