<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class PermissionValidationRuleTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testChecksIfUserHasPermission()
    {
        $userId = 98;
        $user = new FakeUserSession();
        $user->UserId = $userId;

        $resourceId = 100;
        $resourceId1 = 1;
        $resourceId2 = 2;

        $rr1 = new ReservationResource($resourceId);
        $rr2 = new ReservationResource($resourceId1);

        $resource = new FakeBookableResource($resourceId, null);
        $resource1 = new FakeBookableResource($resourceId1, null);
        $resource2 = new FakeBookableResource($resourceId2, null);

        $reservation = new TestReservationSeries();
        $reservation->WithOwnerId($userId);
        $reservation->WithResource($resource);
        $reservation->AddResource($resource1);
        $reservation->AddResource($resource2);
        $reservation->WithBookedBy($user);

        $service = new FakePermissionService([true, false]);
        $service->_CanBookResource = false;
        $factory = $this->createMock('IPermissionServiceFactory');

        $factory->expects($this->once())
            ->method('GetPermissionService')
            ->willReturn($service);

        $rule = new PermissionValidationRule($factory);
        $result = $rule->Validate($reservation, null);

        $this->assertEquals(false, $result->IsValid());
    }
}
