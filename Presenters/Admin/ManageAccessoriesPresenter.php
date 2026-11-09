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
        $accessoryResources = [];
        $resources = $this->page->GetAccessoryResources();
        $min = $this->page->GetAccessoryResourcesMinimums();
        $max = $this->page->GetAccessoryResourcesMaximums();

        foreach ($resources as $resourceId) {
            $accessoryResources[] = new ResourceAccessory($resourceId, $min[$resourceId], $max[$resourceId]);
        }

        $accessoryId = $this->page->GetAccessoryId();
        $accessory = $this->accessoryRepository->LoadById($accessoryId);
        if ($accessory === null) {
            Log::Error('Cannot change accessory resources, accessory %s not found', $accessoryId);
            return;
        }

        $accessory->ChangeResources($accessoryResources);
        $this->accessoryRepository->Update($accessory);
    }
}
