<?php

declare(strict_types=1);

class ReservationCanBeCheckedOutRuleTest extends TestBase
{
    /**
     *
     * @var UserSession
     */
    private $userSession;

    public function setUp(): void
    {
        parent::setup();
        $this->userSession = new UserSession(123);
    }

    public function testCanBeCheckedOutIfCheckedInAndPastStartDate()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true);
        $resource2 = new FakeBookableResource(2);
        $resource2->SetCheckin(false);

        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1)));

        Date::_SetNow(Date::Now()->AddMinutes(30));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);
        $series->AddResource($resource2);
        $series->Checkin($this->fakeUser);

        $rule = new ReservationCanBeCheckedOutRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertTrue($result->IsValid());
    }

    public function testCannotBeCheckedOutIfNotCheckedIn()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true);

        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1)));

        Date::_SetNow(Date::Now()->AddMinutes(30));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);

        $rule = new ReservationCanBeCheckedOutRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }

    public function testCannotBeCheckedOutIfNotStarted()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true);

        $reservation = new TestReservation(null, new DateRange(Date::Now()->AddMinutes(30), Date::Now()->AddHours(1)));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);
        $series->Checkin($this->fakeUser);

        $rule = new ReservationCanBeCheckedOutRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }

    public function testCannotBeCheckedOutCheckoutNotEnabled()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(false);

        $reservation = new TestReservation(null, new DateRange(Date::Now()->AddMinutes(30), Date::Now()->AddHours(1)));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);
        $series->Checkin($this->fakeUser);

        $rule = new ReservationCanBeCheckedOutRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }
}
