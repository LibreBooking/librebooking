<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageAttributesPage.php');

class ManageAttributesPresenterTest extends TestBase
{
    /**
     * @var FakeAttributePage
     */
    private $page;

    /**
     * @var IAttributeRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $attributeRepository;

    /**
     * @var ManageAttributesPresenter
     */
    private $presenter;

    public function setUp(): void
    {
        parent::setup();

        $this->page = new FakeAttributePage();
        $this->attributeRepository = $this->createMock('IAttributeRepository');

        $this->presenter = new ManageAttributesPresenter($this->page, $this->attributeRepository);
    }

    public function testBindsAttributesForRequestedCategory()
    {
        $categoryId = CustomAttributeCategory::RESERVATION;
        $this->page->_requestedCategoryId = $categoryId;

        $attributes = [CustomAttribute::Create('abc', CustomAttributeTypes::SINGLE_LINE_TEXTBOX, CustomAttributeCategory::RESERVATION, null, false, null, null, null)];

        $this->attributeRepository->expects($this->once())
                ->method('GetByCategory')
                ->with($this->equalTo($categoryId))
                ->willReturn($attributes);

        $this->presenter->HandleDataRequest('');

        $this->assertSame($attributes, $this->page->_boundAttributes);
    }

    public function testAddsNewAttribute()
    {
        $label = 'new label';
        $scope = CustomAttributeCategory::RESERVATION;
        $type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
        $required = true;
        $regex = '/$\d^/';
        $possibleValues = '1,2,3';
        $sortOrder = "5";
        $entityIds = [10];
        $adminOnly = true;
        $secondaryEntityIds = ['1029', '2028'];

        $this->page->_label = $label;
        $this->page->_type = $type;
        $this->page->_category = $scope;
        $this->page->_required = $required;
        $this->page->_regex = $regex;
        $this->page->_possibleValues = $possibleValues;
        $this->page->_sortOrder = $sortOrder;
        $this->page->_entityIds = $entityIds;
        $this->page->_adminOnly = $adminOnly;
        $this->page->_limitAttributeScope = true;
        $this->page->_secondaryCategory = CustomAttributeCategory::USER;
        $this->page->_secondaryEntityIds = $secondaryEntityIds;

        $expectedAttribute = CustomAttribute::Create($label, $type, $scope, $regex, $required, $possibleValues, $sortOrder, $entityIds, $adminOnly);
        $expectedAttribute->WithSecondaryEntities(CustomAttributeCategory::USER, $secondaryEntityIds);

        $this->attributeRepository->expects($this->once())
                ->method('Add')
                ->with($this->equalTo($expectedAttribute))
                ->willReturn(1);

        $this->presenter->AddAttribute();
    }

    public function testUpdatesAttribute()
    {
        $attributeId = 1091;
        $label = 'new label';
        $required = true;
        $regex = '/$\d^/';
        $possibleValues = '1,2,3';
        $sortOrder = "5";
        $entityIds = [1,2,3];
        $isPrivate = true;
        $adminOnly = true;
        $secondaryEntityIds = ['1029', '2028'];

        $this->page->_label = $label;
        $this->page->_required = $required;
        $this->page->_regex = $regex;
        $this->page->_possibleValues = $possibleValues;
        $this->page->_attributeId = $attributeId;
        $this->page->_sortOrder = $sortOrder;
        $this->page->_entityIds = $entityIds;
        $this->page->_adminOnly = $adminOnly;
        $this->page->_limitAttributeScope = true;
        $this->page->_secondaryCategory = CustomAttributeCategory::USER;
        $this->page->_secondaryEntityIds = $secondaryEntityIds;
        $this->page->_isPrivate = $isPrivate;

        $expectedAttribute = CustomAttribute::Create('', CustomAttributeTypes::CHECKBOX, CustomAttributeCategory::RESERVATION, null, false, null, $sortOrder, $entityIds, $adminOnly);

        $this->attributeRepository->expects($this->once())
                ->method('LoadById')
                ->with($this->equalTo($attributeId))
                ->willReturn($expectedAttribute);

        $this->attributeRepository->expects($this->once())
                ->method('Update')
                ->with($this->anything());

        $this->presenter->UpdateAttribute();

        $this->assertEquals($label, $expectedAttribute->Label());
        $this->assertEquals($regex, $expectedAttribute->Regex());
        $this->assertEquals($required, $expectedAttribute->Required());
        $this->assertEquals($possibleValues, $expectedAttribute->PossibleValues());
        $this->assertEquals($sortOrder, $expectedAttribute->SortOrder());
        $this->assertEquals([], $expectedAttribute->EntityIds(), 'cannot set entityids for reservation');
        $this->assertEquals($adminOnly, $expectedAttribute->AdminOnly());
        $this->assertEquals($secondaryEntityIds, $expectedAttribute->SecondaryEntityIds());
        $this->assertEquals(CustomAttributeCategory::USER, $expectedAttribute->SecondaryCategory());
        $this->assertEquals($isPrivate, $expectedAttribute->IsPrivate());
    }

    public function testDeletesAttributeById()
    {
        $attributeId = 1091;
        $this->page->_attributeId = $attributeId;

        $this->attributeRepository->expects($this->once())
                        ->method('DeleteById')
                        ->with($this->equalTo($attributeId));

        $this->presenter->DeleteAttribute();
    }
}

class FakeAttributePage extends FakeActionPageBase implements IManageAttributesPage
{
    public $_label;
    public $_type;
    public $_category;
    public $_required;
    public $_regex;
    public $_possibleValues;
    public $_requestedCategoryId;
    public $_boundAttributes;
    public $_attributeId;
    public $_sortOrder;
    public $_entityIds;
    public $_adminOnly;
    public $_limitAttributeScope;
    public $_secondaryCategory;
    public $_secondaryEntityIds;
    public $_isPrivate;


    public function GetLabel()
    {
        return $this->_label;
    }

    public function GetType()
    {
        return $this->_type;
    }

    public function GetCategory()
    {
        return $this->_category;
    }

    public function GetValidationExpression()
    {
        return $this->_regex;
    }

    public function GetIsRequired()
    {
        return $this->_required;
    }

    public function GetEntityIds()
    {
        return $this->_entityIds;
    }

    public function GetPossibleValues()
    {
        return $this->_possibleValues;
    }

    public function GetRequestedCategory()
    {
        return $this->_requestedCategoryId;
    }

    public function GetSortOrder()
    {
        return $this->_sortOrder;
    }

    public function BindAttributes($attributes)
    {
        $this->_boundAttributes = $attributes;
    }

    public function SetCategory($categoryId)
    {
        // TODO: Implement SetCategory() method.
    }

    public function GetAttributeId()
    {
        return $this->_attributeId;
    }

    public function GetIsAdminOnly()
    {
        return $this->_adminOnly;
    }

    public function GetSecondaryEntityIds()
    {
        return $this->_secondaryEntityIds;
    }

    public function GetSecondaryCategory()
    {
        return $this->_secondaryCategory;
    }

    public function GetLimitAttributeScope()
    {
        return $this->_limitAttributeScope;
    }

    public function GetIsPrivate()
    {
        return $this->_isPrivate;
    }
}
