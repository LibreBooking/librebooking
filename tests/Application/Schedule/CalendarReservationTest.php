<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Pages/Pages.php');

class CalendarReservationTest extends TestBase
{
    public function testExistingReservationUrlUsesDefaultReservationPage(): void
    {
        $referenceNumber = 'abc123';
        $res = new TestReservationItemView(
            id: 1,
            startDate: Date::Now(),
            endDate: Date::Now()->AddHours(1),
            resourceId: 1,
            referenceNumber: $referenceNumber,
        );

        $resource = new FakeBookableResource(1, 'Room A');

        $calendarReservations = CalendarReservation::FromScheduleReservationList(
            reservations: [$res],
            blackouts: [],
            availablePeriods: [],
            resources: [$resource],
            userSession: $this->fakeUser,
        );

        $event = $calendarReservations[0]->AsFullCalendarEvent();

        $this->assertStringStartsWith('reservation.php?rn=' . $referenceNumber, $event['url']);
        $this->assertStringNotContainsString('view-reservation.php', $event['url']);
    }

    public function testExistingReservationUrlUsesViewReservationPageWhenSpecified(): void
    {
        $referenceNumber = 'xyz789';
        $res = new TestReservationItemView(
            id: 1,
            startDate: Date::Now(),
            endDate: Date::Now()->AddHours(1),
            resourceId: 1,
            referenceNumber: $referenceNumber,
        );

        $resource = new FakeBookableResource(1, 'Room A');

        $calendarReservations = CalendarReservation::FromScheduleReservationList(
            reservations: [$res],
            blackouts: [],
            availablePeriods: [],
            resources: [$resource],
            userSession: $this->fakeUser,
            reservationPage: Pages::VIEW_RESERVATION,
        );

        $event = $calendarReservations[0]->AsFullCalendarEvent();

        $this->assertStringStartsWith('view-reservation.php?rn=' . $referenceNumber, $event['url']);
    }
}
