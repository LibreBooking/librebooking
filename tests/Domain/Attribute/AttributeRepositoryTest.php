<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/AttributeRepository.php');

class AttributeRepositoryTest extends TestBase
{
    /**
     * @var AttributeRepository
     */
    private $repository;

    public function setUp(): void
    {
        parent::setup();

        $this->repository = new AttributeRepository();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testAddsAttribute()
    {
        $label = 'label';
        $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
        $category = CustomAttributeCategory::RESERVATION;
        $regex = '/regex/';
        $required = false;
        $possibleValues = '';
        $sortOrder = '4';
        $entityId = 12;
        $adminOnly = true;
        $secondaryCategory = CustomAttributeCategory::USER;
        $secondaryEntityId = '828';
        $isPrivate = true;

        $attribute = CustomAttribute::Create(
            $label,
            $type,
            $category,
            $regex,
            $required,
            $possibleValues,
            $sortOrder,
            $entityId,
            $adminOnly
        );

        $attribute->WithSecondaryEntities($secondaryCategory, $secondaryEntityId);
        $attribute->WithIsPrivate($isPrivate);

        $this->repository->Add($attribute);
        $this->assertTrue($this->db->ContainsCommand(new AddAttributeCommand(
            $label,
            $type,
            $category,
            $regex,
            $required,
            $possibleValues,
            $sortOrder,
            $adminOnly,
            $secondaryCategory,
            [$secondaryEntityId],
            $isPrivate
        )));
    }

    public function testLoadsAttributeById()
    {
        $id = 12098;
        $label = 'label';
        $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
        $category = CustomAttributeCategory::RESERVATION;
        $regex = '/regex/';
        $required = false;
        $possibleValues = 'val1,val2,val3';
        $sortOrder = '4';
        $entityId = '12';
        $adminOnly = true;

        $row1 = $this->GetAttributeRow(
            $id,
            $label,
            $type,
            $category,
            $regex,
            $required,
            $possibleValues,
            $sortOrder,
            $entityId,
            null,
            $adminOnly,
            CustomAttributeCategory::USER,
            '1,2,3',
            '1!sep!2!sep!3'
        );

        $this->db->SetRows([$row1]);

        $attribute = $this->repository->LoadById($id);

        $expectedFirstAttribute = new CustomAttribute($id, $label, $type, $category, $regex, $required, $possibleValues, $sortOrder, $entityId, $adminOnly);
        $expectedFirstAttribute->WithSecondaryEntities(CustomAttributeCategory::USER, [1, 2, 3], '1!sep!2!sep!3');

        $this->assertEquals($expectedFirstAttribute, $attribute);
        $this->assertEquals(new GetAttributeByIdCommand($id), $this->db->_LastCommand);
    }

    public function testUpdatesAttribute()
    {
        $id = 12098;
        $label = 'label';
        $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
        $category = CustomAttributeCategory::RESERVATION;
        $regex = '/regex/';
        $required = false;
        $possibleValues = 'val1,val2,val3';
        $sortOrder = '4';
        $entityId = 10;
        $adminOnly = true;
        $secondaryCategory = CustomAttributeCategory::USER;
        $secondaryEntityIds = '828,829';
        $isPrivate = true;

        $attribute = new CustomAttribute($id, $label, $type, $category, $regex, $required, $possibleValues, $sortOrder, $entityId, $adminOnly);
        $attribute->WithSecondaryEntities($secondaryCategory, $secondaryEntityIds);
        $attribute->WithIsPrivate($isPrivate);

        $this->repository->Update($attribute);

        $this->assertTrue($this->db->ContainsCommand(new UpdateAttributeCommand(
            $id,
            $label,
            $type,
            $category,
            $regex,
            $required,
            $possibleValues,
            $sortOrder,
            $adminOnly,
            $secondaryCategory,
            [828, 829],
            $isPrivate
        )));
    }

    public function testDeletesAttributeById()
    {
        $id = 12098;

        $this->repository->DeleteById($id);
        $this->assertEquals(new DeleteAttributeCommand($id), $this->db->_Commands[0]);
        $this->assertEquals(new DeleteAttributeValuesCommand($id), $this->db->_Commands[1]);
    }

    public function testLoadsAttributesByCategory()
    {
        $id = 12098;
        $label = 'label';
        $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
        $category = CustomAttributeCategory::RESERVATION;
        $regex = '/regex/';
        $required = false;
        $possibleValues = 'val1,val2,val3';
        $sortOrder = '4';
        $entityIds = '12';
        $entityDescriptions = 'entity desc';

        $row1 = $this->GetAttributeRow(
            $id,
            $label,
            $type,
            $category,
            $regex,
            $required,
            $possibleValues,
            $sortOrder,
            $entityIds,
            $entityDescriptions
        );
        $row2 = $this->GetAttributeRow(2);

        $this->db->SetRows([$row1, $row2]);

        $attributes = $this->repository->GetByCategory(CustomAttributeCategory::RESERVATION);

        $expectedFirstAttribute = new CustomAttribute($id, $label, $type, $category, $regex, $required, $possibleValues, $sortOrder, $entityIds);
        $expectedFirstAttribute->WithEntityDescriptions([$entityDescriptions]);

        $this->assertEquals(2, count($attributes));
        $this->assertEquals($expectedFirstAttribute, $attributes[0]);
        $this->assertEquals(new GetAttributesByCategoryCommand(CustomAttributeCategory::RESERVATION), $this->db->_LastCommand);
    }

    public function testGetsAttributeEntityValues()
    {
        $a1 = 1;
        $a2 = 2;
        $e1 = 10;
        $v1 = '13';
        $v2 = '222';

        $category = CustomAttributeCategory::USER;
        $entityIds = [1, 4, 6, $e1];

        $row1 = $this->GetAttributeValueRow($a1, $e1, $v1);
        $row2 = $this->GetAttributeValueRow($a2, $e1, $v2);

        $this->db->SetRows([$row1, $row2]);

        $values = $this->repository->GetEntityValues($category, $entityIds);

        $this->assertEquals(new GetAttributeMultipleValuesCommand($category, $entityIds), $this->db->_LastCommand);
        $this->assertEquals($a1, $values[0]->AttributeId);
        $this->assertEquals($e1, $values[0]->EntityId);
        $this->assertEquals($v1, $values[0]->Value);
        $this->assertEquals($a2, $values[1]->AttributeId);
        $this->assertEquals($e1, $values[1]->EntityId);
        $this->assertEquals($v2, $values[1]->Value);
    }

    private function GetAttributeRow(
        $id,
        $label = '',
        $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
        $category = CustomAttributeCategory::RESERVATION,
        $regex = null,
        $required = true,
        $possibleValues = null,
        $sortOrder = null,
        $entityId = null,
        $entityDescription = null,
        $adminOnly = false,
        $secondaryCategory = null,
        $secondaryEntityIds = null,
        $secondaryEntityDescriptions = null
    ) {
        return [
            ColumnNames::ATTRIBUTE_ID => $id,
            ColumnNames::ATTRIBUTE_LABEL => $label,
            ColumnNames::ATTRIBUTE_TYPE => $type,
            ColumnNames::ATTRIBUTE_CATEGORY => $category,
            ColumnNames::ATTRIBUTE_CONSTRAINT => $regex,
            ColumnNames::ATTRIBUTE_REQUIRED => $required,
            ColumnNames::ATTRIBUTE_POSSIBLE_VALUES => $possibleValues,
            ColumnNames::ATTRIBUTE_SORT_ORDER => $sortOrder,
            ColumnNames::ATTRIBUTE_ENTITY_IDS => $entityId,
            ColumnNames::ATTRIBUTE_ENTITY_DESCRIPTIONS => $entityDescription,
            ColumnNames::ATTRIBUTE_ADMIN_ONLY => $adminOnly,
            ColumnNames::ATTRIBUTE_SECONDARY_CATEGORY => $secondaryCategory,
            ColumnNames::ATTRIBUTE_SECONDARY_ENTITY_IDS => $secondaryEntityIds,
            ColumnNames::ATTRIBUTE_SECONDARY_ENTITY_DESCRIPTIONS => $secondaryEntityDescriptions,
        ];
    }

    private function GetAttributeValueRow($attributeid, $entityId, $value)
    {
        return [
            ColumnNames::ATTRIBUTE_ID => $attributeid,
            ColumnNames::ATTRIBUTE_ENTITY_ID => $entityId,
            ColumnNames::ATTRIBUTE_VALUE => $value];
    }
}
