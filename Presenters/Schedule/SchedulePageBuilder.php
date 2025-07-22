<?php

interface ISchedulePageBuilder
{
    /**
     * @param ISchedulePage $page
     * @param ISchedule[] $schedules
     * @param ISchedule $currentSchedule
     */
    public function BindSchedules(ISchedulePage $page, $schedules, $currentSchedule);

    /**
     * @param ISchedulePage $page
     * @param ISchedule[] $schedules
     * @param UserSession $user
     * @return Schedule
     */
    public function GetCurrentSchedule(ISchedulePage $page, $schedules, UserSession $user);

    /**
     * Returns range of dates to bind in UTC
     * @param UserSession $userSession
     * @param ISchedule $schedule
     * @param ISchedulePage $page
     * @return DateRange
     */
    public function GetScheduleDates(UserSession $userSession, ISchedule $schedule, ISchedulePage $page);

    /**
     * @param ISchedulePage $page
     * @param DateRange $dateRange display dates
     * @param ISchedule $schedule
     */
    public function BindDisplayDates(ISchedulePage $page, DateRange $dateRange, ISchedule $schedule);

    /**
     * @param UserSession $user
     * @param ISchedulePage $page
     * @param Date[] $dates
     * @param ISchedule $schedule
     */
    public function BindSpecificDates(UserSession $user, ISchedulePage $page, $dates, ISchedule $schedule);

    /**
     * @param ISchedulePage $page
     * @param ResourceDto[] $resources
     * @param IDailyLayout $dailyLayout
     */
    public function BindReservations(ISchedulePage $page, $resources, IDailyLayout $dailyLayout);

    /**
     * @param ISchedulePage $page
     * @param ResourceGroupTree $resourceGroupTree
     */
    public function BindResourceGroups(ISchedulePage $page, ResourceGroupTree $resourceGroupTree);

    /**
     * @param ISchedulePage $page
     * @param ResourceType[] $resourceTypes
     */
    public function BindResourceTypes(ISchedulePage $page, $resourceTypes);

    /**
     * @param int $scheduleId
     * @param ISchedulePage $page
     * @return int
     */
    public function GetGroupId($scheduleId, ISchedulePage $page);

    /**
     * @param int $scheduleId
     * @param ISchedulePage $page
     * @return ScheduleResourceFilter
     */
    public function GetResourceFilter($scheduleId, ISchedulePage $page);

    /**
     * @param ISchedulePage $page
     * @param ScheduleResourceFilter $filter
     * @param Attribute[] $resourceCustomAttributes
     * @param Attribute[] $resourceTypeCustomAttributes
     */
    public function BindResourceFilter(
        ISchedulePage $page,
        ScheduleResourceFilter $filter,
        $resourceCustomAttributes,
        $resourceTypeCustomAttributes
    );
}

class SchedulePageBuilder implements ISchedulePageBuilder
{
    /**
     * @param ISchedulePage $page
     * @param ISchedule[] $schedules
     * @param ISchedule $currentSchedule
     */
    public function BindSchedules(ISchedulePage $page, $schedules, $currentSchedule)
    {
        $scheduleId = $currentSchedule->GetId();
        $page->SetSchedules($schedules);
        $page->SetScheduleId($scheduleId);
        $page->SetScheduleName($currentSchedule->GetName());
        $page->SetFirstWeekday($currentSchedule->GetWeekdayStart());
        $style = $page->GetScheduleStyle($scheduleId);
        $page->SetScheduleStyle($style == "" ? $currentSchedule->GetDefaultStyle() : $style);
        if ($currentSchedule->GetIsCalendarSubscriptionAllowed()) {
            $page->SetSubscriptionUrl(new CalendarSubscriptionUrl(null, $currentSchedule->GetPublicId(), null));
        }
    }

    /**
     * @param ISchedulePage $page
     * @param ISchedule[] $schedules
     * @param UserSession $user
     * @return Schedule
     */
    public function GetCurrentSchedule(ISchedulePage $page, $schedules, UserSession $user)
    {
        $requestedScheduleId = $page->GetScheduleId();
        if (!empty($requestedScheduleId)) {
            $schedule = $this->GetSchedule($schedules, $page->GetScheduleId());
        } elseif (!empty($user->ScheduleId)) {
            $schedule = $this->GetSchedule($schedules, $user->ScheduleId);
            if ($schedule->GetId() != $user->ScheduleId) {
                $schedule = $this->GetDefaultSchedule($schedules);
            }
        } else {
            $schedule = $this->GetDefaultSchedule($schedules);
        }

        return $schedule;
    }

    public function GetScheduleDates(UserSession $user, ISchedule $schedule, ISchedulePage $page)
    {
        $userTimezone = $user->Timezone;
        $providedDate = $page->GetSelectedDate();
        $selectedDates = $page->GetSelectedDates();
        if (!empty($selectedDates)) {
            $numberOfDatesSelected = count($selectedDates);
            $first = $selectedDates[0];
            $last = $numberOfDatesSelected > 1 ? $selectedDates[$numberOfDatesSelected - 1] : $first;
            return new DateRange($first->GetDate(), $last->AddDays(1)->GetDate());
        }

        $date = empty($providedDate) ? Date::Now() : new Date($providedDate, $userTimezone);
        $selectedDate = $date
                ->ToTimezone($userTimezone)
                ->GetDate();
        $selectedWeekday = $selectedDate->Weekday();

        $scheduleLength = $schedule->GetDaysVisible();

        if ($page->GetShowFullWeek()) {
            $scheduleLength = 7;
        }

        /**
         *  Examples
         *
         *  if we are on 3 and we need to start on 6, we need to go back 4 days
         *  if we are on 3 and we need to start on 5, we need to go back 5 days
         *  if we are on 3 and we need to start on 4, we need to go back 6 days
         *  if we are on 3 and we need to start on 3, we need to go back 0 days
         *  if we are on 3 and we need to start on 2, we need to go back 1 days
         *  if we are on 3 and we need to start on 1, we need to go back 2 days
         *  if we are on 3 and we need to start on 0, we need to go back 3 days
         */

        $startDay = $schedule->GetWeekdayStart();

        if ($startDay == Schedule::Today) {
            $startDate = $selectedDate;
        } else {
            $adjustedDays = ($startDay - $selectedWeekday);

            if ($selectedWeekday < $startDay) {
                $adjustedDays = $adjustedDays - 7;
            }

            $startDate = $selectedDate->AddDays($adjustedDays);
        }

        $applicableDates = new DateRange($startDate, $startDate->AddDays($scheduleLength));

        return $applicableDates;
    }

    public function BindDisplayDates(ISchedulePage $page, DateRange $dateRange, ISchedule $schedule)
    {
        if ($schedule->HasAvailability()) {
            if ($dateRange->GetEnd()->LessThan($schedule->GetAvailabilityBegin())) {
                $page->BindScheduleAvailability($schedule->GetAvailability(), true);
            } elseif ($dateRange->GetBegin()->GreaterThan($schedule->GetAvailabilityEnd())) {
                $page->BindScheduleAvailability($schedule->GetAvailability(), false);
            }

            if ($dateRange->GetBegin()->LessThan($schedule->GetAvailabilityBegin())) {
                $dateRange = new DateRange($schedule->GetAvailabilityBegin(), $dateRange->GetEnd(), $dateRange->Timezone());
            }
            if ($dateRange->GetEnd()->GreaterThan($schedule->GetAvailabilityEnd())) {
                $dateRange = new DateRange($dateRange->GetBegin(), $schedule->GetAvailabilityEnd(), $dateRange->Timezone());
            }
        }

        $scheduleLength = $schedule->GetDaysVisible();
        if ($page->GetShowFullWeek()) {
            $scheduleLength = 7;
        }

        $page->SetDisplayDates($dateRange);

        $startDate = $dateRange->GetBegin();

        $startDay = $schedule->GetWeekdayStart();

        if ($startDay == Schedule::Today) {
            $adjustment = $scheduleLength;
            $prevAdjustment = $scheduleLength;
        } else {
            $adjustment = max($scheduleLength, 7);
            $prevAdjustment = 7 * floor($adjustment / 7); // ie, if 10, we only want to go back 7 days so there is overlap
        }

        $page->SetPreviousNextDates($startDate->AddDays(-$prevAdjustment), $startDate->AddDays($adjustment));
        $page->ShowFullWeekToggle($scheduleLength < 7);
    }

    public function BindSpecificDates(UserSession $user, ISchedulePage $page, $dates, ISchedule $schedule)
    {
        if (empty($dates)) {
            $page->SetSpecificDates([]);
            return;
        }

        $specificDates = [];

        foreach ($dates as $date) {
            $specificDates[] = Date::Parse($date, $user->Timezone);
        }
        $page->SetSpecificDates($specificDates);
    }

    /**
     * @param ISchedulePage $page
     * @param ResourceDto[] $resources
     * @param IDailyLayout $dailyLayout
     */
    public function BindReservations(ISchedulePage $page, $resources, IDailyLayout $dailyLayout)
    {
        $r = [];
        foreach ($resources as $resource) {
            if ($resource->StatusId != ResourceStatus::HIDDEN) {
                $r[] = $resource;
            }
        }
        $page->SetResources($r);
        $page->SetDailyLayout($dailyLayout);
    }

    /**
     * @param array|Schedule[] $schedules
     * @return Schedule
     */
    private function GetDefaultSchedule($schedules)
    {
        foreach ($schedules as $schedule) {
            if ($schedule->GetIsDefault()) {
                return $schedule;
            }
        }

        return $schedules[0];
    }

    /**
     * @param array|Schedule[] $schedules
     * @param int $scheduleId
     * @return Schedule
     */
    private function GetSchedule($schedules, $scheduleId)
    {
        foreach ($schedules as $schedule) {
            /** @var Schedule $schedule */
            if ($schedule->GetId() == $scheduleId) {
                return $schedule;
            }
        }

        return $schedules[0];
    }

    public function BindResourceGroups(ISchedulePage $page, ResourceGroupTree $resourceGroupTree)
    {
        $page->SetResourceGroupTree($resourceGroupTree);
    }

    public function GetGroupId($scheduleId, ISchedulePage $page)
    {
        $groupId = $page->GetGroupId();
        if (!empty($groupId)) {
            return $groupId;
        }

        $cookie = $this->getTreeCookie($scheduleId);

        if (!empty($cookie)) {
            if (strpos($cookie, '-') === false) {
                return $groupId;
            }
        }

        return null;
    }

    private function getTreeCookie($scheduleId)
    {
        $cookie = ServiceLocator::GetServer()->GetCookie('tree' . $scheduleId);
        if (!empty($cookie)) {
            $val = json_decode($cookie, true);
            return $val['selected_node'];
        }

        return null;
    }

    public function BindResourceTypes(ISchedulePage $page, $resourceTypes)
    {
        $page->SetResourceTypes($resourceTypes);
    }

    /**
     * @param int $scheduleId
     * @param ISchedulePage $page
     * @return ScheduleResourceFilter
     */
    public function GetResourceFilter($scheduleId, ISchedulePage $page)
    {
        $filter = new ScheduleResourceFilter();
        if ($page->FilterSubmitted()) {
            if ($page->FilterCleared()) {
                $filter = new ScheduleResourceFilter();
            } else {
                $filter = new ScheduleResourceFilter(
                    $scheduleId,
                    $page->GetResourceTypeId(),
                    $page->GetMaxParticipants(),
                    $this->AsAttributeValues($page->GetResourceAttributes()),
                    $this->AsAttributeValues($page->GetResourceTypeAttributes()),
                    $page->GetResourceIds()
                );
            }
        } else {
            $cookie = ServiceLocator::GetServer()->GetCookie('resource_filter' . $scheduleId);
            if (!empty($cookie)) {
                $val = json_decode($cookie);
                $filter = ScheduleResourceFilter::FromCookie($val);
            }
        }

        $resourceId = $page->GetResourceId();

        if (!empty($resourceId)) {
            $filter->ResourceIds = [$resourceId];
        }

        $filter->ScheduleId = $scheduleId;

        return $filter;
    }

    public function BindResourceFilter(
        ISchedulePage $page,
        ScheduleResourceFilter $filter,
        $resourceCustomAttributes,
        $resourceTypeCustomAttributes
    ) {
        if ($filter->ResourceAttributes != null) {
            foreach ($filter->ResourceAttributes as $attributeFilter) {
                $this->SetAttributeValue($attributeFilter, $resourceCustomAttributes);
            }
        }

        if ($filter->ResourceTypeAttributes != null) {
            foreach ($filter->ResourceTypeAttributes as $attributeFilter) {
                $this->SetAttributeValue($attributeFilter, $resourceTypeCustomAttributes);
            }
        }

        $page->SetResourceCustomAttributes($resourceCustomAttributes);
        $page->SetResourceTypeCustomAttributes($resourceTypeCustomAttributes);

        ServiceLocator::GetServer()
                      ->SetCookie(new Cookie('resource_filter' . $filter->ScheduleId, json_encode($filter)));
        $page->SetFilter($filter);
    }

    /**
     * @param $attributeFormElements AttributeFormElement[]
     * @return AttributeValue[]
     */
    private function AsAttributeValues($attributeFormElements)
    {
        $vals = [];
        foreach ($attributeFormElements as $e) {
            if (!empty($e->Value) || (is_numeric($e->Value) && $e->Value == 0)) {
                $vals[] = new AttributeValue($e->Id, $e->Value);
            }
        }
        return $vals;
    }

    /**
     * @param AttributeValue $attributeFilter
     * @param LBAttribute[] $attributes
     */
    private function SetAttributeValue($attributeFilter, $attributes)
    {
        foreach ($attributes as $attribute) {
            if ($attributeFilter->AttributeId == $attribute->Id()) {
                $attribute->SetValue($attributeFilter->Value);
                break;
            }
        }
    }
}
