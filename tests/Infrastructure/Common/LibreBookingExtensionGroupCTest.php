<?php

require_once(__DIR__ . '/../../../lib/Common/namespace.php');

/**
 * Group C tests: links, URLs, images, formatting, utility.
 * Extends TestBase so setUp() wires ServiceLocator, FakeServer, FakeConfig, FakeResources.
 */
class LibreBookingExtensionGroupCTest extends TestBase
{
    private function makeEnv(string $template): \Twig\Environment
    {
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => $template]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), 'http://example.com/'));
        return $env;
    }

    /** Capture output echoed by a callable (for SmartyPage methods that echo). */
    private function captureEcho(callable $cb): string
    {
        ob_start();
        $cb();
        return (string) ob_get_clean();
    }

    // ── html_link ────────────────────────────────────────────────────────────

    public function testHtmlLinkWithRelativeHrefPrependsRootPath(): void
    {
        $env = $this->makeEnv("{{ html_link(key='Yes', href='some/path') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('http://example.com/some/path', $actual);
        $this->assertStringContainsString('class="link-primary"', $actual);
        $this->assertStringContainsString('bi bi-people-fill', $actual);
    }

    public function testHtmlLinkWithAbsoluteHrefDoesNotPrependRootPath(): void
    {
        $env = $this->makeEnv("{{ html_link(key='Yes', href='/absolute/path') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('href="/absolute/path"', $actual);
        $this->assertStringNotContainsString('http://example.com//absolute', $actual);
    }

    public function testHtmlLinkTitleDefaultsToKeyString(): void
    {
        $env = $this->makeEnv("{{ html_link(key='Yes', href='x') }}");
        $actual = $env->render('t');

        $label = Resources::GetInstance()->GetString('Yes');
        $this->assertStringContainsString('title="' . $label . '"', $actual);
    }

    public function testHtmlLinkWithExplicitTitleKey(): void
    {
        $env = $this->makeEnv("{{ html_link(key='Yes', href='x', title='No') }}");
        $actual = $env->render('t');

        $titleStr = Resources::GetInstance()->GetString('No');
        $this->assertStringContainsString('title="' . $titleStr . '"', $actual);
    }

    public function testHtmlLinkWithExtraAttributesPassedThrough(): void
    {
        $env = $this->makeEnv("{{ html_link(key='Yes', href='x', attributes={'data-id': '99'}) }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('data-id="99"', $actual);
    }

    public function testHtmlLinkMatchesSmartyPage(): void
    {
        $page = new SmartyPage();
        $params = ['key' => 'Yes', 'href' => 'my/page'];
        $expected = $page->PrintLink($params, null);

        // SmartyPage uses $this->RootPath which defaults to '' in SmartyPage
        $env = new \Twig\Environment(
            new \Twig\Loader\ArrayLoader(['t' => "{{ html_link(key='Yes', href='my/page') }}"]),
            ['autoescape' => false]
        );
        $env->addExtension(new LibreBookingExtension(Resources::GetInstance(), ''));
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── add_querystring ───────────────────────────────────────────────────────

    public function testAddQuerystringAppendsKnownKey(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/index.php';
        $_GET = [];
        $_SERVER['QUERY_STRING'] = '';

        $env = $this->makeEnv("{{ add_querystring(key='SORT_DIRECTION', value='asc') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString(QueryStringKeys::SORT_DIRECTION . '=asc', $actual);
    }

    public function testAddQuerystringMatchesSmartyPage(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/index.php';
        $_GET = [];
        $_SERVER['QUERY_STRING'] = '';

        $page = new SmartyPage();
        $expected = $page->AddQueryString(['key' => 'SORT_DIRECTION', 'value' => 'asc'], null);

        $env = $this->makeEnv("{{ add_querystring(key='SORT_DIRECTION', value='asc') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testAddQuerystringAppendsToExistingQueryString(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/index.php';
        $_GET = ['foo' => 'bar'];
        $_SERVER['QUERY_STRING'] = 'foo=bar';

        $env = $this->makeEnv("{{ add_querystring(key='SORT_DIRECTION', value='desc') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('foo=bar', $actual);
        $this->assertStringContainsString(QueryStringKeys::SORT_DIRECTION . '=desc', $actual);
    }

    // ── sort_column ───────────────────────────────────────────────────────────

    public function testSortColumnRendersLinkWithField(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/manage.php';
        $_GET = [];
        $_SERVER['QUERY_STRING'] = '';

        $env = $this->makeEnv("{{ sort_column(field='name', key='Yes') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('<a href="', $actual);
        $this->assertStringContainsString(QueryStringKeys::SORT_FIELD . '=name', $actual);
        $this->assertStringContainsString(QueryStringKeys::SORT_DIRECTION . '=asc', $actual);
    }

    public function testSortColumnMatchesSmartyPage(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/manage.php';
        $_GET = [];
        $_SERVER['QUERY_STRING'] = '';

        $page = new SmartyPage();
        $expected = $this->captureEcho(fn () => $page->SortColumn(['field' => 'name', 'key' => 'Yes'], null));

        $env = $this->makeEnv("{{ sort_column(field='name', key='Yes') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testSortColumnActiveFieldTogglesDirection(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/manage.php';
        $_GET = [QueryStringKeys::SORT_FIELD => 'name', QueryStringKeys::SORT_DIRECTION => 'asc'];
        $_SERVER['QUERY_STRING'] = QueryStringKeys::SORT_FIELD . '=name&' . QueryStringKeys::SORT_DIRECTION . '=asc';
        // FakeServer::GetQuerystring reads from $this->Get, not $_GET
        $this->fakeServer->SetQuerystring(QueryStringKeys::SORT_FIELD, 'name');
        $this->fakeServer->SetQuerystring(QueryStringKeys::SORT_DIRECTION, 'asc');

        $env = $this->makeEnv("{{ sort_column(field='name', key='Yes') }}");
        $actual = $env->render('t');

        // Active asc field → URL should have desc, indicator is bi-caret-up-fill
        $this->assertStringContainsString(QueryStringKeys::SORT_DIRECTION . '=desc', $actual);
        $this->assertStringContainsString('bi-caret-up-fill', $actual);
    }

    public function testSortColumnActiveFieldDescTogglesToAsc(): void
    {
        $_SERVER['SCRIPT_NAME'] = '/app/manage.php';
        $_GET = [QueryStringKeys::SORT_FIELD => 'name', QueryStringKeys::SORT_DIRECTION => 'desc'];
        $_SERVER['QUERY_STRING'] = QueryStringKeys::SORT_FIELD . '=name&' . QueryStringKeys::SORT_DIRECTION . '=desc';
        // FakeServer::GetQuerystring reads from $this->Get, not $_GET
        $this->fakeServer->SetQuerystring(QueryStringKeys::SORT_FIELD, 'name');
        $this->fakeServer->SetQuerystring(QueryStringKeys::SORT_DIRECTION, 'desc');

        $env = $this->makeEnv("{{ sort_column(field='name', key='Yes') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString(QueryStringKeys::SORT_DIRECTION . '=asc', $actual);
        $this->assertStringContainsString('bi-caret-down-fill', $actual);
    }

    // ── resource_image ────────────────────────────────────────────────────────

    public function testResourceImageWithRelativeUrlPrependsScriptUrl(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::UPLOAD_IMAGE_URL, 'uploads/images');
        $this->fakeConfig->_ScriptUrl = 'http://example.com';

        $env = $this->makeEnv("{{ resource_image(image='photo.jpg') }}");
        $actual = $env->render('t');

        $this->assertSame('http://example.com/uploads/images/photo.jpg', $actual);
    }

    public function testResourceImageWithAbsoluteHttpUrlDoesNotPrepend(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::UPLOAD_IMAGE_URL, 'http://cdn.example.com/uploads');
        $this->fakeConfig->_ScriptUrl = 'http://example.com';

        $env = $this->makeEnv("{{ resource_image(image='photo.jpg') }}");
        $actual = $env->render('t');

        $this->assertSame('http://cdn.example.com/uploads/photo.jpg', $actual);
    }

    public function testResourceImageMatchesSmartyPage(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::UPLOAD_IMAGE_URL, 'uploads/images');
        $this->fakeConfig->_ScriptUrl = 'http://example.com';

        $page = new SmartyPage();
        $expected = $page->GetResourceImage(['image' => 'avatar.png'], null);

        $env = $this->makeEnv("{{ resource_image(image='avatar.png') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── fullname ──────────────────────────────────────────────────────────────

    public function testFullnameReturnsFormattedName(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, false);

        $env = $this->makeEnv("{{ fullname(first='John', last='Doe') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('John', $actual);
        $this->assertStringContainsString('Doe', $actual);
    }

    public function testFullnameReturnsPrivateWhenPrivacyEnabledAndNotAdmin(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, true);
        $this->fakeUser->IsAdmin = false;

        $env = $this->makeEnv("{{ fullname(first='John', last='Doe') }}");
        $actual = $env->render('t');

        $privateStr = Resources::GetInstance()->GetString('Private');
        $this->assertSame($privateStr, $actual);
    }

    public function testFullnameIgnoresPrivacyWhenIgnorePrivacyTrue(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, true);
        $this->fakeUser->IsAdmin = false;

        $env = $this->makeEnv("{{ fullname(first='John', last='Doe', ignorePrivacy=true) }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('John', $actual);
        $this->assertStringContainsString('Doe', $actual);
    }

    public function testFullnameMatchesSmartyPage(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, false);

        $page = new SmartyPage();
        $expected = $page->DisplayFullName(['first' => 'Jane', 'last' => 'Smith'], null);

        $env = $this->makeEnv("{{ fullname(first='Jane', last='Smith') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testFullnameAdminBypassesPrivacy(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, true);
        $this->fakeUser->IsAdmin = true;

        $env = $this->makeEnv("{{ fullname(first='John', last='Doe') }}");
        $actual = $env->render('t');

        $this->assertStringContainsString('John', $actual);
    }

    // ── formatdate / format_date ───────────────────────────────────────────────

    public function testFormatdateReturnsEmptyForEmptyDate(): void
    {
        $env = $this->makeEnv("{{ formatdate(date='') }}");
        $actual = $env->render('t');

        $this->assertSame('', $actual);
    }

    public function testFormatdateFormatsDateWithDefaultKey(): void
    {
        $date = Date::Create(2024, 3, 15, 10, 0, 0, 'UTC');

        $env = $this->makeEnv('{{ formatdate(date=d) }}');
        $actual = $env->render('t', ['d' => $date]);

        $page = new SmartyPage();
        $expected = $page->FormatDate(['date' => $date], null);

        $this->assertSame($expected, $actual);
    }

    public function testFormatdateWithExplicitFormat(): void
    {
        $date = Date::Create(2024, 3, 15, 10, 0, 0, 'UTC');

        $env = $this->makeEnv("{{ formatdate(date=d, format='Y-m-d') }}");
        $actual = $env->render('t', ['d' => $date]);

        $this->assertSame('2024-03-15', $actual);
    }

    public function testFormatdateWithKey(): void
    {
        $date = Date::Create(2024, 3, 15, 10, 0, 0, 'UTC');

        $env = $this->makeEnv("{{ formatdate(date=d, key='general_date') }}");
        $actual = $env->render('t', ['d' => $date]);

        $page = new SmartyPage();
        $expected = $page->FormatDate(['date' => $date, 'key' => 'general_date'], null);

        $this->assertSame($expected, $actual);
    }

    public function testFormatDateAliasMatchesFormatdate(): void
    {
        $date = Date::Create(2024, 3, 15, 10, 0, 0, 'UTC');

        $envA = $this->makeEnv('{{ formatdate(date=d) }}');
        $envB = $this->makeEnv('{{ format_date(date=d) }}');

        $this->assertSame($envA->render('t', ['d' => $date]), $envB->render('t', ['d' => $date]));
    }

    public function testFormatdateMatchesSmartyPageForStringDate(): void
    {
        $page = new SmartyPage();
        $expected = $page->FormatDate(['date' => '2024-06-01T00:00:00'], null);

        $env = $this->makeEnv("{{ formatdate(date='2024-06-01T00:00:00') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── formatcurrency ────────────────────────────────────────────────────────

    public function testFormatcurrencyReturnsCurrencyString(): void
    {
        $env = $this->makeEnv("{{ formatcurrency(amount=10.50, currency='USD') }}");
        $actual = $env->render('t');

        $this->assertNotSame('', $actual);
    }

    public function testFormatcurrencyMatchesSmartyPageOutput(): void
    {
        $page = new SmartyPage();
        $expected = $this->captureEcho(fn () => $page->FormatCurrency(['amount' => 42.75, 'currency' => 'USD'], null));

        $env = $this->makeEnv("{{ formatcurrency(amount=42.75, currency='USD') }}");
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    public function testFormatcurrencyDefaultsToZeroForNonNumeric(): void
    {
        $env = $this->makeEnv("{{ formatcurrency(amount='abc', currency='USD') }}");
        $actual = $env->render('t');

        $page = new SmartyPage();
        $expected = $this->captureEcho(fn () => $page->FormatCurrency(['amount' => 'abc', 'currency' => 'USD'], null));

        $this->assertSame($expected, $actual);
    }

    // ── js_array ──────────────────────────────────────────────────────────────

    public function testJsArrayFormatsCorrectly(): void
    {
        $env = $this->makeEnv("{{ js_array(array=['a','b','c']) }}");
        $actual = $env->render('t');

        $this->assertSame('["a","b","c"]', $actual);
    }

    public function testJsArrayMatchesSmartyPage(): void
    {
        $items = ['foo', 'bar', 'baz'];
        $page = new SmartyPage();
        $expected = $page->CreateJavascriptArray(['array' => $items], null);

        $env = $this->makeEnv('{{ js_array(array=items) }}');
        $actual = $env->render('t', ['items' => $items]);

        $this->assertSame($expected, $actual);
    }

    public function testJsArraySingleItemNoComa(): void
    {
        $env = $this->makeEnv("{{ js_array(array=['only']) }}");
        $actual = $env->render('t');

        $this->assertSame('["only"]', $actual);
    }

    public function testJsArrayEmptyArray(): void
    {
        $env = $this->makeEnv('{{ js_array(array=[]) }}');
        $actual = $env->render('t');

        $this->assertSame('[""]', $actual);
    }

    // ── linebreak ─────────────────────────────────────────────────────────────

    public function testLinebreakReturnsNewline(): void
    {
        $env = $this->makeEnv('{{ linebreak() }}');
        $actual = $env->render('t');

        $this->assertSame("\n", $actual);
    }

    public function testLinebreakMatchesSmartyPage(): void
    {
        $page = new SmartyPage();
        $expected = $page->LineBreak([], null);

        $env = $this->makeEnv('{{ linebreak() }}');
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }

    // ── flush ─────────────────────────────────────────────────────────────────

    public function testFlushReturnsFlushingComment(): void
    {
        $env = $this->makeEnv('{{ flush() }}');
        $actual = $env->render('t');

        $this->assertStringContainsString('<!-- flushing -->', $actual);
    }

    public function testFlushOutputMatchesSmartyPageEcho(): void
    {
        $page = new SmartyPage();
        $expected = $this->captureEcho(fn () => $page->Flush([], null));

        $env = $this->makeEnv('{{ flush() }}');
        $actual = $env->render('t');

        $this->assertSame($expected, $actual);
    }
}
