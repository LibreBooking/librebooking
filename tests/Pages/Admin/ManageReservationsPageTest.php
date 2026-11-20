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

    public function testInlineAttributeEditWithNestedValueIsRejected(): void
    {
        // The page must hand the submitted shape to the parser. Reading a
        // nested list (value[][]=x) as null would clear the attribute.
        $this->fakeServer->SetForm(FormKeys::NAME, FormKeys::ATTRIBUTE_PREFIX . '5');
        $this->fakeServer->SetForm(FormKeys::VALUE, [['nested']]);
        $page = $this->CreatePage();

        $this->assertNull(
            AttributeFormParser::GetInlineAttributeValue(name: $page->GetName(), value: $page->GetValue())
        );
    }

    public function testInlineAttributeEditReadsCheckboxList(): void
    {
        // A checkbox attribute editor submits value[].
        $this->fakeServer->SetForm(FormKeys::NAME, FormKeys::ATTRIBUTE_PREFIX . '5');
        $this->fakeServer->SetForm(FormKeys::VALUE, ['1']);
        $page = $this->CreatePage();

        $attributeValue = AttributeFormParser::GetInlineAttributeValue(name: $page->GetName(), value: $page->GetValue());

        $this->assertNotNull($attributeValue);
        $this->assertSame('5', $attributeValue->AttributeId);
        $this->assertSame('1', $attributeValue->Value);
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
