<?php

require_once(ROOT_DIR . 'Presenters/ActionPresenter.php');
require_once(ROOT_DIR . 'Pages/SearchAvailabilityPage.php');
require_once(ROOT_DIR . 'Presenters/Reservation/ReservationPresenterFactory.php');

class SearchAvailabilityPresenter extends ActionPresenter
{
    /**
     * @var ISearchAvailabilityPage
     */
    private $page;
    /**
     * @var IResourceService
     */
    private $resourceService;
    /**
     * @var IReservationService
     */
    private $reservationService;

    /**
     * @var UserSession
     */
    private $user;
    /**
     * @var IScheduleService
     */
    private $scheduleService;

    /**
     * @var ScheduleLayout[]
     */
    private $_layouts = [];

    public function __construct(
        ISearchAvailabilityPage $page,
        UserSession $user,
        IResourceService $resourceService,
        IReservationService $reservationService,
        IScheduleService $scheduleService
    ) {
        parent::__construct($page);

        $this->page = $page;
        $this->user = $user;
        $this->resourceService = $resourceService;
        $this->reservationService = $reservationService;
        $this->scheduleService = $scheduleService;

        $this->AddAction('search', 'SearchAvailability');
    }

    public function PageLoad()
    {
        $this->page->SetResources($this->resourceService->GetAllResources(false, $this->user));
        $this->page->SetResourceTypes($this->resourceService->GetResourceTypes());
        $this->page->SetResourceAttributes($this->resourceService->GetResourceAttributes());
        $this->page->SetResourceTypeAttributes($this->resourceService->GetResourceTypeAttributes());
    }

    public function SearchAvailability()
    {
        $openings = [];
        $dateRange = $this->GetSearchRange();
        $specificTime = $this->page->SearchingSpecificTime();

        $timezone = $this->user->Timezone;
        if (!$specificTime) {
            $requestedLength = $this->GetRequestedLength();
            $startTime = null;
        } else {
            $startTime = Time::Parse($this->page->GetStartTime(), $timezone);
            $endTime = Time::Parse($this->page->GetEndTime(), $timezone);

            $now = Date::Now()->ToTimezone($timezone);
            $requestedLength = DateDiff::BetweenDates($now->SetTimeString($startTime), $now->SetTimeString($endTime));
        }

        $resources = $this->resourceService->GetAllResources(false, $this->user, $this->GetFilter(), null, 100);
        $roFactory = new RepeatOptionsFactory();

        $repeatOptions = $roFactory->CreateFromComposite($this->page, $timezone);
        $repeatDates = $repeatOptions->GetDates($dateRange);

        /** @var ResourceDto $resource */
        foreach ($resources as $resource) {
            $scheduleId = $resource->GetScheduleId();
            $resourceId = $resource->GetResourceId();
            $searchingForMoreThan24Hours = $requestedLength->TotalSeconds() > 86400;
            $reservations = $this->reservationService->Search($dateRange, $scheduleId, [$resourceId]);

            $targetTimezone = $timezone;
            $layout = $this->GetLayout($dateRange, $scheduleId, $targetTimezone, $resourceId);

            foreach ($dateRange->Dates() as $date) {
                if ($searchingForMoreThan24Hours) {
                    $endDate = $date->ApplyDifference($requestedLength);
                    if ($endDate->LessThanOrEqual($dateRange->GetEnd())) {
                        $slotRange = new DateRange($date, $endDate);
                        $slots = [];
                        /** @var PotentialSlot[] $potentialSlots */
                        $potentialSlots = [];
                        foreach ($slotRange->Dates() as $slotDate) {
                            $slots = array_merge($slots, $layout->GetLayout($slotDate));
                        }

                        foreach ($slots as $slot) {
                            $potentialSlot = new PotentialSlot($slot);
                            foreach ($reservations as $reservation) {
                                $potentialSlot->AddReservedItem($reservation);
                            }

                            $potentialSlots[] = $potentialSlot;
                        }

                        for ($i = 0; $i < count($potentialSlots); $i++) {
                            $opening = $this->GetSlot($i, $i, $potentialSlots, $requestedLength, $resource);

                            if ($opening != null) {
                                $openings[] = $opening;
                            }
                        }
                    }
                } else {
                    /** @var PotentialSlot[] $potentialSlots */
                    $potentialSlots = [];
                    $slots = $layout->GetLayout($date);
                    foreach ($slots as $slot) {
                        $potentialSlot = new PotentialSlot($slot);
                        foreach ($reservations as $reservation) {
                            $potentialSlot->AddReservedItem($reservation);
                        }

                        $potentialSlots[] = $potentialSlot;
                    }

                    for ($i = 0; $i < count($potentialSlots); $i++) {
                        if (is_null($startTime) || $startTime->Equals($potentialSlots[$i]->BeginDate()->GetTime())) {
                            $opening = $this->GetSlot($i, $i, $potentialSlots, $requestedLength, $resource);

                            if ($opening != null) {
                                if ($this->AllDaysAreOpen($opening, $repeatDates, $resource, $requestedLength)) {
                                    $openings[] = $opening;
                                }
                            }
                        }
                    }
                }
            }
        }

        Log::Debug('Searching for available openings found %s times', count($openings));

        $this->page->ShowOpenings($openings);
    }

    /**
     * @param int $startIndex
     * @param int $currentIndex
     * @param PotentialSlot[] $potentialSlots
     * @param DateDiff $requestedLength
     * @param ResourceDto $resource
     * @return AvailableOpeningView|null
     */
    private function GetSlot($startIndex, $currentIndex, $potentialSlots, $requestedLength, $resource)
    {
        if ($currentIndex >= count($potentialSlots)) {
            return null;
        }

        $startSlot = $potentialSlots[$startIndex];
        $currentSlot = $potentialSlots[$currentIndex];

        if ($currentSlot == null || !$currentSlot->IsReservable() || $currentSlot->BeginDate()->LessThan(Date::Now())) {
            return null;
        }

        $length = DateDiff::BetweenDates($startSlot->BeginDate(), $currentSlot->EndDate());
        if ($length->GreaterThanOrEqual($requestedLength) && $this->SlotRangeHasAvailability($startIndex, $currentIndex, $potentialSlots, $resource)) {
            return new AvailableOpeningView($resource, $startSlot->BeginDate(), $currentSlot->EndDate());
        }

        return $this->GetSlot($startIndex, $currentIndex + 1, $potentialSlots, $requestedLength, $resource);
    }

    /**
     * @return DateRange
     */
    private function GetSearchRange()
    {
        $range = $this->page->GetRequestedRange();
        $timezone = $this->user->Timezone;

        $today = Date::Now()->ToTimezone($timezone);

        if ($range == 'tomorrow') {
            return new DateRange($today->AddDays(1)->GetDate(), $today->AddDays(2)->GetDate());
        }

        if ($range == 'thisweek') {
            $weekday = $today->Weekday();
            $adjustedDays = (0 - $weekday);

            if ($weekday < 0) {
                $adjustedDays = $adjustedDays - 7;
            }

            $startDate = $today->AddDays($adjustedDays)->GetDate();

            return new DateRange($startDate, $startDate->AddDays(7));
        }

        if ($range == 'daterange') {
            $start = $this->page->GetRequestedStartDate();
            $end = $this->page->GetRequestedEndDate();

            //CHECKS IF ANY OF THE FIELDS IS EMPTY OR IF THEY ARE EQUAL
            $notEmptyButEqual = !empty($start) && !empty($end) && $start == $end;
            $emptyEnd = !empty($start) && empty($end);
            $emptyStart = empty($start) && !empty($end);
            $bothEmpty = empty($start) && empty($end);

            //IF THE END FIELD IS EMPTY OR THE BOTH FIELDS SELECTED ARE EQUAL, IT RETURNS ONLY THE SLOTS SPECIFIED BY THE DAY OF THE START FIELD
            if ($notEmptyButEqual || $emptyEnd) {
                $start = Date::Parse($start, $timezone);
                $end = $start->AddDays(1);
                return new DateRange($start, $end);
            }

            //IF THE START FIELD IS EMPTY, IT RETURNS THE ALL THE SLOTS FROM THE PRESENT TILL THE DAY SPECIFIED BY THE END FIELD
            if ($emptyStart) {
                $end = Date::Parse($end, $timezone)->AddDays(1);
                $start = $today->GetDate();
                return new DateRange($start, $end);
            }

            //IF BOTH ARE EMPTY, IT RETURNS THE SLOTS FROM TODAY
            if ($bothEmpty) {
                return new DateRange($today->GetDate(), $today->AddDays(1)->GetDate());
            }

            //IF THE FIELDS AREN'T EQUAL OR ANY OF THEM IS EMPTY RETURNS THE SPECIFIED RANGE (INCLUDING START AND END DATE DAYS)
            return new DateRange(Date::Parse($start, $timezone), Date::Parse($end, $timezone)->AddDays(1));
        }

        return new DateRange($today->GetDate(), $today->AddDays(1)->GetDate());
    }

    /**
     * @return DateDiff
     */
    private function GetRequestedLength()
    {
        $hourSeconds = 3600 * $this->page->GetRequestedHours();
        $minuteSeconds = 60 * $this->page->GetRequestedMinutes();
        return new DateDiff($hourSeconds + $minuteSeconds);
    }

    /**
     * @return ScheduleResourceFilter
     */
    private function GetFilter()
    {
        return new ScheduleResourceFilter(
            null,
            $this->page->GetResourceType(),
            $this->page->GetMaxParticipants(),
            $this->AsAttributeValues($this->page->GetResourceAttributeValues()),
            $this->AsAttributeValues($this->page->GetResourceTypeAttributeValues()),
            $this->page->GetResources()
        );
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
     * @param AvailableOpeningView $availableOpening
     * @param DateRange[] $repeatDates
     * @param ResourceDto $resource
     * @param DateDiff $requestedLength
     * @return bool
     */
    private function AllDaysAreOpen(AvailableOpeningView $availableOpening, $repeatDates, ResourceDto $resource, $requestedLength)
    {
        if (empty($repeatDates)) {
            return true;
        }

        $targetTimezone = $this->user->Timezone;
        $resourceId = $resource->GetResourceId();
        $scheduleId = $resource->GetScheduleId();

        foreach ($repeatDates as $dateRange) {
            $layout = $this->GetLayout($dateRange, $scheduleId, $targetTimezone, $resourceId);
            $foundMatch = false;
            $reservations = $this->reservationService->Search($dateRange, $scheduleId, [$resourceId]);

            foreach ($dateRange->Dates() as $date) {
                /** @var PotentialSlot[] $potentialSlots */
                $potentialSlots = [];
                $slots = $layout->GetLayout($date);
                foreach ($slots as $slot) {
                    $potentialSlot = new PotentialSlot($slot);
                    foreach ($reservations as $reservation) {
                        $potentialSlot->AddReservedItem($reservation);
                    }

                    $potentialSlots[] = $potentialSlot;
                }

                for ($i = 0; $i < count($potentialSlots); $i++) {
                    //	if ($potentialSlots[$i]->BeginDate()->CompareTimes($availableOpening->Start()->GetTime()) == 0)
                    {
                        $opening = $this->GetSlot($i, $i, $potentialSlots, $requestedLength, $resource);
                        if ($opening != null) {
                            $foundMatch = true;
                        }
                    }
                }

                if (!$foundMatch) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * @param $dateRange
     * @param $scheduleId
     * @param $targetTimezone
     * @param $resourceId
     * @return IScheduleLayout
     */
    private function GetLayout($dateRange, $scheduleId, $targetTimezone, $resourceId)
    {
        $layout = $this->GetCachedLayout($dateRange, $scheduleId, $resourceId);
        if ($layout == null) {
            $layout = $this->scheduleService->GetLayout($scheduleId, new ScheduleLayoutFactory($targetTimezone));
            $this->SetCachedLayout($dateRange, $scheduleId, $resourceId, $layout);
        }

        return $layout;
    }

    /**
     * @param DateRange $dateRange
     * @param int $scheduleId
     * @param int $resourceId
     * @return IScheduleLayout|null
     */
    private function GetCachedLayout($dateRange, $scheduleId, $resourceId)
    {
        $key = $dateRange->ToString() . $scheduleId . $resourceId;
        if (array_key_exists($key, $this->_layouts)) {
            return $this->_layouts[$key];
        }

        return null;
    }

    /**
     * @param DateRange $dateRange
     * @param int $scheduleId
     * @param int $resourceId
     * @param IScheduleLayout $layout
     */
    private function SetCachedLayout($dateRange, $scheduleId, $resourceId, $layout)
    {
        $this->_layouts[$dateRange->ToString() . $scheduleId . $resourceId] = $layout;
    }

    /**
     * @param int $startIndex
     * @param int $currentIndex
     * @param PotentialSlot[] $potentialSlots
     * @param ResourceDto $resource
     * @return bool
     */
    private function SlotRangeHasAvailability($startIndex, $currentIndex, $potentialSlots, $resource)
    {
        for ($i = $startIndex; $i <= $currentIndex; $i++) {
            if (!$potentialSlots[$i]->IsReservable() || $potentialSlots[$i]->ReservationCount() >= $resource->GetMaxConcurrentReservations()) {
                return false;
            }
        }

        return true;
    }
}

class PotentialSlot
{
    /**
     * @var SchedulePeriod
     */
    private $slot;
    /**
     * @var int
     */
    private $reservationCount = 0;
    /**
     * @var bool
     */
    private $isBlackout = false;

    public function __construct(SchedulePeriod $slot)
    {
        $this->slot = $slot;
        $this->isBlackout = !$slot->IsReservable();
    }

    public function AddReservedItem(ReservationListItem $item)
    {
        if (!$item->IsReservation()) {
            $this->isBlackout = true;
            return;
        }

        if ($item->CollidesWithRange(new DateRange($this->slot->BeginDate(), $this->slot->EndDate()))) {
            $this->reservationCount++;
        }
    }

    public function IsReservable()
    {
        return !$this->isBlackout;
    }

    public function BeginDate()
    {
        return $this->slot->BeginDate();
    }

    public function EndDate()
    {
        return $this->slot->EndDate();
    }

    public function ReservationCount()
    {
        return $this->reservationCount;
    }
}

class AvailableOpeningView
{
    /**
     * @var ResourceDto
     */
    private $resource;
    /**
     * @var Date
     */
    private $start;
    /**
     * @var Date
     */
    private $end;

    public function __construct(ResourceDto $resource, Date $start, Date $end)
    {
        $this->resource = $resource;
        $this->start = $start;
        $this->end = $end;
    }

    /**
     * @return ResourceDto
     */
    public function Resource()
    {
        return $this->resource;
    }

    /**
     * @return Date
     */
    public function Start()
    {
        return $this->start;
    }

    /**
     * @return Date
     */
    public function End()
    {
        return $this->end;
    }

    /**
     * @return bool
     */
    public function SameDate()
    {
        return $this->Start()->DateEquals($this->End());
    }
}
