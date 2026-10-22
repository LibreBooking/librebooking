<?php

declare(strict_types=1);

class ReservationDetailsFilterTest extends TestBase
{
    public function setUp(): void
    {
        parent::setup();
        $this->fakeServer->SetUserSession(new NullUserSession());
    }

    public function testPublicUserCanViewDetailsWhenBothSettingsAreEnabled()
    {
        $this->SetSettings(true, true);

        $this->assertTrue(ReservationDetailsFilter::CanPublicViewDetails());
    }

    public function testPublicUserCannotViewDetailsWithoutViewReservations()
    {
        $this->SetSettings(false, true);

        $this->assertFalse(ReservationDetailsFilter::CanPublicViewDetails());
    }

    public function testPublicUserCannotViewDetailsWithoutPublicDetailsSetting()
    {
        $this->SetSettings(true, false);

        $this->assertFalse(ReservationDetailsFilter::CanPublicViewDetails());
    }

    public function testLoggedInUserIsNotTreatedAsPublic()
    {
        $this->SetSettings(true, true);
        $this->fakeServer->SetUserSession(new FakeUserSession());

        $this->assertFalse(ReservationDetailsFilter::CanPublicViewDetails());
    }

    private function SetSettings(bool $viewReservations, bool $publicDetails)
    {
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_VIEW_RESERVATIONS, $viewReservations);
        $this->fakeConfig->SetKey(ConfigKeys::PRIVACY_VIEW_PUBLIC_RESERVATION_DETAILS, $publicDetails);
    }
}
