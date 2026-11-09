<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageSchedulesPage.php');

class ManageSchedulesPageTest extends TestBase
{
    public function testScheduleIdFallsBackFromQuerystringToForm(): void
    {
        $this->fakeServer->SetForm(FormKeys::PK, '42');
        $this->assertSame(42, $this->CreatePage()->GetScheduleId());

        $this->fakeServer->SetQuerystring(QueryStringKeys::SCHEDULE_ID, '7');
        $this->assertSame(7, $this->CreatePage()->GetScheduleId());
    }

    public function testPeakTimeFieldsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::PEAK_BEGIN_MONTH, '3');
        $this->fakeServer->SetForm(FormKeys::PEAK_BEGIN_DAY, '15');
        $page = $this->CreatePage();

        $this->assertSame(3, $page->GetPeakBeginMonth());
        $this->assertSame(15, $page->GetPeakBeginDay());
        $this->assertNull($page->GetPeakEndDay());
    }

    private function CreatePage(): ManageSchedulesPage
    {
        return new class () extends ManageSchedulesPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
