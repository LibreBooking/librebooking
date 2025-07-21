<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');

class AccessoryAvailabilityRuleTest extends TestBase
{
    /**
     * @var IReservationViewRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $reservationRepository;

    /**
     * @var IAccessoryRepository|PHPUnit\Framework\MockObject\MockObject
     */
    public $accessoryRepository;

    /**
     * @var AccessoryAvailabilityRule
     */
    public $rule;

    public function setUp(): void
    {
        parent::setup();

        $this->reservationRepository = $this->createMock('IReservationViewRepository');
        $this->accessoryRepository = $this->createMock('IAccessoryRepository');

        $this->rule = new AccessoryAvailabilityRule($this->reservationRepository, $this->accessoryRepository, 'UTC');
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testRuleIsValidIfTotalQuantityReservedIsLessThanQuantityAvailable()
    {
        $accessory1 = new ReservationAccessory(1, 5);
        $accessory2 = new ReservationAccessory(2, 5);

        $quantityAvailable = 8;

        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-05', 'UTC');

        $startDate1 = Date::Parse('2010-04-06', 'UTC');
        $endDate1 = Date::Parse('2010-04-07', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithAccessory($accessory1);
        $reservation->WithAccessory($accessory2);

        $dr1 = new DateRange($startDate, $endDate);
        $dr2 = new DateRange($startDate1, $endDate1);
        $reservation->WithDuration($dr1);
        $reservation->WithInstanceOn($dr2);

        $accessoryReservation = new AccessoryReservation(2, $startDate, $endDate, $accessory1->AccessoryId, 3);
        $accessoryReservationForOtherResource = new AccessoryReservation(2, $startDate, $endDate, $accessory1->AccessoryId, 3);

        $this->accessoryRepository->expects($this->exactly(2))
            ->method('LoadById')
            ->willReturnMap([
                [$accessory1->AccessoryId, new Accessory($accessory1->AccessoryId, 'name1', $quantityAvailable)],
                [$accessory2->AccessoryId, new Accessory($accessory2->AccessoryId, 'name2', $quantityAvailable)]
            ]);

        $this->reservationRepository->expects($this->exactly(2))
            ->method('GetAccessoriesWithin')
            ->willReturnMap([
                [$dr1, [$accessoryReservation, $accessoryReservationForOtherResource]],
                [$dr2, []]
            ]);

        $result = $this->rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testGetsConflictingReservationTimes()
    {
        $accessory1 = new ReservationAccessory(1, 5);
        $quantityAvailable = 8;

        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-05', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithAccessory($accessory1);
        $dr1 = new DateRange($startDate, $endDate);
        $reservation->WithDuration($dr1);

        $lowerQuantity1 = new AccessoryReservation(2, $startDate, $endDate, $accessory1->AccessoryId, 2);
        $lowerQuantity2 = new AccessoryReservation(3, $startDate, $endDate, $accessory1->AccessoryId, 2);
        $notOnReservation = new AccessoryReservation(4, $startDate, $endDate, 100, 1);

        $this->accessoryRepository->expects($this->once())
            ->method('LoadById')
            ->with($accessory1->AccessoryId)
            ->willReturn(new Accessory($accessory1->AccessoryId, 'name1', $quantityAvailable));

        $this->reservationRepository->expects($this->once())
            ->method('GetAccessoriesWithin')
            ->with($this->equalTo($dr1))
            ->willReturn([$lowerQuantity1, $lowerQuantity2, $notOnReservation]);

        $result = $this->rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
        $this->assertFalse(is_null($result->ErrorMessage()));
    }

    public function testNoConflictsButTooHigh()
    {
        $accessory1 = new ReservationAccessory(1, 5);
        $quantityAvailable = 4;

        $reservation = new TestReservationSeries();
        $dr1 = new TestDateRange();
        $reservation->WithDuration($dr1);
        $reservation->WithAccessory($accessory1);

        $this->accessoryRepository->expects($this->once())
            ->method('LoadById')
            ->with($accessory1->AccessoryId)
            ->willReturn(new Accessory($accessory1->AccessoryId, 'name1', $quantityAvailable));

        $this->reservationRepository->expects($this->once())
            ->method('GetAccessoriesWithin')
            ->with($this->anything())
            ->willReturn([]);

        $result = $this->rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
        $this->assertFalse(is_null($result->ErrorMessage()));
    }

    public function testUnlimitedQuantity()
    {
        $accessory1 = new ReservationAccessory(1, 5);
        $quantityAvailable = null;

        $startDate = Date::Parse('2010-04-04', 'UTC');
        $endDate = Date::Parse('2010-04-05', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithAccessory($accessory1);
        $dr1 = new DateRange($startDate, $endDate);
        $reservation->WithDuration($dr1);

        $this->accessoryRepository->expects($this->once())
            ->method('LoadById')
            ->with($accessory1->AccessoryId)
            ->willReturn(new Accessory($accessory1->AccessoryId, 'name1', $quantityAvailable));

        $result = $this->rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }

    public function testExistingLongRunningReservation()
    {
        $accessory1 = new ReservationAccessory(1, 5);
        $currentReferenceNumber = 1;

        $quantityAvailable = 6;
        $startDate = Date::Parse('2010-04-04 00:00', 'UTC');
        $endDate = Date::Parse('2010-04-06 00:00', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithAccessory($accessory1);

        $dr1 = new DateRange($startDate, $endDate);
        $reservation->WithCurrentInstance(new TestReservation($currentReferenceNumber, $dr1));

        $accessoryReservation = new AccessoryReservation($currentReferenceNumber, $startDate, $endDate, $accessory1->AccessoryId, 5);
        $a1 = new AccessoryReservation(2, Date::Parse('2010-04-04 10:00', 'UTC'), Date::Parse('2010-04-04 12:00', 'UTC'), $accessory1->AccessoryId, 1);
        $a2 = new AccessoryReservation(3, Date::Parse('2010-04-04 13:00', 'UTC'), Date::Parse('2010-04-04 15:00', 'UTC'), $accessory1->AccessoryId, 1);

        $this->accessoryRepository->expects($this->once())
            ->method('LoadById')
            ->with($accessory1->AccessoryId)
            ->willReturn(new Accessory($accessory1->AccessoryId, 'name1', $quantityAvailable));

        $this->reservationRepository->expects($this->atLeast(1))
            ->method('GetAccessoriesWithin')
            ->willReturn([$accessoryReservation, $a1, $a2]);

        $result = $this->rule->Validate($reservation, null);

        $this->assertFalse($result->IsValid());
    }

    public function testMultipleReservationsButNoneOverlapping()
    {
        $accessory = new ReservationAccessory(1, 4);
        $quantityAvailable = 5;

        $startDate = Date::Parse('2010-04-04 05:30', 'UTC');
        $endDate = Date::Parse('2010-04-10 16:30', 'UTC');

        $reservation = new TestReservationSeries();
        $reservation->WithAccessory($accessory);
        $dr1 = new DateRange($startDate, $endDate);
        $reservation->WithDuration($dr1);

        $ar1 = new AccessoryReservation(2, Date::Parse('2010-04-02', 'UTC'), Date::Parse('2010-04-05', 'UTC'), $accessory->AccessoryId, 1);
        $ar2 = new AccessoryReservation(3, Date::Parse('2010-04-05', 'UTC'), Date::Parse('2010-04-06', 'UTC'), $accessory->AccessoryId, 1);
        $ar3 = new AccessoryReservation(4, Date::Parse('2010-04-06', 'UTC'), Date::Parse('2010-04-07', 'UTC'), $accessory->AccessoryId, 1);
        $ar4 = new AccessoryReservation(5, Date::Parse('2010-04-08', 'UTC'), Date::Parse('2010-04-11', 'UTC'), $accessory->AccessoryId, 1);

        $this->accessoryRepository->expects($this->any())
            ->method('LoadById')
            ->with($accessory->AccessoryId)
            ->willReturn(new Accessory($accessory->AccessoryId, 'name1', $quantityAvailable));

        $this->reservationRepository->expects($this->any())
            ->method('GetAccessoriesWithin')
            ->willReturn([$ar1, $ar2, $ar3, $ar4]);

        $result = $this->rule->Validate($reservation, null);

        $this->assertTrue($result->IsValid());
    }
}
