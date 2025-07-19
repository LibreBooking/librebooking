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

    /**
     * @return int
     */
    public function GetResourceId();

    /**
     * @return int
     */
    public function GetGroupId();

    /**
     * @return string
     */
    public function GetGroupName();

    /**
     * @return int
     */
    public function GetParentId();

    /**
     * @param ResourceGroup $newGroup
     */
    public function BindNewGroup(ResourceGroup $newGroup);

    /**
     * @return int
     */
    public function GetNodeId();

    /**
     * @return string
     */
    public function GetNodeType();

    /**
     * @return int
     */
    public function GetTargetNodeId();

    /**
     * @return int
     */
    public function GetPreviousNodeId();
}

class ManageResourceGroupsPage extends ActionPage implements IManageResourceGroupsPage
{
    /**
     * @var ManageResourcesPresenter
     */
    protected $_presenter;

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

    /**
     * @return int
     */
    public function GetResourceId()
    {
        return $this->GetQuerystring(QueryStringKeys::RESOURCE_ID);
    }

    /**
     * @return int
     */
    public function GetGroupId()
    {
        return $this->GetQuerystring(QueryStringKeys::GROUP_ID);
    }

    /**
     * @return string
     */
    public function GetGroupName()
    {
        return $this->GetForm(FormKeys::GROUP_NAME);
    }

    /**
     * @return int
     */
    public function GetParentId()
    {
        return $this->GetForm(FormKeys::PARENT_ID);
    }

    public function BindNewGroup(ResourceGroup $newGroup)
    {
        $this->SetJson(json_encode($newGroup));
    }

    /**
     * @return int
     */
    public function GetNodeId()
    {
        return $this->GetQuerystring(QueryStringKeys::NODE_ID);
    }

    /**
     * @return string
     */
    public function GetNodeType()
    {
        return $this->GetQuerystring(QueryStringKeys::TYPE);
    }

    /**
     * @return int
     */
    public function GetTargetNodeId()
    {
        $groupId = $this->GetQuerystring(QueryStringKeys::GROUP_ID);

        if (empty($groupId) || strtolower($groupId) == 'null') {
            return null;
        }
        return $groupId;
    }

    /**
     * @return int
     */
    public function GetPreviousNodeId()
    {
        return $this->GetQuerystring(QueryStringKeys::PREVIOUS_ID);
    }
}
