<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'Presenters/Admin/ManageAccessoriesPresenter.php');

interface IManageAccessoriesPage extends IActionPage
{
    public function GetAccessoryId(): ?int;

    /**
     * @return string
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
     * @return string[]
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

    /**
     * @return string
     */
    public function GetAccessoryName()
    {
        return $this->GetForm(FormKeys::ACCESSORY_NAME);
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
     * @return string[]
     */
    public function GetAccessoryResources()
    {
        $r = $this->GetForm(FormKeys::ACCESSORY_RESOURCE);
        if (empty($r)) {
            return [];
        }

        return $r;
    }

    /**
     * @return string[]
     */
    public function GetAccessoryResourcesMinimums()
    {
        $r = $this->GetForm(FormKeys::ACCESSORY_MIN_QUANTITY);
        if (empty($r)) {
            return [];
        }

        return $r;
    }

    /**
     * @return string[]
     */
    public function GetAccessoryResourcesMaximums()
    {
        $r = $this->GetForm(FormKeys::ACCESSORY_MAX_QUANTITY);
        if (empty($r)) {
            return [];
        }

        return $r;
    }
}
