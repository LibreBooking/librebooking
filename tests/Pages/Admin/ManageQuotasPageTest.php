<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageQuotasPage.php');

class ManageQuotasPageTest extends TestBase
{
    public function testLimitKeepsDecimals(): void
    {
        $this->fakeServer->SetForm(FormKeys::LIMIT, '1.5');

        $this->assertSame(1.5, $this->CreatePage()->GetLimit());
    }

    public function testEmptyLimitIsNull(): void
    {
        $this->fakeServer->SetForm(FormKeys::LIMIT, '');

        $this->assertNull($this->CreatePage()->GetLimit());
    }

    public function testIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ID, '987');
        $this->fakeServer->SetForm(FormKeys::GROUP, '8287');
        $this->fakeServer->SetForm(FormKeys::SCHEDULE_ID, '400');
        $this->fakeServer->SetQuerystring(QueryStringKeys::QUOTA_ID, '12');
        $page = $this->CreatePage();

        $this->assertSame(987, $page->GetResourceId());
        $this->assertSame(8287, $page->GetGroupId());
        $this->assertSame(400, $page->GetScheduleId());
        $this->assertSame(12, $page->GetQuotaId());
    }

    public function testAllOptionsAreNull(): void
    {
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ID, '');
        $this->fakeServer->SetForm(FormKeys::GROUP, '');
        $this->fakeServer->SetForm(FormKeys::SCHEDULE_ID, '');
        $page = $this->CreatePage();

        $this->assertNull($page->GetResourceId());
        $this->assertNull($page->GetGroupId());
        $this->assertNull($page->GetScheduleId());
    }

    private function CreatePage(): ManageQuotasPage
    {
        return new class () extends ManageQuotasPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
