<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageGroupsPage.php');

class ManageGroupsPageTest extends TestBase
{
    public function testGroupIdPrefersFormOverQuerystring(): void
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::GROUP_ID, '7');
        $this->assertSame(7, $this->CreatePage()->GetGroupId());

        $this->fakeServer->SetForm(FormKeys::GROUP_ID, '42');
        $this->assertSame(42, $this->CreatePage()->GetGroupId());
    }

    public function testGroupIdIsNullWhenNotSubmitted(): void
    {
        $this->assertNull($this->CreatePage()->GetGroupId());
    }

    public function testUserAndAdminGroupIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::USER_ID, '12');
        $this->fakeServer->SetForm(FormKeys::GROUP_ADMIN, '');
        $page = $this->CreatePage();

        $this->assertSame(12, $page->GetUserId());
        $this->assertNull($page->GetAdminGroupId());
    }

    private function CreatePage(): ManageGroupsPage
    {
        return new class () extends ManageGroupsPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
