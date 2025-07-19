<?php

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Pages/Admin/ManageResourceGroupsPage.php');

class ManageResourceGroupsActions
{
    public const AddGroup = 'AddGroup';
    public const AddResource = 'AddResource';
    public const DeleteGroup = 'DeleteGroup';
    public const MoveNode = 'MoveNode';
    public const RemoveResource = 'RemoveResource';
    public const RenameGroup = 'RenameGroup';
}

class ManageResourceGroupsPresenter extends ActionPresenter
{
    /**
     * @var IManageResourceGroupsPage
     */
    private $page;

    /**
     * @var IResourceRepository
     */
    private $resourceRepository;

    public function __construct(
        IManageResourceGroupsPage $page,
        IResourceRepository $resourceRepository
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->resourceRepository = $resourceRepository;

        $this->AddAction(ManageResourceGroupsActions::AddResource, 'AddResource');
        $this->AddAction(ManageResourceGroupsActions::RemoveResource, 'RemoveResource');
        $this->AddAction(ManageResourceGroupsActions::AddGroup, 'AddGroup');
        $this->AddAction(ManageResourceGroupsActions::MoveNode, 'MoveNode');
        $this->AddAction(ManageResourceGroupsActions::RenameGroup, 'RenameGroup');
        $this->AddAction(ManageResourceGroupsActions::DeleteGroup, 'DeleteGroup');
    }

    public function PageLoad()
    {
        $this->page->BindResourceGroups($this->resourceRepository->GetResourceGroups());
        $this->page->BindResources($this->resourceRepository->GetResourceList());
    }

    /**
     * @internal should only be used for testing
     */
    public function AddResource()
    {
        $resourceId = $this->page->GetResourceId();
        $groupId = $this->page->GetGroupId();

        Log::Debug('Adding resource to group. ResourceId=%s, GroupId=%s', $resourceId, $groupId);

        $this->resourceRepository->AddResourceToGroup($resourceId, $groupId);
    }

    /**
     * @internal should only be used for testing
     */
    public function RemoveResource()
    {
        $resourceId = $this->page->GetResourceId();
        $groupId = $this->page->GetGroupId();

        Log::Debug('Removing resource from group. ResourceId=%s, GroupId=%s', $resourceId, $groupId);

        $this->resourceRepository->RemoveResourceFromGroup($resourceId, $groupId);
    }

    /**
     * @internal should only be used for testing
     */
    public function AddGroup()
    {
        $groupName = $this->page->GetGroupName();
        $parentId = $this->page->GetParentId();

        Log::Debug('Adding new resource group. GroupName=%s, ParentId=%s', $groupName, $parentId);
        $addedGroup = $this->resourceRepository->AddResourceGroup(ResourceGroup::Create($groupName, $parentId));
        $this->page->BindNewGroup($addedGroup);
    }

    public function MoveNode()
    {
        $nodeId = $this->page->GetNodeId();
        $nodeType = $this->page->GetNodeType();
        $targetNodeId = $this->page->GetTargetNodeId();
        $previousId = $this->page->GetPreviousNodeId();

        Log::Debug('Moving resource group node. NodeId=%s, NodeType=%s, TargetNodeId=%s, PreviousNodeId=%s', $nodeId, $nodeType, $targetNodeId, $previousId);

        $group = $this->resourceRepository->LoadResourceGroup($nodeId);
        $group->MoveTo($targetNodeId);
        $this->resourceRepository->UpdateResourceGroup($group);
    }

    public function RenameGroup()
    {
        $nodeId = $this->page->GetNodeId();
        $newName = $this->page->GetGroupName();

        Log::Debug('Renaming resource group. NodeId=%s, NewName=%s', $nodeId, $newName);

        $group = $this->resourceRepository->LoadResourceGroup($nodeId);
        $group->Rename($newName);
        $this->resourceRepository->UpdateResourceGroup($group);
    }

    public function DeleteGroup()
    {
        $nodeId = $this->page->GetNodeId();

        Log::Debug('Deleting resource group. NodeId=%s', $nodeId);

        $this->resourceRepository->DeleteResourceGroup($nodeId);
    }

    /**
     * @internal should only be used for testing
     */
    public function Rename()
    {
    }
}
