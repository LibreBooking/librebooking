<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailTemplateContext.php');

class ReservationEmailTemplateContextTest extends TestBase
{
    public function testReservationAttributesIncludeGlobalAndAllSelectedResourceMatches(): void
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
        $series->WithAttributeValue(new AttributeValue(4, 'unrelated resource'));

        $matchingPrimary = (new ReservationEmailTemplateTestAttribute(1))
            ->ForResourceIds([$primaryResource->GetResourceId()]);
        $noSecondaryEntities = new ReservationEmailTemplateTestAttribute(2);
        $matchingAdditionalOnly = (new ReservationEmailTemplateTestAttribute(3))
            ->ForResourceIds([$additionalResource->GetResourceId()]);
        $unrelatedResource = (new ReservationEmailTemplateTestAttribute(4))
            ->ForResourceIds([999]);

        $attributeRepository = new FakeAttributeRepository();
        $attributeRepository->_CustomAttributes = [
            $matchingPrimary,
            $noSecondaryEntities,
            $matchingAdditionalOnly,
            $unrelatedResource,
        ];

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, $attributeRepository);

        $attributes = $context->ReservationAttributes();

        $this->assertCount(3, $attributes);
        $this->assertEquals(1, $attributes[0]->Id());
        $this->assertEquals('primary match', $attributes[0]->Value());
        $this->assertEquals(2, $attributes[1]->Id());
        $this->assertEquals('no secondary entities', $attributes[1]->Value());
        $this->assertEquals(3, $attributes[2]->Id());
        $this->assertEquals('additional resource only', $attributes[2]->Value());
        $this->assertNotContains(4, array_map(static fn (LBAttribute $attribute): int => $attribute->Id(), $attributes));
    }

    public function testResourcesReturnsMultiResourcePayloadAndHonorsAdminOnlyFilter(): void
    {
        $this->fakeConfig->SetKey(ConfigKeys::UPLOAD_IMAGE_URL, 'uploads');

        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');
        $primaryResource->SetLocation('Room A');
        $primaryResource->SetContact('owner@example.com');
        $primaryResource->SetScheduleId(1);
        $primaryResource->SetAdminGroupId(501);
        $primaryResource->SetImage('primary.png');
        $primaryResource->WithAttribute(new AttributeValue(11, 'primary-visible'));
        $primaryResource->WithAttribute(new AttributeValue(12, 'primary-admin'));

        $additionalResource = new FakeBookableResource(200, 'Secondary');
        $additionalResource->SetLocation('Room B');
        $additionalResource->SetContact('secondary@example.com');
        $additionalResource->SetScheduleId(2);
        $additionalResource->SetAdminGroupId(502);
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

        $scheduleRepository = new FakeScheduleRepository();
        $scheduleRepository->_Schedules = [
            new Schedule(1, 'Schedule One', true, 0, 7),
            new Schedule(2, 'Schedule Two', false, 0, 7),
        ];

        $groupRepository = $this->createMock(IGroupViewRepository::class);
        $groupRepository->expects($this->once())
            ->method('GetGroupsByRole')
            ->with(RoleLevel::RESOURCE_ADMIN)
            ->willReturn([
                new GroupItemView(501, 'Resource Admin Group A'),
                new GroupItemView(502, 'Resource Admin Group B'),
            ]);

        $context = new ReservationEmailTemplateContext(
            $series,
            $owner,
            $primaryResource,
            $attributeRepository,
            $scheduleRepository,
            $groupRepository
        );

        $withoutAdminOnly = $context->Resources(false);
        $this->assertCount(2, $withoutAdminOnly);
        $this->assertEquals('Primary', $withoutAdminOnly[0]['name']);
        $this->assertEquals('Schedule One', $withoutAdminOnly[0]['scheduleName']);
        $this->assertEquals('Resource Admin Group A', $withoutAdminOnly[0]['resourceAdministrator']);
        $this->assertEquals('Room A', $withoutAdminOnly[0]['location']);
        $this->assertEquals('owner@example.com', $withoutAdminOnly[0]['contact']);
        $this->assertEquals('uploads/primary.png', $withoutAdminOnly[0]['image']);
        $this->assertCount(1, $withoutAdminOnly[0]['attributes']);
        $this->assertEquals(11, $withoutAdminOnly[0]['attributes'][0]->Id());
        $this->assertEquals('primary-visible', $withoutAdminOnly[0]['attributes'][0]->Value());
        $this->assertEquals('Secondary', $withoutAdminOnly[1]['name']);
        $this->assertEquals('Schedule Two', $withoutAdminOnly[1]['scheduleName']);
        $this->assertEquals('Resource Admin Group B', $withoutAdminOnly[1]['resourceAdministrator']);
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

    public function testReservationAttributesRespectSecondaryCategoryWhenScoped(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');
        $primaryResource->SetResourceTypeId(55);
        $additionalResource = new FakeBookableResource(200, 'Additional');
        $additionalResource->SetResourceTypeId(66);

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithResource($primaryResource);
        $series->AddResource($additionalResource);
        $series->WithAttributeValue(new AttributeValue(11, 'resource type match'));
        $series->WithAttributeValue(new AttributeValue(12, 'resource type id collides with resource id'));
        $series->WithAttributeValue(new AttributeValue(13, 'user match'));
        $series->WithAttributeValue(new AttributeValue(14, 'user id collides with resource id'));
        $series->WithAttributeValue(new AttributeValue(15, 'resource match'));
        $series->WithAttributeValue(new AttributeValue(16, 'resource no match'));
        $series->WithAttributeValue(new AttributeValue(17, 'global'));

        $attributeRepository = new FakeAttributeRepository();
        $attributeRepository->_CustomAttributes = [
            (new ReservationEmailTemplateTestAttribute(11))->ForResourceTypeIds([55]),
            (new ReservationEmailTemplateTestAttribute(12))->ForResourceTypeIds([100]),
            (new ReservationEmailTemplateTestAttribute(13))->ForUserIds([$owner->Id()]),
            (new ReservationEmailTemplateTestAttribute(14))->ForUserIds([100]),
            (new ReservationEmailTemplateTestAttribute(15))->ForResourceIds([$additionalResource->GetResourceId()]),
            (new ReservationEmailTemplateTestAttribute(16))->ForResourceIds([999]),
            new ReservationEmailTemplateTestAttribute(17),
        ];

        $context = new ReservationEmailTemplateContext($series, $owner, $primaryResource, $attributeRepository);

        $attributes = $context->ReservationAttributes();
        $attributeIds = array_map(static fn (LBAttribute $attribute): int => $attribute->Id(), $attributes);

        $this->assertSame([11, 13, 15, 17], $attributeIds);
    }

    public function testResourcesBuildsAttributeRowsWithBlankCheckboxAndDatetimeValues(): void
    {
        $owner = new FakeUser(10);
        $primaryResource = new FakeBookableResource(100, 'Primary');
        $primaryResource->SetScheduleId(1);
        $primaryResource->SetAdminGroupId(501);

        $dateObject = Date::Parse('2026-03-01 10:30:00', 'UTC');
        $dateString = '2026-03-01 11:45:00';

        $primaryResource->WithAttribute(new AttributeValue(21, ''));
        $primaryResource->WithAttribute(new AttributeValue(22, '1'));
        $primaryResource->WithAttribute(new AttributeValue(23, '0'));
        $primaryResource->WithAttribute(new AttributeValue(24, $dateObject));
        $primaryResource->WithAttribute(new AttributeValue(25, $dateString));

        $series = new TestReservationSeries();
        $series->WithOwnerId($owner->Id());
        $series->WithResource($primaryResource);

        $attributeRepository = new FakeAttributeRepository();
        $attributeRepository->_CustomAttributes = [
            $this->createResourceAttribute(21, [100], false, CustomAttributeTypes::SINGLE_LINE_TEXTBOX, 'Blank Value'),
            $this->createResourceAttribute(22, [100], false, CustomAttributeTypes::CHECKBOX, 'Checkbox True'),
            $this->createResourceAttribute(23, [100], false, CustomAttributeTypes::CHECKBOX, 'Checkbox False'),
            $this->createResourceAttribute(24, [100], false, CustomAttributeTypes::DATETIME, 'Date Object'),
            $this->createResourceAttribute(25, [100], false, CustomAttributeTypes::DATETIME, 'Date String'),
        ];

        $scheduleRepository = new FakeScheduleRepository();
        $scheduleRepository->_Schedules = [new Schedule(1, 'Schedule One', true, 0, 7)];

        $groupRepository = $this->createMock(IGroupViewRepository::class);
        $groupRepository->expects($this->once())
            ->method('GetGroupsByRole')
            ->with(RoleLevel::RESOURCE_ADMIN)
            ->willReturn([new GroupItemView(501, 'Resource Admin Group A')]);

        $context = new ReservationEmailTemplateContext(
            $series,
            $owner,
            $primaryResource,
            $attributeRepository,
            $scheduleRepository,
            $groupRepository
        );

        $resources = $context->Resources(false);

        $this->assertCount(1, $resources);
        $rowsByLabel = [];
        foreach ($resources[0]['attributeRows'] as $row) {
            $rowsByLabel[$row['label']] = $row['displayValue'];
        }

        $localized = Resources::GetInstance();
        $dateFormat = $localized->GetDateFormat('general_datetime');

        $this->assertSame('', $rowsByLabel['Blank Value']);
        $this->assertSame($localized->GetString('True'), $rowsByLabel['Checkbox True']);
        $this->assertSame($localized->GetString('False'), $rowsByLabel['Checkbox False']);
        $this->assertSame($dateObject->Format($dateFormat), $rowsByLabel['Date Object']);
        $this->assertSame(Date::Parse($dateString)->Format($dateFormat), $rowsByLabel['Date String']);
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

    private function createResourceAttribute(
        int $id,
        array $entityIds,
        bool $adminOnly,
        int $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
        ?string $label = null
    ): CustomAttribute {
        return new CustomAttribute(
            id: $id,
            label: $label ?? ('resource attribute ' . $id),
            type: $type,
            category: CustomAttributeCategory::RESOURCE,
            regex: null,
            required: false,
            possibleValues: null,
            sortOrder: 1,
            entityIds: $entityIds,
            adminOnly: $adminOnly
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

    public function ForResourceTypeIds(array $entityIds): self
    {
        $this->WithSecondaryEntities(CustomAttributeCategory::RESOURCE_TYPE, $entityIds);

        return $this;
    }

    public function ForUserIds(array $entityIds): self
    {
        $this->WithSecondaryEntities(CustomAttributeCategory::USER, $entityIds);

        return $this;
    }
}
