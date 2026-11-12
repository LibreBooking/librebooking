<?php

declare(strict_types=1);

class AnonymousResourceExcludedRuleTest extends TestBase
{
    private IReservationValidationRule&\PHPUnit\Framework\MockObject\MockObject $baseRule;

    private TestReservationSeries $reservationSeries;

    private FakeBookableResource $resource;

    public function setUp(): void
    {
        parent::setup();

        $this->baseRule = $this->createMock(IReservationValidationRule::class);

        $this->resource = new FakeBookableResource(1);
        $this->reservationSeries = new TestReservationSeries();
        $this->reservationSeries->WithResource($this->resource);
    }

    public function testLoggedInUserIsCheckedByBaseRule(): void
    {
        $this->baseRule->expects($this->once())
            ->method('Validate')
            ->with($this->identicalTo($this->reservationSeries), $this->isNull())
            ->willReturn(new ReservationRuleResult(false, 'not yours'));

        $rule = new AnonymousResourceExcludedRule($this->baseRule, $this->fakeUser);
        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertFalse($result->IsValid());
        $this->assertSame('not yours', $result->ErrorMessage());
    }

    public function testLoggedInUserPassesWhenBaseRulePasses(): void
    {
        $this->baseRule->expects($this->once())
            ->method('Validate')
            ->willReturn(new ReservationRuleResult(true));

        $rule = new AnonymousResourceExcludedRule($this->baseRule, $this->fakeUser);

        $this->assertTrue($rule->Validate($this->reservationSeries, null)->IsValid());
    }

    public function testCheckinRulesAllowOwnerToCheckIn(): void
    {
        $service = (new PreReservationFactory())->CreatePreCheckinService(new UserSession(99));

        $result = $service->Validate($this->CheckinReadySeriesOwnedBy(99));

        $this->assertTrue($result->CanBeSaved());
    }

    public function testCheckinRulesRejectNonAdminCheckingInAnotherUsersReservation(): void
    {
        $service = (new PreReservationFactory())->CreatePreCheckinService(new UserSession(5));

        $result = $service->Validate($this->CheckinReadySeriesOwnedBy(99));

        $this->assertFalse($result->CanBeSaved());
        $this->assertSame([Resources::GetInstance()->GetString('NoReservationAccess')], $result->GetErrors());
    }

    public function testAnonymousUserPassesForDisplayEnabledResource(): void
    {
        $this->resource->EnableDisplay();
        $this->baseRule->expects($this->never())->method('Validate');

        $rule = new AnonymousResourceExcludedRule($this->baseRule, new NullUserSession());

        $this->assertTrue($rule->Validate($this->reservationSeries, null)->IsValid());
    }

    public function testAnonymousUserFailsWhenNoResourceIsDisplayEnabled(): void
    {
        $this->baseRule->expects($this->never())->method('Validate');

        $rule = new AnonymousResourceExcludedRule($this->baseRule, new NullUserSession());
        $result = $rule->Validate($this->reservationSeries, null);

        $this->assertFalse($result->IsValid());
        $this->assertSame(Resources::GetInstance()->GetString('NoResourcePermission'), $result->ErrorMessage());
    }

    private function CheckinReadySeriesOwnedBy(int $ownerId): ExistingReservationSeries
    {
        $resource = new FakeBookableResource(1);
        $resource->SetCheckin(true);

        $series = new ExistingReservationSeries();
        $series->WithOwner($ownerId);
        $series->WithPrimaryResource($resource);
        $series->WithCurrentInstance(new TestReservation(null, new DateRange(Date::Now(), Date::Now()->AddHours(1))));

        return $series;
    }
}
