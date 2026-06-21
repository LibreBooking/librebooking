<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Export/CalendarSubscriptionPage.php');

/**
 * Tests for SubscriptionPage::tryBasicAuth() via CalendarSubscriptionPage.
 *
 * tryBasicAuth() lives on the abstract SubscriptionPage base class and guards
 * all calendar-feed pages. The verified scenarios cover the fail-closed gates
 * (ICS disabled, missing icskey) as well as the original four basic-auth
 * branches:
 *
 *   - ICS disabled              → notFound, no credential check
 *   - Missing subscription key  → notFound, no credential check
 *   - basic.auth flag off       → no-op, validator handles icskey downstream
 *   - basic.auth on, no creds   → notFound (would-be 401 to the client)
 *   - basic.auth on, bad creds  → notFound (would-be 401 to the client)
 *   - basic.auth on, good creds → feedUserSession captured, notFound stays false
 */
class CalendarSubscriptionPageTest extends TestBase
{
    private TestableSubscriptionPage $page;

    public function setUp(): void
    {
        parent::setUp();
        unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        $this->page = new TestableSubscriptionPage();
        $this->page->SubscriptionKey = 'feed-secret';
        $this->fakeConfig->SetKey(ConfigKeys::ICS_ENABLED, true);
    }

    public function tearDown(): void
    {
        unset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW']);
        parent::tearDown();
    }

    public function testFailsClosedWhenIcsIsDisabled(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_ENABLED, false);
        $this->fakeConfig->SetKey(ConfigKeys::ICS_BASIC_AUTH, true);
        $_SERVER['PHP_AUTH_USER'] = 'admin';
        $_SERVER['PHP_AUTH_PW'] = 'correctpassword';
        $this->page->fakeAuth->_ValidateResult = true;

        $this->page->callTryBasicAuth();

        $this->assertTrue($this->page->isNotFound(), 'notFound must be set when ICS is disabled');
        $this->assertFalse($this->page->fakeAuth->_ValidateCalled, 'Validate() must not be invoked when ICS is disabled');
        $this->assertFalse($this->page->fakeAuth->_LoginForFeedCalled, 'LoginForFeed must not be invoked when ICS is disabled');
    }

    public function testFailsClosedWhenSubscriptionKeyIsMissing(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_BASIC_AUTH, true);
        $this->page->SubscriptionKey = '';
        $_SERVER['PHP_AUTH_USER'] = 'admin';
        $_SERVER['PHP_AUTH_PW'] = 'correctpassword';
        $this->page->fakeAuth->_ValidateResult = true;

        $this->page->callTryBasicAuth();

        $this->assertTrue($this->page->isNotFound(), 'notFound must be set when subscription key is missing');
        $this->assertFalse($this->page->fakeAuth->_ValidateCalled, 'Validate() must not be invoked without a subscription key');
        $this->assertFalse($this->page->fakeAuth->_LoginForFeedCalled, 'LoginForFeed must not be invoked without a subscription key');
    }

    public function testBasicAuthIsNoopWhenDisabled(): void
    {
        // basic.auth defaults to false — tryBasicAuth() must be a no-op once
        // the ICS_ENABLED and icskey gates have passed.
        $_SERVER['PHP_AUTH_USER'] = 'user';
        $_SERVER['PHP_AUTH_PW'] = 'pass';

        $this->page->callTryBasicAuth();

        $this->assertFalse($this->page->isNotFound(), 'notFound should stay false when Basic Auth is disabled');
        $this->assertFalse($this->page->fakeAuth->_LoginForFeedCalled, 'LoginForFeed must not be called when feature is off');
    }

    public function testBasicAuthSets401WhenCredentialsMissing(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_BASIC_AUTH, true);
        // No PHP_AUTH_USER / PHP_AUTH_PW in $_SERVER.

        $this->page->callTryBasicAuth();

        $this->assertTrue($this->page->isNotFound(), 'notFound should be set when credentials are absent');
        $this->assertFalse($this->page->fakeAuth->_LoginForFeedCalled, 'LoginForFeed must not be called without credentials');
    }

    public function testBasicAuthSets401WhenCredentialsAreInvalid(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_BASIC_AUTH, true);
        $_SERVER['PHP_AUTH_USER'] = 'user';
        $_SERVER['PHP_AUTH_PW'] = 'wrongpassword';
        $this->page->fakeAuth->_ValidateResult = false;

        $this->page->callTryBasicAuth();

        $this->assertTrue($this->page->isNotFound(), 'notFound should be set when credentials are invalid');
        $this->assertFalse($this->page->fakeAuth->_LoginForFeedCalled, 'LoginForFeed must not be called on failed validation');
    }

    public function testBasicAuthSucceedsAndCapturesFeedSession(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::ICS_BASIC_AUTH, true);
        $_SERVER['PHP_AUTH_USER'] = 'admin';
        $_SERVER['PHP_AUTH_PW'] = 'correctpassword';
        $this->page->fakeAuth->_ValidateResult = true;

        $this->page->callTryBasicAuth();

        $this->assertFalse($this->page->isNotFound(), 'notFound must stay false when credentials are valid');
        $this->assertTrue($this->page->fakeAuth->_LoginForFeedCalled, 'LoginForFeed must be called to build the feed session');
        $this->assertSame('admin', $this->page->fakeAuth->_LastLogin, 'LoginForFeed must receive the authenticated username');
        $this->assertNotNull($this->page->GetFeedUserSession(), 'feedUserSession must be captured for the presenter');
    }

    public function testPageLoadSets404WhenPresenterReportsInvalid(): void
    {
        // e.g. wrong (but non-empty) subscription key, or a resource/schedule
        // with subscriptions disabled — CalendarSubscriptionValidator::IsValid()
        // returns false, so presenter->PageLoad() returns false without ever
        // calling SetIsNotFound(). PageLoad() must still treat that as not-found
        // rather than falling through to renderFeed() with an empty result.
        $presenter = $this->createMock(CalendarSubscriptionPresenter::class);
        $presenter->method('PageLoad')->willReturn(false);
        $this->page->setPresenter($presenter);

        $this->page->PageLoad();

        $this->assertTrue($this->page->isNotFound(), 'notFound must be set when the presenter reports an invalid request');
        $this->assertFalse($this->page->renderFeedCalled, 'renderFeed must not run when the presenter reports an invalid request');
    }

    public function testPageLoadRendersFeedWhenPresenterSucceeds(): void
    {
        $presenter = $this->createMock(CalendarSubscriptionPresenter::class);
        $presenter->method('PageLoad')->willReturn(true);
        $this->page->setPresenter($presenter);

        $this->page->PageLoad();

        $this->assertFalse($this->page->isNotFound());
        $this->assertTrue($this->page->renderFeedCalled, 'renderFeed must run when the presenter reports a valid request');
    }
}

/**
 * Thin test double for CalendarSubscriptionPage.
 *
 * Skips the real constructor (which wires up DB repositories, the presenter,
 * etc.) and injects a FakeWebAuthentication so that tryBasicAuth() can be
 * exercised without any infrastructure.
 */
class TestableSubscriptionPage extends CalendarSubscriptionPage
{
    public FakeWebAuthentication $fakeAuth;
    public string $SubscriptionKey = '';
    public bool $renderFeedCalled = false;

    public function __construct()
    {
        // Deliberately skip parent::__construct() — avoids DB / DI dependencies.
        // Only the property defaults on SubscriptionPage ($notFound = false, etc.)
        // are needed for these tests.
        $this->fakeAuth = new FakeWebAuthentication();
    }

    public function GetSubscriptionKey()
    {
        return $this->SubscriptionKey;
    }

    protected function createWebAuthentication(): IWebAuthentication
    {
        return $this->fakeAuth;
    }

    /** Expose the protected method under test. */
    public function callTryBasicAuth(): void
    {
        $this->tryBasicAuth();
    }

    public function isNotFound(): bool
    {
        return $this->notFound;
    }

    public function setPresenter(CalendarSubscriptionPresenter $presenter): void
    {
        $this->presenter = $presenter;
    }

    protected function renderFeed(): void
    {
        $this->renderFeedCalled = true;
    }
}
