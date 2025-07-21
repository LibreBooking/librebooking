<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageResourceTypesPage.php');

class ManageResourceTypesActions
{
    public const Add = 'Add';
    public const Update = 'Update';
    public const Delete = 'Delete';
    public const ChangeAttribute = 'ChangeAttribute';
}

class ManageResourceTypesPresenter extends ActionPresenter
{
    /**
     * @var IManageResourceTypesPage
     */
    private $page;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    public function __construct(
        IManageResourceTypesPage $page,
        IResourceRepository $resourceRepository,
        IAttributeService $attributeService
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->resourceRepository = $resourceRepository;
        $this->attributeService = $attributeService;

        $this->AddAction(ManageResourceTypesActions::Add, 'Add');
        $this->AddAction(ManageResourceTypesActions::Update, 'Update');
        $this->AddAction(ManageResourceTypesActions::Delete, 'Delete');
        $this->AddAction(ManageResourceTypesActions::ChangeAttribute, 'ChangeAttribute');
    }

    public function PageLoad()
    {
        $types = $this->resourceRepository->GetResourceTypes();
//
        //		$ids = array();
        //		foreach ($types as $type)
        //		{
        //			$ids[] = $type->Id();
        //		}

        $attributeList = $this->attributeService->GetByCategory(CustomAttributeCategory::RESOURCE_TYPE);
        $this->page->BindAttributeList($attributeList);

        $this->page->BindResourceTypes($types);
    }

    public function Add()
    {
        $name = $this->page->GetResourceTypeName();
        $description = $this->page->GetDescription();

        Log::Debug('Adding resource type. Name=%s', $name);

        $this->resourceRepository->AddResourceType(ResourceType::CreateNew($name, $description));
    }

    public function Update()
    {
        $id = $this->page->GetId();
        $name = $this->page->GetResourceTypeName();
        $description = $this->page->GetDescription();

        Log::Debug('Updating resource type id=%s', $id);

        $type = $this->resourceRepository->LoadResourceType($id);

        $type->SetName($name);
        $type->SetDescription($description);

        $this->resourceRepository->UpdateResourceType($type);
    }

    private function GetInlineAttributeValue()
    {
        $value = $this->page->GetValue();
        if (is_array($value)) {
            $value = $value[0];
        }
        $id = str_replace(FormKeys::ATTRIBUTE_PREFIX, '', $this->page->GetName());

        return new AttributeValue($id, $value);
    }

    public function ChangeAttribute()
    {
        $id = $this->page->GetId();
        $type = $this->resourceRepository->LoadResourceType($id);

        $attributeValue = $this->GetInlineAttributeValue();

        Log::Debug('Changing attributes. ResourceTypeId=%s, AttributeId=%s, Value=%s', $id, $attributeValue->AttributeId, $attributeValue->Value);

        $type->ChangeAttribute($attributeValue);
        $this->resourceRepository->UpdateResourceType($type);
    }

    public function Delete()
    {
        $id = $this->page->GetId();
        Log::Debug('Deleting resource type id=%s', $id);

        $this->resourceRepository->RemoveResourceType($id);
    }

    public function ProcessDataRequest($dataRequest)
    {
        if ($dataRequest == 'all') {
            $this->page->SetResourceTypesJson(array_map(
                ['ResourceTypeJson', 'FromResourceType'],
                $this->resourceRepository->GetResourceTypes()
            ));
        }
    }

    protected function LoadValidators($action)
    {
        if ($action == ManageResourceTypesActions::ChangeAttribute) {
            $attributes = $this->GetInlineAttributeValue();
            $this->page->RegisterValidator(
                'attributeValidator',
                new AttributeValidatorInline(
                    $this->attributeService,
                    CustomAttributeCategory::RESOURCE_TYPE,
                    $attributes,
                    $this->page->GetId(),
                    true,
                    true
                )
            );
        }
    }
}

class ResourceTypeJson
{
    public $Id;
    public $Name;

    public function __construct($id, $name)
    {
        $this->Id = $id;
        $this->Name = $name;
    }

    /**
     * @param ResourceType $resourceType
     * @return ResourceTypeJson
     */
    public static function FromResourceType($resourceType)
    {
        return new ResourceTypeJson($resourceType->Id(), $resourceType->Name());
    }
}
