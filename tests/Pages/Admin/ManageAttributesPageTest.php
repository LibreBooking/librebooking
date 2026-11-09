<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageAttributesPage.php');

class ManageAttributesPageTest extends TestBase
{
    public function testEntityIdsAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::ATTRIBUTE_ENTITY, ['10', 'abc', '11']);

        $this->assertSame([10, 11], $this->CreatePage()->GetEntityIds());
    }

    public function testEntityIdsWrapASingleId(): void
    {
        $this->fakeServer->SetForm(FormKeys::ATTRIBUTE_ENTITY, '10');

        $this->assertSame([10], $this->CreatePage()->GetEntityIds());
    }

    public function testEntityIdsAreEmptyWhenNothingIsSubmitted(): void
    {
        $page = $this->CreatePage();

        $this->assertSame([], $page->GetEntityIds());
        $this->assertSame([], $page->GetSecondaryEntityIds());
    }

    public function testSecondaryEntitiesAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::ATTRIBUTE_SECONDARY_ENTITY_IDS, ['1029', '2028']);
        $this->fakeServer->SetForm(FormKeys::ATTRIBUTE_SECONDARY_CATEGORY, (string)CustomAttributeCategory::USER);
        $page = $this->CreatePage();

        $this->assertSame([1029, 2028], $page->GetSecondaryEntityIds());
        $this->assertSame(CustomAttributeCategory::USER, $page->GetSecondaryCategory());
    }

    public function testSortOrderAndAttributeIdAreParsedAsIntegers(): void
    {
        $this->fakeServer->SetForm(FormKeys::ATTRIBUTE_SORT_ORDER, '5');
        $this->fakeServer->SetQuerystring(QueryStringKeys::ATTRIBUTE_ID, '1091');
        $page = $this->CreatePage();

        $this->assertSame(5, $page->GetSortOrder());
        $this->assertSame(1091, $page->GetAttributeId());
    }

    public function testEmptySortOrderIsNull(): void
    {
        $this->fakeServer->SetForm(FormKeys::ATTRIBUTE_SORT_ORDER, '');

        $this->assertNull($this->CreatePage()->GetSortOrder());
    }

    private function CreatePage(): ManageAttributesPage
    {
        return new class () extends ManageAttributesPage {
            public function __construct()
            {
                $this->server = ServiceLocator::GetServer();
            }
        };
    }
}
