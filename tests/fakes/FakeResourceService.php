<?php

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class FakeResourceService implements IResourceService
{
    /**
     * @var ResourceDto[]
     */
    public $_AllResources = [];

    /**
     * @var ResourceDto[]
     */
    public $_ScheduleResources = [];

    /**
     * @var ScheduleResourceFilter|null
     */
    public $_LastFilter;

    /**
     * Gets resource list for a schedule
     * @param int $scheduleId
     * @param bool $includeInaccessibleResources
     * @param UserSession $user
     * @param ScheduleResourceFilter|null $filter
     * @return array|ResourceDto[]
     */
    public function GetScheduleResources($scheduleId, $includeInaccessibleResources, UserSession $user, $filter = null)
    {
        return $this->_ScheduleResources;
    }

    public function GetAllResources($includeInaccessibleResources, UserSession $user, $filter = null, $pageNumber = null, $pageSize = null)
    {
        $this->_LastFilter = $filter;
        return $this->_AllResources;
    }

    /**
     * @return array|Accessory[]
     */
    public function GetAccessories(): array
    {
        return [];
    }

    /**
     * @param int $scheduleId
     * @param UserSession $user
     * @return ResourceGroupTree
     */
    public function GetResourceGroups($scheduleId, UserSession $user)
    {
        throw new LogicException('GetResourceGroups() not implemented in FakeResourceService');
    }

    /**
     * @return ResourceType[]
     */
    public function GetResourceTypes(): array
    {
        return [];
    }

    /**
     * @return LBAttribute[]
     */
    public function GetResourceAttributes(): array
    {
        return [];
    }

    /**
     * @return LBAttribute[]
     */
    public function GetResourceTypeAttributes(): array
    {
        return [];
    }

    /**
     * @param int $resourceId
     * @return BookableResource
     */
    public function GetResource($resourceId)
    {
        throw new LogicException('GetResource() not implemented in FakeResourceService');
    }
}
