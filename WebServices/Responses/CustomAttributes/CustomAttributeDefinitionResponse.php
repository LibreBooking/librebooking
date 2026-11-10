<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class CustomAttributeDefinitionResponse extends RestResponse
{
    public $id;
    /**
     * @var string
     */
    public $label;

    /**
     * @var int|CustomAttributeTypes
     */
    public $type;

    /**
     * @var int|CustomAttributeCategory
     */
    public $categoryId;

    /**
     * @var string|null null when the attribute has no validation regex
     */
    public $regex;

    /**
     * @var bool
     */
    public $required;

    /**
     * @var string[]
     */
    public $possibleValues;

    /**
     * @var int
     */
    public $sortOrder;

    /**
     * @var int[]
     */
    public $appliesToIds = [];

    /**
     * @var int 1 when the attribute is admin-only, otherwise 0
     */
    public $adminOnly;

    /**
     * @var bool
     */
    public $isPrivate;

    /**
     * @var int|null null when the attribute is not limited to secondary entities
     */
    public $secondaryCategoryId;

    /**
     * @var int[]
     */
    public $secondaryEntityIds;

    public function __construct(IRestServer $server, CustomAttribute $attribute)
    {
        $this->id = $attribute->Id();
        $this->categoryId = $attribute->Category();
        $this->label = $attribute->Label();
        $this->possibleValues = $attribute->PossibleValueList();
        $this->regex = $attribute->Regex();
        $this->required = $attribute->Required();
        $this->type = $attribute->Type();
        $this->sortOrder = $attribute->SortOrder();
        $this->appliesToIds = $attribute->EntityIds();
        $this->isPrivate = $attribute->IsPrivate();
        $this->adminOnly = $attribute->AdminOnly();
        $this->secondaryCategoryId = $attribute->SecondaryCategory();
        $this->secondaryEntityIds = $attribute->SecondaryEntityIds();

        $this->AddService($server, WebServices::AllCustomAttributes, [WebServiceParams::AttributeCategoryId => $this->categoryId]);
        $this->AddService($server, WebServices::GetCustomAttribute, [WebServiceParams::AttributeId => $this->id]);
        $this->AddService($server, WebServices::UpdateCustomAttribute, [WebServiceParams::AttributeId => $this->id]);
        $this->AddService($server, WebServices::DeleteCustomAttribute, [WebServiceParams::AttributeId => $this->id]);
    }

    public static function Example()
    {
        return new ExampleCustomAttributeDefinitionResponse();
    }
}

class ExampleCustomAttributeDefinitionResponse extends CustomAttributeDefinitionResponse
{
    public function __construct()
    {
        $this->id = 1;
        // Allowed values: CustomAttributeTypes::CHECKBOX, CustomAttributeTypes::MULTI_LINE_TEXTBOX,
        // CustomAttributeTypes::SELECT_LIST, CustomAttributeTypes::SINGLE_LINE_TEXTBOX.
        $this->type = CustomAttributeTypes::SINGLE_LINE_TEXTBOX;
        // Allowed values: CustomAttributeCategory::RESERVATION, CustomAttributeCategory::RESOURCE,
        // CustomAttributeCategory::RESOURCE_TYPE, CustomAttributeCategory::USER.
        $this->categoryId = CustomAttributeCategory::RESERVATION;
        $this->label = 'display label';
        $this->possibleValues = ['possible', 'values'];
        $this->regex = 'validation regex';
        $this->required = true;
        $this->sortOrder = 100;
        $this->appliesToIds = [10];
        $this->adminOnly = 1;
        $this->isPrivate = true;
        $this->secondaryCategoryId = 1;
        $this->secondaryEntityIds = [1,2];
    }
}
