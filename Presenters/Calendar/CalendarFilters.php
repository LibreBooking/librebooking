<?php

class CalendarFilters
{
    public const FilterSchedule = 'schedule';
    public const FilterResource = 'resource';

    /**
     * @var array|CalendarFilter[]
     */
    private $filters = [];

    /**
     * @var ResourceGroupTree
     */
    private $resourceGroupTree;

    /**
     * @param array|Schedule[] $schedules
     * @param array|ResourceDto[] $resources
     * @param int $selectedScheduleId
     * @param int $selectedResourceId
     * @param ResourceGroupTree $resourceGroupTree
     */
    public function __construct($schedules, $resources, $selectedScheduleId, $selectedResourceId, ResourceGroupTree $resourceGroupTree)
    {
        $this->resourceGroupTree = $resourceGroupTree;

        if (!empty($resources)) {
            $this->filters[] = new CalendarFilter(self::FilterSchedule, null, Resources::GetInstance()->GetString('AllReservations'), (empty($selectedResourceId) && empty($selectedScheduleId)));
        }
        foreach ($schedules as $schedule) {
            if ($this->ScheduleContainsNoResources($schedule, $resources)) {
                continue;
            }

            $filter = new CalendarFilter(self::FilterSchedule, $schedule->GetId(), $schedule->GetName(), (empty($selectedResourceId) && $selectedScheduleId == $schedule->GetId()));

            foreach ($resources as $resource) {
                if ($resource->GetScheduleId() == $schedule->GetId()) {
                    $filter->AddSubFilter(new CalendarFilter(self::FilterResource, $resource->GetResourceId(), $resource->GetName(), ($selectedResourceId == $resource->GetResourceId())));
                }
            }

            $this->filters[] = $filter;
        }
    }

    /**
     * @return bool
     */
    public function IsEmpty()
    {
        return empty($this->filters);
    }

    /**
     * @return array|CalendarFilter[]
     */
    public function GetFilters()
    {
        return $this->filters;
    }

    /**
     * @return ResourceGroupTree
     */
    public function GetResourceGroupTree()
    {
        return $this->resourceGroupTree;
    }

    /**
     * @param Schedule $schedule
     * @param ResourceDto[] $resources
     * @return bool
     */
    private function ScheduleContainsNoResources(Schedule $schedule, $resources)
    {
        foreach ($resources as $resource) {
            if ($resource->GetScheduleId() == $schedule->GetId()) {
                return false;
            }
        }

        return true;
    }
}

class CalendarFilter
{
    /**
     * @var array|CalendarFilter[]
     */
    private $filters = [];

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var bool
     */
    private $selected;

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function Id()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function Type()
    {
        return $this->type;
    }

    /**
     * @return bool
     */
    public function Selected()
    {
        return $this->selected;
    }

    public function __construct($type, $id, $name, $selected)
    {
        $this->type = $type;
        $this->id = $id;
        $this->name = $name;
        $this->selected = $selected;
    }

    public function AddSubFilter(CalendarFilter $subfilter)
    {
        $this->filters[] = $subfilter;
    }

    /**
     * @return array|CalendarFilter[]
     */
    public function GetFilters()
    {
        return $this->filters;
    }
}
