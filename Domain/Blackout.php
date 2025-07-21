<?php

class BlackoutSeries
{
    /**
     * @var int
     */
    protected $seriesId;

    /**
     * @var int
     */
    protected $ownerId;

    /**
     * @var int[]
     */
    protected $resourceIds = [];

    /**
     * @var BlackoutResource[]
     */
    protected $resources = [];

    /**
     * @var Blackout[]
     */
    protected $blackouts = [];

    /**
     * @var int
     */
    protected $blackoutIteration = 0;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var DateRange
     */
    protected $blackoutDate;

    /**
     * @var IRepeatOptions
     */
    protected $repeatOptions;

    /**
     * @var RepeatConfiguration
     */
    protected $repeatConfiguration;

    /**
     * @var bool
     */
    protected $isNew = true;

    protected $currentBlackoutInstanceId;

    /**
     * @var Date[]
     */
    protected $customRepeatDates = [];

    /**
     * @param int $userId
     * @param string $title
     */
    public function __construct($userId, $title)
    {
        $this->WithRepeatOptions(new RepeatNone());
        $this->ownerId = $userId;
        $this->title = $title;
    }

    /**
     * @param $userId
     * @param $title
     * @param DateRange $blackoutDate
     * @return BlackoutSeries
     */
    public static function Create($userId, $title, DateRange $blackoutDate)
    {
        $series = new BlackoutSeries($userId, $title);
        $series->AddBlackout(new Blackout($blackoutDate));
        $series->SetCurrentBlackout($blackoutDate);
        return $series;
    }

    /**
     * @param int $ownerId
     * @param SeriesUpdateScope|string $scope
     * @param string $title
     * @param DateRange $blackoutDate
     * @param IRepeatOptions $repeatOptions
     * @param int[] $resourceIds
     */
    public function Update($ownerId, $scope, $title, $blackoutDate, $repeatOptions, $resourceIds)
    {
        $this->ownerId = $ownerId;
        $this->title = $title;
        $this->resourceIds = [];
        foreach ($resourceIds as $rid) {
            $this->AddResourceId($rid);
        }

        if ($scope == SeriesUpdateScope::ThisInstance) {
            $this->blackouts = [];
            $this->AddBlackout(new Blackout($blackoutDate));
            $this->SetCurrentBlackout($blackoutDate);

            $this->Repeats(new RepeatNone());
        } else {
            $currentDate = $this->CurrentBlackout()->Date();
            $newDate = $blackoutDate;

            $startDiff = DateDiff::BetweenDates($currentDate->GetBegin(), $newDate->GetBegin());
            $endDiff = DateDiff::BetweenDates($currentDate->GetEnd(), $newDate->GetEnd());

            $earliestDate = $this->GetEarliestDate($blackoutDate);

            if (!$earliestDate->Equals($blackoutDate)) {
                $earliestDate = new DateRange($earliestDate->GetBegin()->ApplyDifference($startDiff), $earliestDate->GetEnd()->ApplyDifference($endDiff));
            }

            $this->blackouts = [];

            $this->Repeats($repeatOptions);
            $this->AddBlackout(new Blackout($earliestDate));
            $this->SetCurrentBlackout($earliestDate);
        }

        $this->isNew = $scope == SeriesUpdateScope::ThisInstance;
    }

    private function GetEarliestDate(DateRange $blackoutDate)
    {
        $earliestDate = $blackoutDate;

        foreach ($this->blackouts as $blackout) {
            if ($blackout->StartDate()->LessThan($earliestDate->GetBegin())) {
                $earliestDate = $blackout->Date();
            }
        }

        return $earliestDate;
    }

    /**
     * @return bool
     */
    public function IsNew()
    {
        return $this->isNew;
    }

    /**
     * @return int[]
     */
    public function ResourceIds()
    {
        return $this->resourceIds;
    }

    /**
     * @return int
     */
    public function OwnerId()
    {
        return $this->ownerId;
    }

    /**
     * @return string
     */
    public function Title()
    {
        return $this->title;
    }

    /**
     * @param $resourceId int
     */
    public function AddResourceId($resourceId)
    {
        $this->resourceIds[] = $resourceId;
    }

    public function AddResource(BlackoutResource $resource)
    {
        $this->AddResourceId($resource->GetId());
        $this->resources[] = $resource;
    }

    public function AddBlackout(Blackout $blackout)
    {
        $this->blackoutIteration = 0;
        $blackout->SetSeries($this);
        $this->blackouts[$this->ToKey($blackout->Date())] = $blackout;

        if ($this->repeatOptions->RepeatType() === RepeatType::Custom) {
            $this->customRepeatDates[] = $blackout->StartDate();
        }
    }

    public function Delete(Blackout $blackout)
    {
        if (count($this->blackouts) <= 1) {
            Log::Debug('Only blackout in the series. Cannot delete. Id %s', $blackout->Id());
            return false;
        }
        $key = $this->ToKey($blackout->Date());
        $this->blackoutIteration = 0;
        unset($this->blackouts[$key]);
        $this->currentBlackoutInstanceId = null;

        Log::Debug('Deleted blackout Id %s', $blackout->Id());
        return true;
    }

    /**
     * @return Blackout[]
     */
    public function AllBlackouts()
    {
        if (count($this->blackouts) == 0) {
            return [];
        }

        asort($this->blackouts);
        return $this->blackouts;
    }

    /**
     * @return int
     */
    public function ResourceId()
    {
        return $this->resourceIds[0];
    }

    /**
     * @param int $resourceId
     * @return bool
     */
    public function ContainsResource($resourceId)
    {
        return in_array($resourceId, $this->resourceIds);
    }

    /**
     * @param IRepeatOptions $repeatOptions
     */
    public function Repeats(IRepeatOptions $repeatOptions)
    {
        $this->WithRepeatOptions($repeatOptions);
        foreach ($repeatOptions->GetDates($this->blackoutDate) as $date) {
            $this->AddBlackout(new Blackout($date));
        }
    }

    /**
     * @return string
     */
    public function RepeatType()
    {
        return $this->repeatOptions->RepeatType();
    }

    /**
     * @return string
     */
    public function RepeatConfigurationString()
    {
        return $this->repeatOptions->ConfigurationString();
    }

    public function RepeatConfiguration()
    {
        return $this->repeatConfiguration;
    }

    public function CustomRepeatDates()
    {
        return $this->customRepeatDates;
    }

    /**
     * @return int
     */
    public function Id()
    {
        return $this->seriesId;
    }

    public function WithId($id)
    {
        $this->seriesId = $id;
    }

    public function WithRepeatOptions(IRepeatOptions $repeatOptions)
    {
        $this->repeatOptions = $repeatOptions;
        $this->repeatConfiguration = RepeatConfiguration::Create($repeatOptions->RepeatType(), $repeatOptions->ConfigurationString());
    }

    public function SetCurrentBlackout(DateRange $date)
    {
        $this->blackoutDate = $date;
    }

    protected function WithCurrentBlackoutId($blackoutInstanceId)
    {
        $this->currentBlackoutInstanceId = $blackoutInstanceId;
    }


    /**
     * @param string[] $row
     * @return BlackoutSeries
     */
    public static function FromRow($row)
    {
        $series = new BlackoutSeries($row[ColumnNames::OWNER_USER_ID], $row[ColumnNames::BLACKOUT_TITLE]);
        $series->WithId($row[ColumnNames::BLACKOUT_SERIES_ID]);
        $series->SetCurrentBlackout(new DateRange(Date::FromDatabase($row[ColumnNames::BLACKOUT_START]), Date::FromDatabase($row[ColumnNames::BLACKOUT_END])));
        $series->WithCurrentBlackoutId($row[ColumnNames::BLACKOUT_INSTANCE_ID]);
        $configuration = RepeatConfiguration::Create($row[ColumnNames::REPEAT_TYPE], $row[ColumnNames::REPEAT_OPTIONS]);
        $factory = new RepeatOptionsFactory();
        $options = $factory->Create(
            $row[ColumnNames::REPEAT_TYPE],
            $configuration->Interval,
            $configuration->TerminationDate,
            $configuration->Weekdays,
            $configuration->MonthlyType,
            []
        );

        $series->WithRepeatOptions($options);

        return $series;
    }

    /**
     * @return Blackout
     */
    public function CurrentBlackout()
    {
        return $this->blackouts[$this->ToKey($this->blackoutDate)];
    }

    /**
     * @param DateRange $date
     * @return string
     */
    private function ToKey(DateRange $date)
    {
        return $date->GetBegin()->Timestamp();
    }

    /**
     * @return BlackoutResource[]
     */
    public function Resources()
    {
        return $this->resources;
    }

    /**
     * @return int
     */
    public function CurrentBlackoutInstanceId()
    {
        return $this->currentBlackoutInstanceId;
    }

    /**
     * @return Blackout|false
     */
    public function NextBlackout()
    {
        if ($this->blackoutIteration == 0) {
            $this->blackouts = $this->AllBlackouts();
        }

        if ($this->blackoutIteration < count($this->blackouts)) {
            $keys = array_keys($this->blackouts);
            return $this->blackouts[$keys[$this->blackoutIteration++]];
        }

        return false;
    }

    /**
     * testing only
     */
    public function _ResetBlackoutIteration()
    {
        $this->blackoutIteration = 0;
    }
}

class Blackout
{
    /**
     * @var DateRange
     */
    protected $date;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var BlackoutSeries
     */
    protected $series;

    /**
     * @param DateRange $blackoutDate
     */
    public function __construct($blackoutDate)
    {
        $this->date = $blackoutDate;
    }

    /**
     * @return DateRange
     */
    public function Date()
    {
        return $this->date;
    }

    /**
     * @return Date
     */
    public function StartDate()
    {
        return $this->date->GetBegin();
    }

    /**
     * @return Date
     */
    public function EndDate()
    {
        return $this->date->GetEnd();
    }

    /**
     * @param DateRange $date
     */
    public function SetDate(DateRange $date)
    {
        $this->date = $date;
    }


    /**
     * @param int $id
     */
    public function WithId($id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function Id()
    {
        return $this->id;
    }

    public function SetSeries(BlackoutSeries $blackoutSeries)
    {
        $this->series = $blackoutSeries;
    }

    public function GetSeries()
    {
        return $this->series;
    }
}

class BlackoutResource implements IResource
{
    private $id;
    private $name;
    private $scheduleId;
    private $adminGroupId;
    private $scheduleAdminGroupId;
    private $statusId;

    public function __construct($id, $name, $scheduleId, $adminGroupId = null, $scheduleAdminGroupId = null, $statusId = null)
    {
        $this->id = $id;
        $this->name = $name;
        $this->scheduleId = $scheduleId;
        $this->adminGroupId = $adminGroupId;
        $this->scheduleAdminGroupId = $scheduleAdminGroupId;
        $this->statusId = $statusId;
    }

    /**
     * @return int
     */
    public function GetId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function GetName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function GetAdminGroupId()
    {
        return $this->adminGroupId;
    }

    /**
     * @return int
     */
    public function GetScheduleId()
    {
        return $this->scheduleId;
    }

    /**
     * @return int
     */
    public function GetScheduleAdminGroupId()
    {
        return $this->scheduleAdminGroupId;
    }

    /**
     * @return int
     */
    public function GetResourceId()
    {
        return $this->id;
    }


    /**
     * @return int
     */
    public function GetStatusId()
    {
        return $this->statusId;
    }
}
