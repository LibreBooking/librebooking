<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class EmptyReservationListingTest extends TestBase
{
    public function testOnDateForResourceReturnsEmptyArray(): void
    {
        $listing = new EmptyReservationListing();

        $this->assertSame([], $listing->OnDateForResource(Date::Now(), 1));
    }

    public function testDailyLayoutSummaryWithEmptyListingHasNoReservations(): void
    {
        $layout = new DailyLayout(new EmptyReservationListing(), new FakeScheduleLayout());

        $summary = $layout->GetSummary(Date::Now(), 1);

        $this->assertSame(0, $summary->NumberOfReservations());
    }
}
