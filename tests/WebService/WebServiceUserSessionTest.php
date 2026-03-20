<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Values/WebService/WebServiceUserSession.php');

class WebServiceUserSessionTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
        $this->fakeConfig->SetKey(ConfigKeys::INACTIVITY_TIMEOUT, ConfigKeys::INACTIVITY_TIMEOUT['default']);
    }

    public function testExtendsSession()
    {
        $now = Date::Create(2026, 3, 20, 12, 0, 0, 'UTC');
        Date::_SetNow($now);

        $session = new WebServiceUserSession(123);
        $session->SessionExpiration = '2012-05-01';
        $session->ExtendSession();

        $expected = $now->AddMinutes(ConfigKeys::INACTIVITY_TIMEOUT['default'])->ToUtc()->ToIso();

        $this->assertEquals($expected, $session->SessionExpiration);
    }

    public function testIsExpired()
    {
        $session = new WebServiceUserSession(123);
        $session->SessionExpiration = Date::Now()->AddMinutes(-1)->ToUtc()->ToIso();

        $this->assertTrue($session->IsExpired());
    }

    public function testIsNotExpired()
    {
        $session = new WebServiceUserSession(123);
        $session->SessionExpiration = Date::Now()->AddMinutes(1)->ToUtc()->ToIso();

        $this->assertFalse($session->IsExpired());
    }

    public function testCreateUsesConfiguredTimeout()
    {
        $timeoutMinutes = 10080; // 7 days
        $this->fakeConfig->SetKey(ConfigKeys::INACTIVITY_TIMEOUT, $timeoutMinutes);
        $now = Date::Create(2026, 3, 20, 12, 0, 0, 'UTC');
        Date::_SetNow($now);

        $expiration = WebServiceExpiration::Create();
        $expected = $now->AddMinutes($timeoutMinutes)->ToUtc()->ToIso();

        $this->assertEquals($expected, $expiration);
    }

}
