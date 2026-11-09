<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageBlackoutsPage.php');

class ManageBlackoutsPageTest extends TestBase
{
    public function testDeletedBlackoutIdsIsEmptyWhenNothingIsSubmitted(): void
    {
        $this->assertSame([], $this->CreatePage()->GetDeletedBlackoutIds());
    }

    public function testDeletedBlackoutIdsWrapsASingleId(): void
    {
        $this->fakeServer->SetForm(FormKeys::BLACKOUT_INSTANCE_ID, '5');

        $this->assertSame([5], $this->CreatePage()->GetDeletedBlackoutIds());
    }

    public function testDeletedBlackoutIdsParsesSubmittedList(): void
    {
        $this->fakeServer->SetForm(FormKeys::BLACKOUT_INSTANCE_ID, ['5', '6']);

        $this->assertSame([5, 6], $this->CreatePage()->GetDeletedBlackoutIds());
    }

    public function testBlackoutResourceIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ID, ['3', '7']);

        $this->assertSame([3, 7], $this->CreatePage()->GetBlackoutResourceIds());
    }

    public function testBlackoutResourceIdsIsEmptyWhenNothingIsSelected(): void
    {
        $this->assertSame([], $this->CreatePage()->GetBlackoutResourceIds());
    }

    public function testBlackoutIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::BLACKOUT_ID, '12');
        $this->fakeServer->SetForm(FormKeys::BLACKOUT_INSTANCE_ID, '34');
        $page = $this->CreatePage();

        $this->assertSame(12, $page->GetBlackoutId());
        $this->assertSame(34, $page->GetUpdateBlackoutId());
        $this->assertNull($page->GetScheduleId());
    }

    private function CreatePage(): ManageBlackoutsPage
    {
        return new class () extends ManageBlackoutsPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
