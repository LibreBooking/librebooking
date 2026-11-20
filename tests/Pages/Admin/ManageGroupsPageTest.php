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

    public function testAllowedResourceIdsIsNullWhenNotSubmitted(): void
    {
        $this->assertNull($this->CreatePage()->GetAllowedResourceIds());
    }

    /**
     * @return array<string, array{mixed, string[]}>
     */
    public static function allowedResourceIdsProvider(): array
    {
        return [
            'list' => [['1_0', '2_1'], ['1_0', '2_1']],
            'single value' => ['1_0', ['1_0']],
            'blank' => ['', []],
            'empty list' => [[], []],
            'nested list is dropped' => [['1_0', ['2_1']], ['1_0']],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('allowedResourceIdsProvider')]
    public function testAllowedResourceIdsAreStrings(mixed $submitted, array $expected): void
    {
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ID, $submitted);

        $this->assertSame($expected, $this->CreatePage()->GetAllowedResourceIds());
    }

    /**
     * @return array<string, array{mixed, int[]}>
     */
    public static function idListProvider(): array
    {
        return [
            'list' => [['3', '5'], [3, 5]],
            'single value' => ['3', [3]],
            'blank' => ['', []],
            'values that are not integers are dropped' => [['3', 'abc', ['5'], '7'], [3, 7]],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('idListProvider')]
    public function testIdListsAreParsedAsIntegers(mixed $submitted, array $expected): void
    {
        $this->fakeServer->SetForm(FormKeys::ROLE_ID, $submitted);
        $this->fakeServer->SetForm(FormKeys::GROUP_ID, $submitted);
        $this->fakeServer->SetForm(FormKeys::RESOURCE_ID, $submitted);
        $this->fakeServer->SetForm(FormKeys::SCHEDULE_ID, $submitted);
        $page = $this->CreatePage();

        $this->assertSame($expected, $page->GetRoleIds());
        $this->assertSame($expected, $page->GetGroupAdminIds());
        $this->assertSame($expected, $page->GetResourceAdminIds());
        $this->assertSame($expected, $page->GetScheduleAdminIds());
    }

    public function testIdListsAreEmptyWhenNotSubmitted(): void
    {
        $page = $this->CreatePage();

        $this->assertSame([], $page->GetRoleIds());
        $this->assertSame([], $page->GetGroupAdminIds());
        $this->assertSame([], $page->GetResourceAdminIds());
        $this->assertSame([], $page->GetScheduleAdminIds());
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
