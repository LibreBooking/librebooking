<?php

require_once(ROOT_DIR . 'Presenters/Admin/ManageResourceGroupsPresenter.php');

class ManageResourceGroupsPresenterTest extends TestBase
{
    /**
     * @var ManageResourceGroupsPresenter
     */
    private $presenter;

    /**
     * @var IManageResourceGroupsPage|PHPUnit\Framework\MockObject\MockObject
     */
    private $page;

    /**
     * @var IResourceRepository|PHPUnit\Framework\MockObject\MockObject
     */
    private $resourceRepository;


    public function setUp(): void
    {
        parent::setup();

        $this->page = $this->createMock('IManageResourceGroupsPage');
        $this->resourceRepository = $this->createMock('IResourceRepository');

        $this->presenter = new ManageResourceGroupsPresenter($this->page, $this->resourceRepository);
    }

    public function testBindsResourcesAndGroupsOnLoad()
    {
        $groupTree = new ResourceGroupTree();
        $resources = [];

        $this->resourceRepository
                ->expects($this->once())
        ->method('GetResourceGroups')
        ->willReturn($groupTree);

        $this->resourceRepository
                ->expects($this->once())
        ->method('GetResourceList')
        ->willReturn($resources);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('BindResourceGroups')
        ->with($this->equalTo($groupTree));

        $this->page
                ->expects($this->atLeastOnce())
        ->method('BindResources')
        ->with($this->equalTo($resources));

        $this->presenter->PageLoad();
    }

    public function testAddsResourceToGroup()
    {
        $resourceId = 1;
        $groupId = 2;

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetResourceId')
        ->willReturn($resourceId);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetGroupId')
        ->willReturn($groupId);

        $this->resourceRepository
                ->expects($this->once())
        ->method('AddResourceToGroup')
        ->with($this->equalTo($resourceId), $this->equalTo($groupId));

        $this->presenter->AddResource();
    }

    public function testRemovesResourceFromGroup()
    {
        $resourceId = 1;
        $groupId = 2;

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetResourceId')
        ->willReturn($resourceId);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetGroupId')
        ->willReturn($groupId);

        $this->resourceRepository
                ->expects($this->once())
        ->method('RemoveResourceFromGroup')
        ->with($this->equalTo($resourceId), $this->equalTo($groupId));

        $this->presenter->RemoveResource();
    }

    public function testMovesGroupNode()
    {
        $nodeId = 1;
        $targetId = 2;
        $nodeType = ResourceGroup::GROUP_TYPE;
        $previousNodeId = 3;

        $group = new ResourceGroup($nodeId, 'name', $previousNodeId);
        $group->MoveTo($targetId);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetNodeId')
        ->willReturn($nodeId);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetTargetNodeId')
        ->willReturn($targetId);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetNodeType')
        ->willReturn($nodeType);

        $this->page
                ->expects($this->atLeastOnce())
        ->method('GetPreviousNodeId')
        ->willReturn($previousNodeId);

        $this->resourceRepository
                ->expects($this->once())
        ->method('LoadResourceGroup')
        ->with($this->equalTo($nodeId))
        ->willReturn($group);

        $this->resourceRepository
                ->expects($this->once())
        ->method('UpdateResourceGroup')
        ->with($group);

        $this->presenter->MoveNode();
    }
}
