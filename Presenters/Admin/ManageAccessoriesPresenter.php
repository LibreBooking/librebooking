<?php

require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');

class ManageAccessoriesActions
{
    public const Add = 'addAccessory';
    public const Change = 'changeAccessory';
    public const Delete = 'deleteAccessory';
    public const ChangeAccessoryResource = 'changeAccessoryResource';
}

class ManageAccessoriesPresenter extends ActionPresenter
{
    /**
     * @var IManageAccessoriesPage
     */
    private $page;

    /**
     * @var IAccessoryRepository
     */
    private $accessoryRepository;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    /**
     * @param IManageAccessoriesPage $page
     * @param IResourceRepository $resourceRepository
     * @param IAccessoryRepository $accessoryRepository
     */
    public function __construct(IManageAccessoriesPage $page, IResourceRepository $resourceRepository, IAccessoryRepository $accessoryRepository)
    {
        parent::__construct($page);

        $this->page = $page;
        $this->resourceRepository = $resourceRepository;
        $this->accessoryRepository = $accessoryRepository;

        $this->AddAction(ManageAccessoriesActions::Add, 'AddAccessory');
        $this->AddAction(ManageAccessoriesActions::Change, 'ChangeAccessory');
        $this->AddAction(ManageAccessoriesActions::Delete, 'DeleteAccessory');
        $this->AddAction(ManageAccessoriesActions::ChangeAccessoryResource, 'ChangeAccessoryResources');
    }

    public function PageLoad()
    {
        $accessories = $this->resourceRepository->GetAccessoryList($this->page->GetSortField(), $this->page->GetSortDirection());
        $resources = $this->resourceRepository->GetResourceList();

        $this->page->BindAccessories($accessories);
        $this->page->BindResources($resources);
    }

    public function AddAccessory()
    {
        $name = $this->page->GetAccessoryName();
        $quantity = $this->page->GetQuantityAvailable();

        Log::Debug('Adding new accessory with name %s and quantity %s', $name, $quantity);

        $this->accessoryRepository->Add(Accessory::Create($name, $quantity));
    }

    public function ChangeAccessory()
    {
        $id = $this->page->GetAccessoryId();
        $name = $this->page->GetAccessoryName();
        $quantity = $this->page->GetQuantityAvailable();

        Log::Debug('Changing accessory with id %s to name %s and quantity %s', $id, $name, $quantity);

        $accessory = $this->accessoryRepository->LoadById($id);
        if ($accessory === null) {
            Log::Error('Cannot change accessory, accessory %s not found', $id);
            return;
        }

        $accessory->SetName($name);
        $accessory->SetQuantityAvailable($quantity);

        $this->accessoryRepository->Update($accessory);
    }

    public function DeleteAccessory()
    {
        $id = $this->page->GetAccessoryId();

        Log::Debug('Deleting accessory with id %s', $id);

        $this->accessoryRepository->Delete($id);
    }

    public function ProcessDataRequest($dataRequest)
    {
        $accessory = $this->accessoryRepository->LoadById($this->page->GetAccessoryId());
        $this->page->SetAccessoryResources($accessory === null ? [] : $accessory->Resources());
    }

    public function ChangeAccessoryResources()
    {
        $accessoryId = $this->page->GetAccessoryId();

        $accessoryResources = $this->GetSubmittedAccessoryResources();
        if ($accessoryResources === null) {
            Log::Error('Not changing accessory resources, the submitted resources or quantities are malformed. AccessoryId=%s', $accessoryId);
            return;
        }

        $accessory = $this->accessoryRepository->LoadById($accessoryId);
        if ($accessory === null) {
            Log::Error('Cannot change accessory resources, accessory %s not found', $accessoryId);
            return;
        }

        $accessory->ChangeResources($accessoryResources);
        $this->accessoryRepository->Update($accessory);
    }

    /**
     * Builds the accessory's resources from the submitted resource IDs and
     * their minimum and maximum quantities. The form submits each quantity
     * keyed by resource ID; a blank quantity means no limit.
     *
     * @return ResourceAccessory[]|null null when a field is not a list, or a
     *                                  selected resource has no minimum or
     *                                  maximum quantity entry
     */
    private function GetSubmittedAccessoryResources(): ?array
    {
        $resources = $this->page->GetAccessoryResources();
        $min = $this->page->GetAccessoryResourcesMinimums();
        $max = $this->page->GetAccessoryResourcesMaximums();

        if (!is_array($resources) || !is_array($min) || !is_array($max)) {
            return null;
        }

        $accessoryResources = [];
        foreach ($resources as $resourceId) {
            if (
                !is_string($resourceId)
                || !is_string($min[$resourceId] ?? null)
                || !is_string($max[$resourceId] ?? null)
            ) {
                return null;
            }

            $accessoryResources[] = new ResourceAccessory(
                resourceId: $resourceId,
                minQuantity: $min[$resourceId],
                maxQuantity: $max[$resourceId],
            );
        }

        return $accessoryResources;
    }
}
