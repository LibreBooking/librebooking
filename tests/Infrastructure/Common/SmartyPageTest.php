<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

use PHPUnit\Framework\Attributes\DataProvider;

class SmartyPageTest extends TestBase
{
    public function testValidationGroupHandlesOpeningAndClosingCalls(): void
    {
        $page = new SmartyPage();

        $output = $page->fetch('string:{validation_group}<li>Required</li>{/validation_group}');

        $this->assertStringContainsString('<ul class="list-unstyled"><li>Required</li></ul>', $output);
        $this->assertSame('', $page->fetch('string:{validation_group}{/validation_group}'));
    }

    public function testDataTableHelpersAcceptSmartyTemplateArgument(): void
    {
        $page = new SmartyPage();

        $this->assertStringContainsString(
            'new DataTable("#report-results"',
            $page->fetch('string:{datatable tableId="report-results"}')
        );
        $this->assertStringContainsString(
            'new DataTable("#resources"',
            $page->fetch('string:{datatablefilter tableId="resources"}')
        );
    }

    #[DataProvider('renderingPluginProvider')]
    public function testRenderingPluginsReturnOutputForModifiers(string $plugin, string $arguments, string $expectedFragment): void
    {
        $page = new SmartyPage();
        $this->fakeResources->CurrentLanguage = 'en_US';
        $this->fakeUser->CSRFToken = 'test-token';
        $attribute = $this->createMock(CustomAttribute::class);
        $attribute->method('Type')->willReturn(CustomAttributeTypes::CHECKBOX);
        $page->assign('attribute', $attribute);
        $page->registerPlugin('modifier', 'wrap_output', static fn ($value): string => '[' . $value . ']');

        $output = $page->fetch('string:{' . $plugin . ' ' . $arguments . '}');
        $this->assertStringContainsString($expectedFragment, $output);
        $this->assertSame(
            '[' . $output . ']',
            $page->fetch('string:{' . $plugin . '|wrap_output ' . $arguments . '}')
        );
    }

    public static function renderingPluginProvider(): array
    {
        return [
            'JavaScript' => ['jsfile', 'src="app.js" async=true', 'scripts/app.js?v='],
            'CSS' => ['cssfile', 'src="app.css"', 'css/app.css?v='],
            'vendor JavaScript' => ['vendor_js', 'src="app.js" async=true', 'assets/vendor/app.js?v='],
            'vendor CSS' => ['vendor_css', 'src="app.css"', 'assets/vendor/app.css?v='],
            'indicator' => ['indicator', '', 'spinner-border-sm indicator d-none'],
            'checked attribute' => ['read_only_attribute', 'attribute=$attribute value=1', 'Yes'],
            'CSRF token' => ['csrf_token', '', 'value="test-token"'],
            'cancel button' => ['cancel_button', '', 'Cancel</button>'],
            'update button' => ['update_button', '', 'Update</button>'],
            'add button' => ['add_button', '', 'Add</button>'],
            'delete button' => ['delete_button', '', 'Delete</button>'],
            'reset button' => ['reset_button', '', 'Reset</button>'],
            'filter button' => ['filter_button', '', 'Filter</button>'],
            'OK button' => ['ok_button', '', 'OK</button>'],
            'show/hide icon' => ['showhide_icon', '', 'Show/Hide</span>'],
            'sort column' => ['sort_column', 'field="name" key="Name"', '>Name </a>'],
            'currency' => ['formatcurrency', 'amount=12.50 currency="USD"', '12.50'],
        ];
    }

    public function testReadOnlyTextAttributeReturnsStringWithoutOutput(): void
    {
        $page = new SmartyPage();
        $template = $page->createTemplate('string:');
        $attribute = $this->createMock(CustomAttribute::class);
        $attribute->method('Type')->willReturn(CustomAttributeTypes::SINGLE_LINE_TEXTBOX);
        $this->expectOutputString('');

        $this->assertSame('42', $page->ReadOnlyAttribute(['attribute' => $attribute, 'value' => 42], $template));
        $this->assertSame('', $page->ReadOnlyAttribute(['attribute' => $attribute, 'value' => null], $template));
    }

    public function testHtmlLinkEscapesTextAndAttributes(): void
    {
        $page = new SmartyPage();
        $page->assign('label', '<b>Users</b> &amp; Groups');
        $page->assign('url', '/users?name=" onclick="alert(1)&all=1');
        $page->assign('title', 'Users" onmouseover="alert(1)');

        $output = $page->fetch('string:{html_link key=$label href=$url title=$title data-title=$title}');

        $this->assertStringContainsString('&lt;b&gt;Users&lt;/b&gt; &amp; Groups</a>', $output);
        $this->assertStringContainsString('href="/users?name=&quot; onclick=&quot;alert(1)&amp;all=1"', $output);
        $this->assertStringContainsString('title="Users&quot; onmouseover=&quot;alert(1)"', $output);
        $this->assertStringContainsString('data-title="Users&quot; onmouseover=&quot;alert(1)"', $output);
        $this->assertStringNotContainsString('&amp;amp;', $output);
    }

    public function testTextboxEscapesAdditionalAttributesAndItsOwnAttributes(): void
    {
        $page = new SmartyPage();
        $page->assign('attribute', 'field" onfocus="alert(1)');
        $page->assign('placeholder', 'Name &amp; surname <required>');

        $output = $page->fetch('string:{textbox name=FIRST_NAME id=$attribute type=$attribute data-title=$attribute placeholder=$placeholder}');

        $this->assertStringContainsString('id="field&quot; onfocus=&quot;alert(1)"', $output);
        $this->assertStringContainsString('type="field&quot; onfocus=&quot;alert(1)"', $output);
        $this->assertStringContainsString('data-title="field&quot; onfocus=&quot;alert(1)"', $output);
        $this->assertStringContainsString('placeholder="Name &amp; surname &lt;required&gt;"', $output);
        $this->assertSame(1, substr_count($output, ' id="'));
    }

    public function testObjectOptionsEscapeLabelsAndValuesWithoutChangingSelection(): void
    {
        $page = new SmartyPage();
        $value = 'room" onclick="alert(1)';
        $label = '</option><script>alert(1)</script> &amp; Room';
        $page->assign('options', [(object) ['id' => $value, 'name' => $label]]);
        $page->assign('selected', $value);

        $output = $page->fetch('string:{object_html_options options=$options key=id label=name usemethod=false selected=$selected}');

        $this->assertStringContainsString('value="room&quot; onclick=&quot;alert(1)" selected="selected"', $output);
        $this->assertStringContainsString('label="&lt;/option&gt;&lt;script&gt;alert(1)&lt;/script&gt; &amp; Room"', $output);
        $this->assertStringContainsString('>&lt;/option&gt;&lt;script&gt;alert(1)&lt;/script&gt; &amp; Room</option>', $output);
        $this->assertStringNotContainsString('<script>', $output);
    }

    public function testJavascriptArrayPreservesValuesWithoutClosingScript(): void
    {
        $page = new SmartyPage();
        $values = ['quote' => '"', 'markup' => '</script><script>alert(1)</script>', 'newline' => "line\nnext", 'slash' => 'a\\b', 'number' => 7];
        $page->assign('values', $values);

        $output = $page->fetch('string:{js_array array=$values}');

        $this->assertSame(['"', '</script><script>alert(1)</script>', "line\nnext", 'a\\b', '7'], json_decode($output, true, flags: JSON_THROW_ON_ERROR));
        $this->assertStringNotContainsString('<', $output);
        $this->assertSame('[]', $page->CreateJavascriptArray(['array' => []], $page->createTemplate('string:')));
        $this->assertSame(["Jos\u{FFFD}"], json_decode($page->CreateJavascriptArray(['array' => ["Jos\xE9"]], $page->createTemplate('string:')), true));
    }

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
            $page->DisplayFullName(['first' => "Mary 'Mae'", 'last' => '"O"'], $page->createTemplate('string:'))
        );
    }

    public function testDisplayFullNameEscapesForCsvWhenRequested(): void
    {
        $page = new SmartyPage();

        $this->assertSame(
            'Mary \'Mae\' ""O""',
            $page->DisplayFullName(['first' => "Mary 'Mae'", 'last' => '"O"', 'csv' => true], $page->createTemplate('string:'))
        );
    }

    public function testDisplayFullNameHonorsPrivacyForCsv(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, true);
        $this->fakeUser->IsAdmin = false;
        $page = new SmartyPage();

        $this->assertSame(
            'Private',
            $page->DisplayFullName(['first' => 'Mary', 'last' => 'Smith', 'csv' => true], $page->createTemplate('string:'))
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

    public function testDisplayFullNameDoesNotDoubleEncodeStoredNames(): void
    {
        $page = new SmartyPage();

        $this->assertSame(
            'Smith &amp; Sons &lt;b&gt;',
            $page->DisplayFullName(['first' => 'Smith &amp; Sons', 'last' => '<b>'], $page->createTemplate('string:'))
        );
    }

    public function testDisplayFullNameSubstitutesInvalidUtf8(): void
    {
        $page = new SmartyPage();

        $this->assertSame(
            "Jos\u{FFFD} &lt;b&gt;",
            $page->DisplayFullName(['first' => "Jos\xE9", 'last' => '<b>'], $page->createTemplate('string:'))
        );
    }

    public function testEscapeWithoutDoubleEncodeEscapesRawAndKeepsStoredEntities(): void
    {
        $page = new SmartyPage();
        $page->assign('value', '<b>Smith &amp; Sons</b>');

        $this->assertSame(
            '&lt;b&gt;Smith &amp; Sons&lt;/b&gt;',
            $page->fetch("string:{\$value|escape:'html':'UTF-8':false}")
        );
    }

    public function testEscapeCsvModifierIsRegistered(): void
    {
        $page = new SmartyPage();
        $page->assign('value', 'say "hi"');

        $this->assertSame('"say ""hi"""', $page->fetch('string:"{$value|escape_csv}"'));
    }

    public function testCreatePaginationLinksKeepFiltersAndEscapeHref(): void
    {
        $this->fakeServer->Url = '/Web/admin/manage_users.php?' . http_build_query([
            'page' => 2,
            'pageSize' => 20,
            'as' => 2,
            'q' => "O'Brien & Sons",
        ]);
        $page = new SmartyPage();

        $output = $page->CreatePagination(['pageInfo' => new PageInfo(45, 2, 20)], $page->createTemplate('string:'));

        $this->assertStringContainsString(
            'href="/Web/admin/manage_users.php?page=3&amp;pageSize=20&amp;as=2&amp;q=O%27Brien+%26+Sons" data-page="3"',
            $output
        );
        $this->assertStringNotContainsString('&amp;amp;', $output);

        $queries = $this->GetPaginationLinkQueries($output);
        $this->assertSame(['1', '1', '2', '3', '3'], array_column($queries, 'page'));
        foreach ($queries as $query) {
            $this->assertSame('2', $query['as']);
            $this->assertSame("O'Brien & Sons", $query['q']);
        }
    }

    public function testCreatePaginationViewAllLink(): void
    {
        $this->fakeServer->Url = '/Web/admin/manage_users.php?pageSize=20&as=2';
        $page = new SmartyPage();

        $output = $page->CreatePagination(['pageInfo' => new PageInfo(45, 1, 20)], $page->createTemplate('string:'));

        $this->assertStringContainsString(
            '<a class="link-primary" href="/Web/admin/manage_users.php?pageSize=-1&amp;as=2&amp;page=1"',
            $output
        );
    }

    /**
     * @return list<array<string, string>> Decoded query of each pagination link
     */
    private function GetPaginationLinkQueries(string $output): array
    {
        preg_match_all('/<li class="[^"]*page-item"><a [^>]*href="([^"]*)"/', $output, $matches);

        $queries = [];
        foreach ($matches[1] as $href) {
            parse_str((string) parse_url(html_entity_decode($href, ENT_QUOTES, 'UTF-8'), PHP_URL_QUERY), $query);
            $queries[] = $query;
        }

        return $queries;
    }
}
