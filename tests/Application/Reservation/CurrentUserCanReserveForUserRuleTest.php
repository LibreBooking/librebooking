<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class CurrentUserCanReserveForUserRuleTest extends TestBase
{
    private TestReservationSeries $reservationSeries;

    public function setUp(): void
    {
        parent::setup();

        $this->fakeUser->IsAdmin = false;
        $this->fakeUser->IsGroupAdmin = false;
        $this->fakeUser->IsResourceAdmin = false;
        $this->fakeUser->IsScheduleAdmin = false;
        $this->reservationSeries = new TestReservationSeries();
    }

    public function testAllowsCurrentUserToReserveForSelf()
    {
        $this->reservationSeries->WithOwnerId($this->fakeUser->UserId);

        $authorizationService = $this->createMock('IAuthorizationService');
        $authorizationService->expects($this->never())
                             ->method('CanReserveFor');

        $rule = new CurrentUserCanReserveForUserRule($this->fakeUser, $authorizationService);

        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRejectsReservationForAnotherUserWithoutAuthorization()
    {
        $reserveForId = 1234;
        $this->reservationSeries->WithOwnerId($reserveForId);

        $authorizationService = $this->createMock('IAuthorizationService');
        $authorizationService->expects($this->once())
                             ->method('CanReserveFor')
                             ->with($this->fakeUser, $reserveForId)
                             ->willReturn(false);

        $rule = new CurrentUserCanReserveForUserRule($this->fakeUser, $authorizationService);

        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertFalse($result->IsValid());
        $this->assertEquals(Resources::GetInstance()->GetString('InvalidReservationData'), $result->ErrorMessage());
    }

    public function testAllowsReservationForAnotherUserWithAuthorization()
    {
        $reserveForId = 1234;
        $this->reservationSeries->WithOwnerId($reserveForId);

        $authorizationService = $this->createMock('IAuthorizationService');
        $authorizationService->expects($this->once())
                             ->method('CanReserveFor')
                             ->with($this->fakeUser, $reserveForId)
                             ->willReturn(true);

        $rule = new CurrentUserCanReserveForUserRule($this->fakeUser, $authorizationService);

        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertTrue($result->IsValid());
    }

    public function testAllowsApplicationAdminToReserveForAnotherUser()
    {
        $reserveForId = 1234;
        $adminUser = new FakeUserSession(true, null, 999);
        $adminUser->IsResourceAdmin = false;
        $this->reservationSeries->WithOwnerId($reserveForId);

        $userRepository = $this->createMock('IUserRepository');
        $userRepository->expects($this->never())
                       ->method('LoadById');

        $rule = new CurrentUserCanReserveForUserRule($adminUser, new AuthorizationService($userRepository));

        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertTrue($result->IsValid());
    }

    public function testRejectsResourceAdminWithoutReserveForAuthorization()
    {
        $reserveForId = 1234;
        $this->fakeUser->IsResourceAdmin = true;
        $this->reservationSeries->WithOwnerId($reserveForId);

        $authorizationService = $this->createMock('IAuthorizationService');
        $authorizationService->expects($this->once())
                             ->method('CanReserveFor')
                             ->with($this->fakeUser, $reserveForId)
                             ->willReturn(false);

        $rule = new CurrentUserCanReserveForUserRule($this->fakeUser, $authorizationService);

        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertFalse($result->IsValid());
    }
}
