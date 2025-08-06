<?php

require_once(ROOT_DIR . 'Pages/SecurePage.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

class ResourceDetailsPage extends Page implements IResourceDetailsPage
{
    /**
     * @var \ResourceDetailsPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('', 1);
        $this->presenter = new ResourceDetailsPresenter($this, new ResourceRepository(), new AttributeService(new AttributeRepository()));
    }

    public function PageLoad()
    {
        $this->presenter->PageLoad();

        $this->smarty->display('Ajax/resourcedetails.tpl');
    }

    public function BindResource(BookableResource $resource)
    {
        $this->Set('resourceName', $resource->GetName());
        $this->Set('description', $resource->GetDescription());
        $this->Set('notes', $resource->GetNotes());
        $this->Set('contactInformation', $resource->GetContact());
        $this->Set('locationInformation', $resource->GetLocation());
        $this->Set('allowMultiday', $resource->GetAllowMultiday());
        $this->Set('minimumDuration', $resource->GetMinLength());
        $this->Set('maximumDuration', $resource->GetMaxLength());

        $this->Set('maxParticipants', $resource->GetMaxParticipants());
        $this->Set('maximumNotice', $resource->GetMaxNotice());
        $this->Set('minimumNotice', $resource->GetMinNoticeAdd());
        $this->Set('requiresApproval', $resource->GetRequiresApproval());
        $this->Set('autoAssign', $resource->GetAutoAssign());
        $this->Set('color', $resource->GetColor());
        $this->Set('textColor', $resource->GetTextColor());
        $this->Set('autoReleaseMinutes', $resource->GetAutoReleaseMinutes());
        $this->Set('isCheckInEnabled', $resource->IsCheckInEnabled());
        $this->Set('creditsEnabled', Configuration::Instance()->GetKey(ConfigKeys::CREDITS_ENABLED, new BooleanConverter()));
        $this->Set('peakCredits', $resource->GetPeakCreditsPerSlot());
        $this->Set('offPeakCredits', $resource->GetCreditsPerSlot());

        if ($resource->HasImage()) {
            $this->Set('imageUrl', $resource->GetImage());
            $this->Set('images', $resource->GetImages());
        }
    }

    public function BindAttributes($attributes)
    {
        $this->Set('Attributes', $attributes);
    }

    public function GetResourceId()
    {
        return ServiceLocator::GetServer()->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    /**
     * @param ResourceType $resourceType
     * @param Attribute[] $attributes
     */
    public function BindResourceType(ResourceType $resourceType, $attributes)
    {
        $this->Set('resourceType', $resourceType->Name());
        $this->Set('ResourceTypeAttributes', $attributes);
    }
}

interface IResourceDetailsPage
{
    /**
     * @param BookableResource $resource
     */
    public function BindResource(BookableResource $resource);

    /**
     * @param Attribute[] $attributes
     */
    public function BindAttributes($attributes);

    /**
     * @param ResourceType $resourceType
     * @param Attribute[] $attributes
     */
    public function BindResourceType(ResourceType $resourceType, $attributes);

    /**
     * @return int
     */
    public function GetResourceId();
}

class ResourceDetailsPresenter
{
    /**
     * @var ResourceRepository
     */
    private $resourceRepository;

    /**
     * @var IResourceDetailsPage
     */
    private $page;

    /**
     * @var IAttributeService
     */
    private $attributeService;

    /**
     * @param IResourceDetailsPage $page
     * @param IResourceRepository $resourceRepository
     * @param IAttributeService $attributeService
     */
    public function __construct(IResourceDetailsPage $page, IResourceRepository $resourceRepository, IAttributeService $attributeService)
    {
        $this->page = $page;
        $this->resourceRepository = $resourceRepository;
        $this->attributeService = $attributeService;
    }

    public function PageLoad()
    {
        $resourceId = $this->page->GetResourceId();
        $resource = $this->resourceRepository->LoadById($resourceId);
        $this->page->BindResource($resource);

        $attributeList = $this->attributeService->GetAttributes(CustomAttributeCategory::RESOURCE, $resourceId);
        $this->page->BindAttributes($attributeList->GetAttributes($resourceId));

        if ($resource->HasResourceType()) {
            $resourceType = $this->resourceRepository->LoadResourceType($resource->GetResourceTypeId());
            $attributeList = $this->attributeService->GetAttributes(CustomAttributeCategory::RESOURCE_TYPE, $resource->GetResourceTypeId());

            $this->page->BindResourceType($resourceType, $attributeList->GetAttributes($resource->GetResourceTypeId()));
        }
    }
}
