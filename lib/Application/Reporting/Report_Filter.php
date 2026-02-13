<?php

class Report_Filter
{
    /**
     * @var int[]|null
     */
    private $resourceIds;

    /**
     * @var int[]|null
     */
    private $scheduleIds;

    /**
     * @var int|null
     */
    private $userId;

    /**
     * @var int|null
     */
    private $participantId;

    /**
     * @var int[]|null
     */
    private $groupIds;

    /**
     * @var int[]|null
     */
    private $accessoryIds;

    /**
     * @var bool
     */
    private $includeDeleted;

    /**
     * @var int[]|null
     */
    private $resourceTypeIds;

    /**
     * @param $resourceIds int[]|null
     * @param $scheduleIds int[]|null
     * @param $userId int|null
     * @param $groupIds int[]|null
     * @param $accessoryIds int[]|null
     * @param $participantId int|null
     * @param $includeDeleted bool
     * @param $resourceTypeIds int[]|null
     */
    public function __construct($resourceIds, $scheduleIds, $userId, $groupIds, $accessoryIds, $participantId, $includeDeleted, $resourceTypeIds)
    {
        $removeEmpty = function ($value) {
            return !empty($value);
        };

        if (!is_array($resourceIds)) {
            $resourceIds = [$resourceIds];
        }
        if (!is_array($scheduleIds)) {
            $scheduleIds = [$scheduleIds];
        }
        if (!is_array($groupIds)) {
            $groupIds = [$groupIds];
        }
        if (!is_array($accessoryIds)) {
            $accessoryIds = [$accessoryIds];
        }
        if (!is_array($resourceTypeIds)) {
            $resourceTypeIds = [$resourceTypeIds];
        }

        $this->resourceIds = array_filter($resourceIds, $removeEmpty);
        $this->scheduleIds = array_filter($scheduleIds, $removeEmpty);
        $this->userId = $userId;
        $this->groupIds = array_filter($groupIds, $removeEmpty);
        $this->accessoryIds = array_filter($accessoryIds, $removeEmpty);
        $this->participantId = $participantId;
        $this->includeDeleted = $includeDeleted;
        $this->resourceTypeIds = array_filter($resourceTypeIds, $removeEmpty) ;
    }

    public function Add(ReportCommandBuilder $builder)
    {
        if (!empty($this->resourceIds)) {
            $builder->WithResourceIds($this->resourceIds);
        }
        if (!empty($this->scheduleIds)) {
            $builder->WithScheduleIds($this->scheduleIds);
        }
        if (!empty($this->userId)) {
            $builder->WithUserId($this->userId);
        }
        if (!empty($this->participantId)) {
            $builder->WithParticipantId($this->participantId);
        }
        if (!empty($this->groupIds)) {
            $builder->WithGroupIds($this->groupIds);
        }
        if (!empty($this->accessoryIds)) {
            $builder->WithAccessoryIds($this->accessoryIds);
        }
        if ($this->includeDeleted) {
            $builder->WithDeleted();
        }
        if (!empty($this->resourceTypeIds)) {
            $builder->WithResourceTypeIds($this->resourceTypeIds);
        }
    }

    /**
     * @return int[]|null
     */
    public function ResourceIds()
    {
        return $this->resourceIds;
    }

    /**
     * @return int[]|null
     */
    public function ResourceTypeIds()
    {
        return $this->resourceTypeIds;
    }

    /**
     * @return int[]|null
     */
    public function ScheduleIds()
    {
        return $this->scheduleIds;
    }

    /**
     * @return int|null
     */
    public function UserId()
    {
        return $this->userId;
    }

    /**
     * @return int|null
     */
    public function ParticipantId()
    {
        return $this->participantId;
    }

    /**
     * @return int[]|null
     */
    public function GroupIds()
    {
        return $this->groupIds;
    }

    /**
     * @return int[]|null
     */
    public function AccessoryIds()
    {
        return $this->accessoryIds;
    }

    /**
     * @return bool
     */
    public function IncludeDeleted()
    {
        return $this->includeDeleted === true;
    }
}
