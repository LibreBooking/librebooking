<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');

class ManageAttributesActions
{
    public const AddAttribute = 'addAttribute';
    public const DeleteAttribute = 'deleteAttribute';
    public const UpdateAttribute = 'updateAttribute';
}

class ManageAttributesPresenter extends ActionPresenter
{
    /**
     * @var IManageAttributesPage
     */
    private $page;

    /**
     * @var IAttributeRepository
     */
    private $attributeRepository;

    public function __construct(IManageAttributesPage $page, IAttributeRepository $attributeRepository)
    {
        parent::__construct($page);

        $this->page = $page;
        $this->attributeRepository = $attributeRepository;

        $this->AddAction(ManageAttributesActions::AddAttribute, 'AddAttribute');
        $this->AddAction(ManageAttributesActions::DeleteAttribute, 'DeleteAttribute');
        $this->AddAction(ManageAttributesActions::UpdateAttribute, 'UpdateAttribute');
    }

    public function PageLoad()
    {
        // Set category visibility flags for template conditional rendering
        $this->page->SetCategoryVisibilityRules();
    }

    public function AddAttribute()
    {
        $attributeName = $this->page->GetLabel();
        $type = $this->page->GetType();
        $scope = $this->page->GetCategory();
        $regex = $this->page->GetValidationExpression();
        $required = $this->page->GetIsRequired();
        $possibleValues = $this->page->GetPossibleValues();
        $sortOrder = $this->page->GetSortOrder();
        $entityIds = $this->page->GetEntityIds();
        $adminOnly = $this->page->GetIsAdminOnly();

        Log::Debug('Adding new attribute named: %s', $attributeName);

        $attribute = CustomAttribute::Create($attributeName, $type, $scope, $regex, $required, $possibleValues, $sortOrder, $entityIds, $adminOnly);
        $this->AddSecondaryEntities($attribute);
        $attribute->WithIsPrivate($this->page->GetIsPrivate());

        $this->attributeRepository->Add($attribute);
    }

    public function DeleteAttribute()
    {
        $attributeId = $this->page->GetAttributeId();
        Log::Debug('Deleting attribute with id: %s', $attributeId);
        $this->attributeRepository->DeleteById($attributeId);
    }

    public function UpdateAttribute()
    {
        $attributeId = $this->page->GetAttributeId();
        $attributeName = $this->page->GetLabel();
        $regex = $this->page->GetValidationExpression();
        $required = $this->page->GetIsRequired();
        $possibleValues = $this->page->GetPossibleValues();
        $sortOrder = $this->page->GetSortOrder();
        $entityIds = $this->page->GetEntityIds();
        $adminOnly = $this->page->GetIsAdminOnly();

        Log::Debug('Updating attribute with id: %s', $attributeId);

        $attribute = $this->attributeRepository->LoadById($attributeId);
        $attribute->Update($attributeName, $regex, $required, $possibleValues, $sortOrder, $entityIds, $adminOnly);
        $this->AddSecondaryEntities($attribute);
        $attribute->WithIsPrivate($this->page->GetIsPrivate());

        $this->attributeRepository->Update($attribute);
    }

    public function HandleDataRequest($dataRequest)
    {
        $categoryId = $this->page->GetRequestedCategory();

        if (empty($categoryId)) {
            $categoryId = CustomAttributeCategory::RESERVATION;
        }

        $this->page->SetCategory($categoryId);
        $this->page->BindAttributes($this->attributeRepository->GetByCategory($categoryId));
    }

    private function AddSecondaryEntities(CustomAttribute $attribute)
    {
        if ($this->page->GetLimitAttributeScope()) {
            $secondaryEntityIds = $this->page->GetSecondaryEntityIds();
            $secondaryCategory = $this->page->GetSecondaryCategory();

            $attribute->WithSecondaryEntities($secondaryCategory, $secondaryEntityIds);
        } else {
            $attribute->WithSecondaryEntities(null, null);
        }
    }
}
