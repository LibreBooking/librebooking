<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailTemplateContext.php');

class ReservationEmailTemplateContextTest extends TestBase
{
    public function testReservationAttributesMatchLegacySelectionRules(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');
        $additionalResource = new FakeBookableResource(200, 'Additional');

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithResource($primaryResource);
        $series->AddResource($additionalResource);
        $series->WithAttributeValue(new AttributeValue(1, 'primary match'));
        $series->WithAttributeValue(new AttributeValue(2, 'no secondary entities'));
        $series->WithAttributeValue(new AttributeValue(3, 'additional resource only'));

        $matchingPrimary = (new ReservationEmailTemplateTestAttribute(1))
            ->ForResourceIds([$primaryResource->GetResourceId()]);
        $noSecondaryEntities = new ReservationEmailTemplateTestAttribute(2);
        $matchingAdditionalOnly = (new ReservationEmailTemplateTestAttribute(3))
            ->ForResourceIds([$additionalResource->GetResourceId()]);

        $attributeRepository = new FakeAttributeRepository();
        $attributeRepository->_CustomAttributes = [
            $matchingPrimary,
            $noSecondaryEntities,
            $matchingAdditionalOnly,
        ];

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, $attributeRepository);

        $attributes = $context->ReservationAttributes();

        $this->assertCount(1, $attributes);
        $this->assertEquals(1, $attributes[0]->Id());
        $this->assertEquals('primary match', $attributes[0]->Value());
    }

    public function testResourcesReturnsMultiResourcePayloadAndHonorsAdminOnlyFilter(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::UPLOAD_IMAGE_URL, 'uploads');

        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');
        $primaryResource->SetLocation('Room A');
        $primaryResource->SetContact('owner@example.com');
        $primaryResource->SetImage('primary.png');
        $primaryResource->WithAttribute(new AttributeValue(11, 'primary-visible'));
        $primaryResource->WithAttribute(new AttributeValue(12, 'primary-admin'));

        $additionalResource = new FakeBookableResource(200, 'Secondary');
        $additionalResource->SetLocation('Room B');
        $additionalResource->SetContact('secondary@example.com');
        $additionalResource->WithAttribute(new AttributeValue(11, 'secondary-visible'));
        $additionalResource->WithAttribute(new AttributeValue(12, 'secondary-admin'));

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithResource($primaryResource);
        $series->AddResource($additionalResource);

        $visibleResourceAttribute = $this->createResourceAttribute(11, [100, 200], false);
        $adminOnlyResourceAttribute = $this->createResourceAttribute(12, [100, 200], true);

        $attributeRepository = new FakeAttributeRepository();
        $attributeRepository->_CustomAttributes = [$visibleResourceAttribute, $adminOnlyResourceAttribute];

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, $attributeRepository);

        $withoutAdminOnly = $context->Resources(false);
        $this->assertCount(2, $withoutAdminOnly);
        $this->assertEquals('Primary', $withoutAdminOnly[0]['name']);
        $this->assertEquals('Room A', $withoutAdminOnly[0]['location']);
        $this->assertEquals('owner@example.com', $withoutAdminOnly[0]['contact']);
        $this->assertEquals('uploads/primary.png', $withoutAdminOnly[0]['image']);
        $this->assertCount(1, $withoutAdminOnly[0]['attributes']);
        $this->assertEquals(11, $withoutAdminOnly[0]['attributes'][0]->Id());
        $this->assertEquals('primary-visible', $withoutAdminOnly[0]['attributes'][0]->Value());
        $this->assertEquals('Secondary', $withoutAdminOnly[1]['name']);
        $this->assertCount(1, $withoutAdminOnly[1]['attributes']);
        $this->assertEquals(11, $withoutAdminOnly[1]['attributes'][0]->Id());
        $this->assertEquals('secondary-visible', $withoutAdminOnly[1]['attributes'][0]->Value());

        $withAdminOnly = $context->Resources(true);
        $this->assertCount(2, $withAdminOnly[0]['attributes']);
        $this->assertEquals(12, $withAdminOnly[0]['attributes'][1]->Id());
        $this->assertEquals('primary-admin', $withAdminOnly[0]['attributes'][1]->Value());
        $this->assertCount(2, $withAdminOnly[1]['attributes']);
        $this->assertEquals(12, $withAdminOnly[1]['attributes'][1]->Id());
        $this->assertEquals('secondary-admin', $withAdminOnly[1]['attributes'][1]->Value());
    }

    public function testRepeatVariablesReturnsEmptyListsForNonRecurringSeries(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithResource($primaryResource);
        $series->WithCurrentInstance(new TestReservation());

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, new FakeAttributeRepository());
        $repeatVariables = $context->GetRecurrenceInstances('UTC');

        $this->assertEmpty($repeatVariables['RepeatDates']);
        $this->assertEmpty($repeatVariables['RepeatRanges']);
    }

    public function testRepeatVariablesExpandsRecurringSeriesUsingRequestedTimezone(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithBookedBy(new FakeUserSession(false, 'UTC', 20));
        $series->WithResource($primaryResource);

        $start = Date::Parse('2026-03-01 10:00:00', 'UTC');
        $end = Date::Parse('2026-03-01 11:00:00', 'UTC');
        $series->WithCurrentInstance(new TestReservation('ref-1', new DateRange($start, $end), 1));
        $series->WithRepeatOptions(new RepeatDaily(1, Date::Parse('2026-03-03', 'UTC')));

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, new FakeAttributeRepository());
        $repeatVariables = $context->GetRecurrenceInstances('America/Chicago');

        $this->assertCount(3, $repeatVariables['RepeatDates']);
        $this->assertCount(3, $repeatVariables['RepeatRanges']);
        $this->assertEquals('America/Chicago', $repeatVariables['RepeatDates'][0]->Timezone());
        $this->assertEquals('America/Chicago', $repeatVariables['RepeatRanges'][0]->GetBegin()->Timezone());
    }

    public function testCreatedByReturnsNullWhenBookedByMatchesOwner(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithBookedBy(new FakeUserSession(false, 'UTC', $owner->Id()));
        $series->WithResource($primaryResource);

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, new FakeAttributeRepository());

        $this->assertNull($context->CreatedBy());
    }

    public function testCreatedByReturnsNameWhenBookedByDiffersFromOwner(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithBookedBy(new FakeUserSession(false, 'UTC', 99));
        $series->WithResource($primaryResource);

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, new FakeAttributeRepository());
        $createdBy = $context->CreatedBy();

        $this->assertNotNull($createdBy);
        $this->assertEquals('first last', (string)$createdBy);
    }

    private function createResourceAttribute(int $id, array $entityIds, bool $adminOnly): CustomAttribute
    {
        return new CustomAttribute(
            $id,
            'resource attribute ' . $id,
            CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
            CustomAttributeCategory::RESOURCE,
            null,
            false,
            null,
            1,
            $entityIds,
            $adminOnly
        );
    }
}

class ReservationEmailTemplateTestAttribute extends FakeCustomAttribute
{
    public function __construct(int $id)
    {
        parent::__construct($id);
    }

    public function ForResourceIds(array $entityIds): self
    {
        $this->WithSecondaryEntities(CustomAttributeCategory::RESOURCE, $entityIds);

        return $this;
    }
}
