<?php

require_once(ROOT_DIR . 'Pages/Admin/AdminPage.php');
require_once(ROOT_DIR . 'lib/Application/Attributes/namespace.php');

interface IManageResourceGroupsPage extends IActionPage
{
    /**
     * @param ResourceGroupTree $resourceGroups
     */
    public function BindResourceGroups(ResourceGroupTree $resourceGroups);

    /**
     * @param BookableResource[] $resources
     */
    public function BindResources($resources);

    public function GetResourceId(): ?int;

    public function GetGroupId(): ?int;

    /**
     * @return string
     */
    public function GetGroupName();

    public function GetParentId(): ?int;

    /**
     * @param ResourceGroup $newGroup
     */
    public function BindNewGroup(ResourceGroup $newGroup);

    public function GetNodeId(): ?int;

    /**
     * @return string
     */
    public function GetNodeType();

    public function GetTargetNodeId(): ?int;

    public function GetPreviousNodeId(): ?string;
}

class ManageResourceGroupsPage extends ActionPage implements IManageResourceGroupsPage
{
    protected ManageResourceGroupsPresenter $_presenter;

    public function __construct()
    {
        parent::__construct('ManageResourceGroups', 1);
        $this->_presenter = new ManageResourceGroupsPresenter($this, new ResourceRepository());
    }

    public function ProcessPageLoad()
    {
        $this->_presenter->PageLoad();

        $this->Display('Admin/Resources/manage_resource_groups.tpl');
    }

    /**
     * @return void
     */
    public function ProcessAction()
    {
        $this->_presenter->ProcessAction();
    }

    /**
     * @param $dataRequest string
     * @return void
     */
    public function ProcessDataRequest($dataRequest)
    {
    }

    public function BindResourceGroups(ResourceGroupTree $resourceGroups)
    {
        $this->Set('ResourceGroups', json_encode($resourceGroups->GetGroups(false)));
    }

    /**
     * @param BookableResource[] $resources
     */
    public function BindResources($resources)
    {
        $this->Set('Resources', $resources);
    }

    public function GetResourceId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::RESOURCE_ID);
    }

    public function GetGroupId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::GROUP_ID);
    }

    /**
     * @return string
     */
    public function GetGroupName()
    {
        return $this->GetForm(FormKeys::GROUP_NAME);
    }

    public function GetParentId(): ?int
    {
        return $this->GetFormInt(key: FormKeys::PARENT_ID);
    }

    public function BindNewGroup(ResourceGroup $newGroup)
    {
        $this->SetJson(json_encode($newGroup));
    }

    public function GetNodeId(): ?int
    {
        return $this->GetQuerystringInt(key: QueryStringKeys::NODE_ID);
    }

    /**
     * @return string
     */
    public function GetNodeType()
    {
        return $this->GetQuerystring(QueryStringKeys::TYPE);
    }

    public function GetTargetNodeId(): ?int
    {
        // The resource group tree sends the literal string "null" when moving a
        // group to the top level; GetQuerystringInt() returns null for it.
        return $this->GetQuerystringInt(key: QueryStringKeys::GROUP_ID);
    }

    public function GetPreviousNodeId(): ?string
    {
        // A tree node ID such as "resource-3-7"; only used for logging.
        $previousId = $this->GetQuerystring(QueryStringKeys::PREVIOUS_ID);

        return is_string($previousId) ? $previousId : null;
    }
}
