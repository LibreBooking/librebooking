<?php

require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');

class PrivacyFilterTest extends TestBase
{
    /**
     * @var PrivacyFilter
     */
    private $privacyFilter;

    /**
     * @var IReservationAuthorization|PHPUnit\Framework\MockObject\MockObject
     */
    private $reservationAuthorization;

    public function setUp(): void
    {
        parent::setup();

        $this->reservationAuthorization = $this->createMock('IReservationAuthorization');

        $this->privacyFilter = new PrivacyFilter($this->reservationAuthorization);
    }

    public function testCanViewUserDetailsIfConfigured()
    {
        $this->hideUserDetails(false);

        $user = new UserSession(1);

        $canView = $this->privacyFilter->CanViewUser($user, null);

        $this->assertTrue($canView);
    }

    public function testHidesDetailsIfUserIsNotAnAdminAndNoReservationProvided()
    {
        $this->hideUserDetails(true);

        $user = new UserSession(1);

        $canView = $this->privacyFilter->CanViewUser($user, null);

        $this->assertFalse($canView);
    }

    public function testCanViewIfUserIsAnAdminAndNoReservationProvided()
    {
        $this->hideUserDetails(true);

        $user = new UserSession(1);
        $user->IsAdmin = true;

        $canView = $this->privacyFilter->CanViewUser($user, null);

        $this->assertTrue($canView);
    }

    public function testCanViewIfUserIsOwner()
    {
        $this->hideUserDetails(true);

        $userId = 1;
        $user = new UserSession($userId);

        $canView = $this->privacyFilter->CanViewUser($user, null, $userId);

        $this->assertTrue($canView);
    }

    public function testChecksReservationAuthorizationAndCachesResultIfUserNotAnAdminAndReservationProvided()
    {
        $this->hideUserDetails(true);

        $user = new UserSession(1);
        $reservation = new ReservationView();

        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanViewDetails')
                                       ->with($this->equalTo($reservation), $this->equalTo($user))
                                       ->willReturn(true);

        $canView = $this->privacyFilter->CanViewUser($user, $reservation);
        $canView2 = $this->privacyFilter->CanViewUser($user, $reservation);

        $this->assertTrue($canView);
        $this->assertTrue($canView2);
    }

    public function testCanViewReservationDetailsIfConfigured()
    {
        $this->hideReservationDetails(false);

        $user = new UserSession(1);

        $canView = $this->privacyFilter->CanViewDetails($user, null);

        $this->assertTrue($canView);
    }

    public function testHidesReservationDetailsIfUserIsNotAnAdminAndNoReservationProvided()
    {
        $this->hideReservationDetails(true);

        $user = new UserSession(1);

        $canView = $this->privacyFilter->CanViewDetails($user, null);

        $this->assertFalse($canView);
    }

    public function testCanViewReservationIfUserIsAnAdminAndNoReservationProvided()
    {
        $this->hideReservationDetails(true);

        $user = new UserSession(1);
        $user->IsAdmin = true;

        $canView = $this->privacyFilter->CanViewDetails($user, null);

        $this->assertTrue($canView);
    }

    public function testCanViewReservationIfUserIsOwner()
    {
        $this->hideReservationDetails(true);

        $userId = 1;
        $user = new UserSession($userId);

        $canView = $this->privacyFilter->CanViewDetails($user, null, $userId);

        $this->assertTrue($canView);
    }

    public function testReservationDetailsChecksReservationAuthorizationAndCachesResultIfUserNotAnAdminAndReservationProvided()
    {
        $this->hideReservationDetails(true);

        $user = new UserSession(1);
        $reservation = new ReservationView();

        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanViewDetails')
                                       ->with($this->equalTo($reservation), $this->equalTo($user))
                                       ->willReturn(true);

        $canView = $this->privacyFilter->CanViewDetails($user, $reservation);
        $canView2 = $this->privacyFilter->CanViewDetails($user, $reservation);

        $this->assertTrue($canView);
        $this->assertTrue($canView2);
    }

    public function testHidesPast()
    {
        $this->hideReservationDetails('past');

        $user = new UserSession(1);
        $res = new ReservationView();
        $res->StartDate = Date::Now()->AddDays(-2);
        $res->EndDate = Date::Now()->AddDays(-1);

        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanViewDetails')
                                       ->with($this->equalTo($res), $this->equalTo($user))
                                       ->willReturn(false);

        $canView = $this->privacyFilter->CanViewDetails($user, $res);

        $this->assertFalse($canView);
    }

    public function testHidesFuture()
    {
        $this->hideReservationDetails('future');

        $user = new UserSession(1);
        $res = new ReservationView();
        $res->StartDate = Date::Now()->AddDays(1);
        $res->EndDate = Date::Now()->AddDays(2);

        $this->reservationAuthorization->expects($this->once())
                                       ->method('CanViewDetails')
                                       ->with($this->equalTo($res), $this->equalTo($user))
                                       ->willReturn(false);

        $canView = $this->privacyFilter->CanViewDetails($user, $res);

        $this->assertFalse($canView);
    }

    private function hideUserDetails($hide)
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_USER_DETAILS, $hide);
    }

    private function hideReservationDetails($hide)
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_HIDE_RESERVATION_DETAILS, $hide);
    }
}
