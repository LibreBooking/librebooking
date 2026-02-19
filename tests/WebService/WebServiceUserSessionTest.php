<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Values/WebService/WebServiceUserSession.php');

class WebServiceUserSessionTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function testExtendsSession()
    {
        $session = new WebServiceUserSession(123);
        $session->SessionExpiration = '2012-05-01';
        $session->ExtendSession();

        $this->assertEquals(WebServiceExpiration::Create(), $session->SessionExpiration);
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
}
