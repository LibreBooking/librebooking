<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

use PHPUnit\Framework\Attributes\DataProvider;

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

    #[DataProvider('escapeCsvValues')]
    public function testEscapeCsvDoublesDoubleQuotesOnly(mixed $value, string $expected): void
    {
        $page = new SmartyPage();

        $this->assertSame($expected, $page->EscapeCsv($value));
    }

    /**
     * @return array<string, array{0:mixed, 1:string}>
     */
    public static function escapeCsvValues(): array
    {
        return [
            'plain text' => ['plain', 'plain'],
            'double quotes are doubled' => ['say "hi"', 'say ""hi""'],
            'single quotes unchanged' => ["O'Brien", "O'Brien"],
            'backslashes unchanged' => ['C:\\path', 'C:\\path'],
            'commas and newlines unchanged' => ["a,b\nc", "a,b\nc"],
            'null becomes empty string' => [null, ''],
            'integer is stringified' => [42, '42'],
        ];
    }

    public function testDisplayFullNameEncodesHtmlByDefault(): void
    {
        $page = new SmartyPage();

        $this->assertSame(
            'Mary &#039;Mae&#039; &quot;O&quot;',
            $page->DisplayFullName(['first' => "Mary 'Mae'", 'last' => '"O"'], $page)
        );
    }

    public function testDisplayFullNameEscapesForCsvWhenRequested(): void
    {
        $page = new SmartyPage();

        $this->assertSame(
            'Mary \'Mae\' ""O""',
            $page->DisplayFullName(['first' => "Mary 'Mae'", 'last' => '"O"', 'csv' => true], $page)
        );
    }

    public function testDisplayFullNameHonorsPrivacyForCsv(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, true);
        $this->fakeUser->IsAdmin = false;
        $page = new SmartyPage();

        $this->assertSame(
            'Private',
            $page->DisplayFullName(['first' => 'Mary', 'last' => 'Smith', 'csv' => true], $page)
        );
    }

    #[DataProvider('replaceQueryStringValues')]
    public function testReplaceQueryString(string $url, string $key, string|int $value, string $expected): void
    {
        $page = new SmartyPage();

        $this->assertSame($expected, $page->ReplaceQueryString($url, $key, $value));
    }

    /**
     * @return array<string, array{0:string, 1:string, 2:string|int, 3:string}>
     */
    public static function replaceQueryStringValues(): array
    {
        return [
            'adds to url without query' => ['/Web/admin/manage_users.php', 'page', 3, '/Web/admin/manage_users.php?page=3'],
            'adds when only similar key present' => ['/users.php?pageSize=20', 'page', 3, '/users.php?pageSize=20&page=3'],
            'replaces at beginning' => ['/users.php?page=2&pageSize=20&as=2', 'page', 3, '/users.php?page=3&pageSize=20&as=2'],
            'replaces in middle' => ['/users.php?as=2&page=2&sort=name', 'page', 3, '/users.php?as=2&page=3&sort=name'],
            'replaces at end' => ['/users.php?as=2&sort=name&dir=desc&page=2', 'page', 3, '/users.php?as=2&sort=name&dir=desc&page=3'],
            'replaces page size' => ['/users.php?page=3&pageSize=20&as=2', 'pageSize', 50, '/users.php?page=3&pageSize=50&as=2'],
            'ignores similar names' => ['/x.php?homepage=1&pageSize=20&page_x=4', 'page', 3, '/x.php?homepage=1&pageSize=20&page_x=4&page=3'],
            'ignores key text in values' => ['/x.php?q=page%3D2&ref=page', 'page', 3, '/x.php?q=page%3D2&ref=page&page=3'],
            'preserves absolute url' => ['https://example.com:8443/Web/users.php?page=1', 'page', 2, 'https://example.com:8443/Web/users.php?page=2'],
            'preserves fragment' => ['/users.php?page=1#list', 'page', 2, '/users.php?page=2#list'],
            'adds before fragment' => ['/users.php#list', 'page', 2, '/users.php?page=2#list'],
            'fragment containing query characters' => ['/users.php?as=2#a?page=1&b', 'page', 2, '/users.php?as=2&page=2#a?page=1&b'],
            'preserves unrelated fields' => [
                '/x.php?q=a%20b+c&tag=1&tag=2&ids[]=4&ids%5B%5D=5&empty=&flag&a.b=1&page=1',
                'page',
                2,
                '/x.php?q=a+b+c&tag=1&tag=2&ids%5B%5D=4&ids%5B%5D=5&empty=&flag&a.b=1&page=2',
            ],
            'treats plus as space' => ['/users.php?q=John+Smith&page=1', 'page', 2, '/users.php?q=John+Smith&page=2'],
            'preserves literal plus' => ['/users.php?q=1%2B1&page=1', 'page', 2, '/users.php?q=1%2B1&page=2'],
            'adds to empty query' => ['/Web/admin/manage_users.php?', 'page', 2, '/Web/admin/manage_users.php?page=2'],
            'skips empty segments' => ['/x.php?&&as=2&&page=1', 'page', 2, '/x.php?as=2&page=2'],
            'keeps empty-name fields with values' => ['/x.php?=keep&page=1', 'page', 2, '/x.php?=keep&page=2'],
            'handles server url format' => [
                '/users.php?' . http_build_query(['q' => 'John Smith', 'ids' => [4, 5], 'page' => 1]),
                'page',
                2,
                '/users.php?q=John+Smith&ids%5B0%5D=4&ids%5B1%5D=5&page=2',
            ],
            'collapses repeated target' => ['/x.php?page=1&as=2&page=5', 'page', 3, '/x.php?page=3&as=2'],
            'recognizes encoded target name' => ['/x.php?pag%65=1&as=2', 'page', 3, '/x.php?page=3&as=2'],
            'encodes replacement value' => ['/x.php?as=2', 'q', 'a b&c=d"\'é', '/x.php?as=2&q=a+b%26c%3Dd%22%27%C3%A9'],
            'preserves view all page size' => ['/users.php?page=2&pageSize=20', 'pageSize', -1, '/users.php?page=2&pageSize=-1'],
        ];
    }

    public function testReplaceQueryStringUpdatesPageThenPageSize(): void
    {
        $page = new SmartyPage();

        $url = $page->ReplaceQueryString('/users.php?page=2&pageSize=20&as=2', 'page', 3);
        $url = $page->ReplaceQueryString($url, 'pageSize', 20);

        $this->assertSame('/users.php?page=3&pageSize=20&as=2', $url);
    }

    public function testEscapeCsvModifierIsRegistered(): void
    {
        $page = new SmartyPage();
        $page->assign('value', 'say "hi"');

        $this->assertSame('"say ""hi"""', $page->fetch('string:"{$value|escape_csv}"'));
    }
}
