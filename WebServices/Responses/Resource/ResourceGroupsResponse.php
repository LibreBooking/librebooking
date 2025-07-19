<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');

class ResourceGroupsResponse extends RestResponse
{
    public $groups;

    public function __construct(ResourceGroupTree $groupTree)
    {
        $this->groups = $groupTree->GetGroups();
    }

    public static function Example()
    {
        return new ExampleResourceGroupsResponse();
    }
}

class ExampleResourceGroupsResponse extends ResourceGroupsResponse
{
    public function __construct()
    {
        $groups = new ResourceGroupTree();
        $group = new ResourceGroup(0, 'Resource Group 1');
        $group2 = new ResourceGroup(1, 'Resource Group 2');
        $group->AddResource(new ResourceGroupAssignment(0, 'Resource 1', 1, null, 2, ResourceStatus::AVAILABLE, null, false, true, true, 30, 10, 1, '#ffffff', 2));
        $group2->AddResource(new ResourceGroupAssignment(1, 'Resource 2', 1, null, 2, ResourceStatus::AVAILABLE, null, true, false, false, null, null, 2, '#000000', 1));
        $group->AddChild($group2);
        $groups->AddGroup($group);

        $this->groups = $groups->GetGroups();
    }
}
