<?php

class ReservationCanBeCheckedInRuleTest extends TestBase
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

    public function testCanBeCheckedInIfWithinWindowWithAutoRelease()
    {
        $earliestRelease = 10;
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(false);
        $resource2 = new FakeBookableResource(2);
        $resource2->SetCheckin(true, $earliestRelease);

        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1)));

        Date::_SetNow(Date::Now()->AddMinutes($earliestRelease - 1));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);
        $series->AddResource($resource2);

        $rule = new ReservationCanBeCheckedInRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertTrue($result->IsValid());
    }

    public function testCanBeCheckedInIfWithinWindowWithoutAutoRelease()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true);

        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1)));

        Date::_SetNow(Date::Now()->AddMinutes(59));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);

        $rule = new ReservationCanBeCheckedInRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertTrue($result->IsValid());
    }

    public function testCannotBeCheckedInIfPastEnd()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true);

        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1)));

        Date::_SetNow(Date::Now()->AddMinutes(60));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);

        $rule = new ReservationCanBeCheckedInRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }

    public function testCannotBeCheckedInIfNoResourceHasCheckinEnabled()
    {
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(false);

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance(new TestReservation());

        $rule = new ReservationCanBeCheckedInRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }

    public function testCannotBeCheckedInIfPastReleaseTime()
    {
        $earliestRelease = 10;
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true, 20);
        $resource2 = new FakeBookableResource(2);
        $resource2->SetCheckin(true, $earliestRelease);

        $reservation = new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1)));

        Date::_SetNow(Date::Now()->AddMinutes($earliestRelease + 1));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);
        $series->AddResource($resource2);

        $rule = new ReservationCanBeCheckedInRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }

    public function testCannotBeCheckedInIfMoreThanFiveMinutesUntilStart()
    {
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_CHECKIN_MINUTES_PRIOR, 5);
        $resource1 = new FakeBookableResource(1);
        $resource1->SetCheckin(true);
        $reservation = new TestReservation(null, new DateRange(Date::Now()->AddMinutes(6), Date::Now()->AddHours(1)));

        $series = new ExistingReservationSeries();
        $series->WithPrimaryResource($resource1);
        $series->WithCurrentInstance($reservation);

        $rule = new ReservationCanBeCheckedInRule($this->userSession);
        $result = $rule->Validate($series, null);
        $this->assertFalse($result->IsValid());
    }
}
