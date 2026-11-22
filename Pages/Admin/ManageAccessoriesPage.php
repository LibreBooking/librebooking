<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageAccessoriesPresenter.php');

interface IManageAccessoriesPage extends IActionPage
{
    public function GetAccessoryId(): ?int;

    /**
     * @return string|null
     */
    public function GetAccessoryName();

    public function GetQuantityAvailable(): ?int;

    /**
     * @param $accessories AccessoryDto[]
     */
    public function BindAccessories($accessories);

    /**
     * @param BookableResource[] $resources
     */
    public function BindResources($resources);

    /**
     * @param ResourceAccessory[] $resources
     */
    public function SetAccessoryResources($resources);

    /**
     * @return array<array-key, mixed> the selected resource IDs as submitted;
     *                                 the caller must check each entry
     */
    public function GetAccessoryResources();

    /**
     * @return string[]
     */
    public function GetAccessoryResourcesMinimums();

    /**
     * @return string[]
     */
    public function GetAccessoryResourcesMaximums();
}

class ManageAccessoriesPage extends ActionPage implements IManageAccessoriesPage
{
    /**
     * @var ManageAccessoriesPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('ManageAccessories', 1);
        $this->presenter = new ManageAccessoriesPresenter($this, new ResourceRepository(), new AccessoryRepository());
    }

    public function ProcessPageLoad()
    {
        $this->presenter->PageLoad();

        $this->Display('Admin/manage_accessories.tpl');
    }

    public function BindAccessories($accessories)
    {
        $this->Set('accessories', $accessories);
    }

    public function ProcessAction()
    {
        $this->presenter->ProcessAction();
    }

    public function GetAccessoryId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::ACCESSORY_ID);
    }

    public function GetAccessoryName(): ?string
    {
        return $this->GetFormString(key: FormKeys::ACCESSORY_NAME);
    }

    public function GetQuantityAvailable(): ?int
    {
        return $this->GetFormInt(key: FormKeys::ACCESSORY_QUANTITY_AVAILABLE);
    }

    public function ProcessDataRequest($dataRequest)
    {
        $this->presenter->ProcessDataRequest($dataRequest);
    }

    /**
     * @param BookableResource[] $resources
     */
    public function BindResources($resources)
    {
        $this->Set('resources', $resources);
    }

    /**
     * @param ResourceAccessory[] $resources
     */
    public function SetAccessoryResources($resources)
    {
        $this->SetJson($resources);
    }

    /**
     * Returns the selected resource IDs as submitted, without dropping
     * entries that are not strings. The presenter rejects the whole request
     * when an entry is malformed; dropping it here would turn a selection
     * holding only malformed entries into "no resources selected".
     *
     * @return array<array-key, mixed> a single value becomes a one-item
     *                                 list; empty when the field is missing
     *                                 or blank
     */
    public function GetAccessoryResources(): array
    {
        $resources = $this->GetForm(var: FormKeys::ACCESSORY_RESOURCE);
        if (is_array($resources)) {
            return $resources;
        }

        return $resources === null || $resources === '' ? [] : [$resources];
    }

    /**
     * @return string[]
     */
    public function GetAccessoryResourcesMinimums(): array
    {
        return $this->GetFormStringList(key: FormKeys::ACCESSORY_MIN_QUANTITY);
    }

    /**
     * @return string[]
     */
    public function GetAccessoryResourcesMaximums(): array
    {
        return $this->GetFormStringList(key: FormKeys::ACCESSORY_MAX_QUANTITY);
    }
}
