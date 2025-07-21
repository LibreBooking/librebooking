<?php

class ResourceGroupTree
{
    /**
     * @var ResourceGroup[] $references
     */
    protected $references = [];

    /**
     * @var array|ResourceGroup[]
     */
    protected $groups = [];

    /**
     * @var array|ResourceDto[]
     */
    protected $resources = [];

    /**
     * @var ResourceGroup[]
     */
    private $orphaned = [];

    public function AddGroup(ResourceGroup $group)
    {
        $groupId = $group->id;
        $this->references[$groupId] = $group;

        if (array_key_exists($groupId, $this->orphaned)) {
            foreach ($this->orphaned as $orphanedGroup) {
                $this->references[$groupId]->AddChild($orphanedGroup);
            }

            unset($this->orphaned[$groupId]);
        }

        // It it's a root node, we add it directly to the tree
        $parent_id = $group->parent_id;
        if (empty($parent_id)) {
            $this->groups[] = $group;
        } else {
            if (!array_key_exists($parent_id, $this->references)) {
                // parent hasn't been added yet, hold this off until the parent shows up
                $this->orphaned[$parent_id] = $group;
            } else {
                // It was not a root node, add this node as a reference in the parent.
                $this->references[$parent_id]->AddChild($group);
            }
        }
    }

    public function AddAssignment(ResourceGroupAssignment $assignment)
    {
        if (array_key_exists($assignment->group_id, $this->references)) {
            $this->resources[$assignment->resource_id] = new ResourceDto(
                $assignment->resource_id,
                $assignment->resource_name,
                true,
                true,
                $assignment->GetScheduleId(),
                $assignment->GetMinimumLength(),
                $assignment->GetResourceTypeId(),
                $assignment->GetAdminGroupId(),
                $assignment->GetScheduleAdminGroupId(),
                $assignment->GetStatusId(),
                $assignment->GetRequiresApproval(),
                $assignment->IsCheckInEnabled(),
                $assignment->IsAutoReleased(),
                $assignment->GetAutoReleaseMinutes(),
                $assignment->GetColor(),
                $assignment->GetMaxConcurrentReservations()
            );
            $this->references[$assignment->group_id]->AddResource($assignment);
        }
    }

    /**
     * @param bool $includeDefaultGroup
     * @return array|ResourceGroup[]
     */
    public function GetGroups($includeDefaultGroup = true)
    {
        if ($includeDefaultGroup) {
            return $this->groups;
        } else {
            return array_slice($this->groups, 1);
        }
    }

    /**
     * @param bool $includeDefaultGroup
     * @return array|ResourceGroup[]
     */
    public function GetGroupList($includeDefaultGroup = true)
    {
        if ($includeDefaultGroup) {
            return $this->references;
        } else {
            return array_slice($this->references, 1, null, true);
        }
    }

    /**
     * @param int $groupId
     * @param int[] $resourceIds
     * @return int[]
     */
    public function GetResourceIds($groupId, &$resourceIds = [])
    {
        $group = $this->references[$groupId];

        if (empty($group->children)) {
            return $resourceIds;
        }

        foreach ($group->children as $child) {
            if ($child->type == ResourceGroup::RESOURCE_TYPE) {
                $resourceIds[] = $child->resource_id;
            } else {
                $this->GetResourceIds($child->id, $resourceIds);
            }
        }

        return $resourceIds;
    }

    /**
     * @param int $groupId
     * @return ResourceGroup
     */
    public function GetGroup($groupId)
    {
        return $this->references[$groupId];
    }

    /**
     * @return IBookableResource[] array of resources keyed by their ids
     */
    public function GetAllResources()
    {
        return $this->resources;
    }
}

class ResourceGroup
{
    public const RESOURCE_TYPE = 'resource';
    public const GROUP_TYPE = 'group';

    public $id;
    public $name;
    public $label;
    public $parent;
    public $parent_id;
    /**
     * @var ResourceGroup[]|ResourceGroupAssignment[]
     */
    public $children = [];
    public $type = ResourceGroup::GROUP_TYPE;

    public function __construct($id, $name, $parentId = null)
    {
        $this->WithId($id);
        $this->SetName($name);
        $this->parent_id = $parentId;
    }

    /**
     * @param $resourceGroup ResourceGroup
     */
    public function AddChild(ResourceGroup $resourceGroup)
    {
        $resourceGroup->parent_id = $this->id;
        $this->children[] = $resourceGroup;
    }

    /**
     * @param $assignment ResourceGroupAssignment
     */
    public function AddResource(ResourceGroupAssignment $assignment)
    {
        $this->children[] = $assignment;
    }

    /**
     * @param string $groupName
     * @param int $parentId
     * @return ResourceGroup
     */
    public static function Create($groupName, $parentId = null)
    {
        return new ResourceGroup(null, $groupName, $parentId);
    }

    /**
     * @param int $id
     */
    public function WithId($id)
    {
        $this->id = $id;
    }

    public function SetName($name)
    {
        $this->name = $name;
        $this->label = $name;
    }

    /**
     * @param int $targetId
     */
    public function MoveTo($targetId)
    {
        $this->parent_id = $targetId;
    }

    public function Rename($newName)
    {
        $this->SetName($newName);
    }

    public function __toString()
    {
        return $this->name;
    }
}

class ResourceGroupAssignment implements IBookableResource
{
    public $type = ResourceGroup::RESOURCE_TYPE;
    public $group_id;
    public $resource_name;
    public $id;
    public $label;
    public $resource_id;
    public $resourceAdminGroupId;
    public $scheduleId;
    public $statusId;
    public $scheduleAdminGroupId;
    public $requiresApproval;
    public $isCheckInEnabled;
    public $isAutoReleased;
    public $autoReleaseMinutes;
    public $minLength;
    public $resourceTypeId;
    public $color;
    public $textColor;
    public $maxConcurrentReservations;

    public function __construct(
        $group_id,
        $resource_name,
        $resource_id,
        $resourceAdminGroupId,
        $scheduleId,
        $statusId,
        $scheduleAdminGroupId,
        $requiresApproval,
        $isCheckInEnabled,
        $isAutoReleased,
        $autoReleaseMinutes,
        $minLength,
        $resourceTypeId,
        $color,
        $maxConcurrentReservations
    ) {
        $this->group_id = $group_id;
        $this->resource_name = $resource_name;
        $this->id = "{$this->type}-{$group_id}-{$resource_id}";
        $this->label = $resource_name;
        $this->resource_id = $resource_id;
        $this->resourceAdminGroupId = $resourceAdminGroupId;
        $this->scheduleId = $scheduleId;
        $this->statusId = $statusId;
        $this->scheduleAdminGroupId = $scheduleAdminGroupId;
        $this->requiresApproval = $requiresApproval;
        $this->isCheckInEnabled = $isCheckInEnabled;
        $this->isAutoReleased = $isAutoReleased;
        $this->autoReleaseMinutes = $autoReleaseMinutes;
        $this->minLength = $minLength;
        $this->resourceTypeId = $resourceTypeId;
        $this->color = $color;
        $this->textColor = '';
        if (!empty($color)) {
            $textColor = new ContrastingColor($color);
            $this->textColor = $textColor->__toString();
        }
        $this->maxConcurrentReservations = $maxConcurrentReservations;
    }

    public function GetId()
    {
        return $this->resource_id;
    }

    public function GetName()
    {
        return $this->resource_name;
    }

    public function GetAdminGroupId()
    {
        return $this->resourceAdminGroupId;
    }

    public function GetScheduleId()
    {
        return $this->scheduleId;
    }

    public function GetScheduleAdminGroupId()
    {
        return $this->scheduleAdminGroupId;
    }

    public function GetStatusId()
    {
        return $this->statusId;
    }

    public function GetResourceId()
    {
        return $this->resource_id;
    }

    public function GetRequiresApproval()
    {
        return $this->requiresApproval;
    }

    public function IsCheckInEnabled()
    {
        return $this->isCheckInEnabled;
    }

    public function IsAutoReleased()
    {
        return $this->isAutoReleased;
    }

    public function GetAutoReleaseMinutes()
    {
        return $this->autoReleaseMinutes;
    }

    public function GetMinimumLength()
    {
        return $this->minLength;
    }

    public function GetResourceTypeId()
    {
        return $this->resourceTypeId;
    }

    public function GetColor()
    {
        return $this->color;
    }

    public function GetTextColor()
    {
        return $this->textColor;
    }

    public function GetMaxConcurrentReservations()
    {
        return $this->maxConcurrentReservations;
    }
}
