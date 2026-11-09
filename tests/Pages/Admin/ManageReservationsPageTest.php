<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageReservationsPage.php');

class ManageReservationsPageTest extends TestBase
{
    public function testDeletedReservationIdsIsEmptyWhenNothingIsSubmitted(): void
    {
        $this->assertSame([], $this->CreatePage()->GetDeletedReservationIds());
    }

    public function testDeletedReservationIdsWrapsASingleId(): void
    {
        $this->fakeServer->SetForm(FormKeys::RESERVATION_ID, '5');

        $this->assertSame([5], $this->CreatePage()->GetDeletedReservationIds());
    }

    public function testFilterIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::SCHEDULE_ID, '3');
        $this->fakeServer->SetQuerystring(QueryStringKeys::RESERVATION_RESOURCE_STATUS_ID, '0');
        $this->fakeServer->SetQuerystring(QueryStringKeys::USER_ID, '');
        $page = $this->CreatePage();

        $this->assertSame(3, $page->GetScheduleId());
        $this->assertSame(0, $page->GetResourceStatusFilterId());
        $this->assertNull($page->GetUserId());
    }

    private function CreatePage(): ManageReservationsPage
    {
        return new class () extends ManageReservationsPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
