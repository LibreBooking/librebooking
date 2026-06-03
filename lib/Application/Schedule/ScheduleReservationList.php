<?php

interface IScheduleReservationList
{
    /**
     * @return array|IReservationSlot[]
     */
    public function BuildSlots();
}

class ScheduleReservationList implements IScheduleReservationList
{
    /**
     * @var array|ReservationListItem[]
     */
    private $_items;

    /**
     * @var IScheduleLayout
     */
    private $_layout;

    /**
     * @var Date
     */
    private $_layoutDateStart;

    /**
     * @var Date
     */
    private $_layoutDateEnd;

    /**
     * @var array|SchedulePeriod[]
     */
    private $_layoutItems;

    private $_itemsByStartTime = [];

    /**
     * @var array|SchedulePeriod[]
     */
    private $_layoutByStartTime = [];

    /**
     * @var array|int[]
     */
    private $_layoutIndexByEndTime = [];

    /**
     * @var Time
     */
    private $_midnight;

    /**
     * @var LayoutIndexCache
     */
    private $layoutIndexCache;

    /**
     * @var string
     */
    private $_destinationTimezone;

    /**
     * @var Date
     */
    private $_firstLayoutTime;

    /**
     * @var Date
     */
    private $_lastLayoutTime;

    /**
     * @param array|ReservationListItem[] $items
     * @param IScheduleLayout $layout
     * @param Date $layoutDate
     * @param bool $hideBlockedPeriods
     */
    public function __construct($items, IScheduleLayout $layout, Date $layoutDate, $hideBlockedPeriods = false)
    {
        $this->_items = $items;
        $this->_layout = $layout;
        $this->_destinationTimezone = $this->_layout->Timezone();
        $this->_layoutDateStart = $layoutDate->ToTimezone($this->_destinationTimezone)->GetDate();
        $this->_layoutDateEnd = $this->_layoutDateStart->AddDays(1);
        $this->_layoutItems = $this->_layout->GetLayout($layoutDate, $hideBlockedPeriods);
        $this->_midnight = new Time(0, 0, 0, $this->_destinationTimezone);

        $this->layoutIndexCache = new LayoutIndexCache();

        $this->IndexLayout();
        $this->IndexItems();
    }

    public function BuildSlots()
    {
        $sw = StopWatch::StartNew();

        $slots = [];

        for ($currentIndex = 0; $currentIndex < count($this->_layoutItems); $currentIndex++) {
            $layoutItem = $this->_layoutItems[$currentIndex];
            $item = $this->GetItemStartingAt($layoutItem->BeginDate());

            if ($item != null) {
                if ($this->ItemEndsOnFutureDate($item)) {
                    $endTime = $this->_layoutDateEnd;
                } else {
                    $endTime = $item->EndDate()->ToTimezone($this->_destinationTimezone);
                }

                $endingPeriodIndex = max($this->GetLayoutIndexEndingAt($endTime), $currentIndex);
                $span = ($endingPeriodIndex - $currentIndex) + 1;

                $slots[] = $item->BuildSlot(
                    $layoutItem,
                    $this->_layoutItems[$endingPeriodIndex],
                    $this->_layoutDateStart,
                    $span
                );

                $currentIndex = $endingPeriodIndex;
            } else {
                $slots[] = new EmptyReservationSlot($layoutItem, $layoutItem, $this->_layoutDateStart, $layoutItem->IsReservable());
            }
        }

        $sw->Stop();
        //		Log::Debug('BuildSlots() took %s seconds', $sw->GetTotalSeconds());

        return $slots;
    }

    private function IndexItems()
    {
        $sw = StopWatch::StartNew();

        foreach ($this->_items as $index => $item) {
            if ($item->HasBufferTime()) {
                $bufferItem = new BufferItem($item, BufferItem::LOCATION_BEFORE);
                if (!$this->Collides($bufferItem, $index)) {
                    $this->IndexItem($bufferItem);
                }
            }

            $this->IndexItem($item);

            if ($item->HasBufferTime()) {
                $bufferItem = new BufferItem($item, BufferItem::LOCATION_AFTER);
                if (!$this->Collides($bufferItem, $index)) {
                    $this->IndexItem($bufferItem);
                }
            }
        }

        $sw->Stop();
        //		Log::Debug('IndexItems() took %s seconds', $sw->GetTotalSeconds());
    }

    private function IndexItem(ReservationListItem $item)
    {
        if (($item->StartDate()->Compare($this->_lastLayoutTime) >= 0) ||
                ($item->EndDate()->Compare($this->_firstLayoutTime) <= 0)) {
            // skip the item if it starts after this layout or ends before it
            return;
        }

        $start = $item->StartDate()->ToTimezone($this->_destinationTimezone);

        $startsInPast = $this->ItemStartsOnPastDate($item);
        if ($startsInPast) {
            $start = $this->_firstLayoutTime;
        } elseif ($this->ItemIsNotOnLayoutBoundary($item)) {
            $layoutItem = $this->FindClosestLayoutIndexBeforeStartingTime($item);
            if (!empty($layoutItem)) {
                $start = $layoutItem->BeginDate()->ToTimezone($this->_destinationTimezone);
            }
        }

        $this->_itemsByStartTime[$start->Timestamp()] = $item;
    }

    private function ItemStartsOnPastDate(ReservationListItem $item)
    {
        //Log::Debug("PAST");
        return $item->StartDate()->Compare($this->_layoutDateStart) <= 0;
    }

    private function ItemEndsOnFutureDate(ReservationListItem $item)
    {
        //Log::Debug("%s %s %s", $reservation->GetReferenceNumber(), $reservation->GetEndDate()->GetDate(), $this->_layoutDateEnd->GetDate());
        return $item->EndDate()->Compare($this->_layoutDateEnd) >= 0;
    }

    private function IndexLayout()
    {
        $sw = StopWatch::StartNew();

        if (!$this->layoutIndexCache->Contains($this->_layoutDateStart)) {
            $this->layoutIndexCache->Add(
                $this->_layoutDateStart,
                $this->_layoutItems,
                $this->_layoutDateStart,
                $this->_layoutDateEnd
            );
        }
        $cachedIndex = $this->layoutIndexCache->Get($this->_layoutDateStart);
        $this->_firstLayoutTime = $cachedIndex->GetFirstLayoutTime();
        $this->_lastLayoutTime = $cachedIndex->GetLastLayoutTime();
        $this->_layoutByStartTime = $cachedIndex->LayoutByStartTime();
        $this->_layoutIndexByEndTime = $cachedIndex->LayoutIndexByEndTime();

        $sw->Stop();
        //		Log::Debug('IndexLayout() took %s seconds', $sw->GetTotalSeconds());
    }

    /**
     * @param Date $endingTime
     * @return int index of $_layoutItems which has the corresponding $endingTime
     */
    private function GetLayoutIndexEndingAt(Date $endingTime)
    {
        $timeKey = $endingTime->Timestamp();

        if (array_key_exists($timeKey, $this->_layoutIndexByEndTime)) {
            return $this->_layoutIndexByEndTime[$timeKey];
        }

        return $this->FindClosestLayoutIndexBeforeEndingTime($endingTime);
    }

    /**
     * @param Date $beginTime
     * @return ReservationListItem
     */
    private function GetItemStartingAt(Date $beginTime)
    {
        $timeKey = $beginTime->Timestamp();
        if (array_key_exists($timeKey, $this->_itemsByStartTime)) {
            return $this->_itemsByStartTime[$timeKey];
        }
        return null;
    }

    /**
     * @param Date $endingTime
     * @return int index of $_layoutItems which has the closest ending time to $endingTime without going past it
     */
    private function FindClosestLayoutIndexBeforeEndingTime(Date $endingTime)
    {
        for ($i = count($this->_layoutItems) - 1; $i >= 0; $i--) {
            $currentItem = $this->_layoutItems[$i];

            if ($currentItem->BeginDate()->LessThan($endingTime)) {
                return $i;
            }
        }

        return 0;
    }

    /**
     * @param ReservationListItem $item
     * @return SchedulePeriod which has the closest starting time to $endingTime without going prior to it
     */
    private function FindClosestLayoutIndexBeforeStartingTime(ReservationListItem $item)
    {
        for ($i = count($this->_layoutItems) - 1; $i >= 0; $i--) {
            $currentItem = $this->_layoutItems[$i];

            if ($currentItem->BeginDate()->LessThan($item->StartDate())) {
                return $currentItem;
            }
        }

        if ($item->StartDate()->LessThan($this->_layoutItems[0]->BeginDate())) {
            return $this->_layoutItems[0];
        }

        Log::Error('Could not find a fitting starting slot for reservation. Item %s', var_export($item, true));
        return null;
    }

    /**
     * @param ReservationListItem $item
     * @return bool
     */
    private function ItemIsNotOnLayoutBoundary(ReservationListItem $item)
    {
        $timeKey = $item->StartDate()->Timestamp();
        return !(array_key_exists($timeKey, $this->_layoutByStartTime));
    }

    private function Collides(ReservationListItem $item, $itemIndex)
    {
        $previousItem = $itemIndex > 0 ? $this->_items[--$itemIndex] : null;
        $nextItem = $itemIndex < count($this->_items) - 1 ? $this->_items[++$itemIndex] : null;

        $itemDateRange = new DateRange($item->StartDate(), $item->EndDate());
        if ($previousItem != null) {
            if ($itemDateRange->Overlaps(new DateRange($previousItem->StartDate(), $previousItem->EndDate()))) {
                return true;
            }
        }

        if ($nextItem != null) {
            if ($itemDateRange->Overlaps(new DateRange($nextItem->StartDate(), $nextItem->EndDate()))) {
                return true;
            }
        }

        return false;
    }
}

class LayoutIndexCache
{
    /**
     * @var CachedLayoutIndex[]
     */
    private $_cache = [];

    /**
     * @param Date $date
     * @return bool
     */
    public function Contains(Date $date)
    {
        return array_key_exists($date->Timestamp(), $this->_cache);
    }

    /**
     * @param Date $date
     * @param SchedulePeriod[] $schedulePeriods
     * @param Date $startDate
     * @param Date $endDate
     */
    public function Add(Date $date, $schedulePeriods, Date $startDate, Date $endDate)
    {
        $this->_cache[$date->Timestamp()] = new CachedLayoutIndex($schedulePeriods, $startDate, $endDate);
    }

    public function Get(Date $date)
    {
        return $this->_cache[$date->Timestamp()];
    }

    public function Clear()
    {
        $this->_cache = [];
    }
}

class CachedLayoutIndex
{
    private $_firstLayoutTime;
    private $_lastLayoutTime;
    private $_layoutByStartTime = [];
    private $_layoutIndexByEndTime = [];

    /**
     * @param SchedulePeriod[] $schedulePeriods
     * @param Date $startDate
     * @param Date $endDate
     */
    public function __construct($schedulePeriods, Date $startDate, Date $endDate)
    {
        $this->_firstLayoutTime = $endDate;
        $this->_lastLayoutTime = $startDate;

        for ($i = 0; $i < count($schedulePeriods); $i++) {
            /** @var Date $itemBegin */
            $itemBegin = $schedulePeriods[$i]->BeginDate();
            $itemEnd = $schedulePeriods[$i]->EndDate();
            if ($itemBegin->LessThan($this->_firstLayoutTime)) {
                $this->_firstLayoutTime = $itemBegin;
            }
            if ($itemEnd->GreaterThan($this->_lastLayoutTime)) {
                $this->_lastLayoutTime = $itemEnd;
            }

            /** @var Date $endTime */
            $endTime = $schedulePeriods[$i]->EndDate();
            if (!$schedulePeriods[$i]->EndDate()->DateEquals($startDate)) {
                $endTime = $endDate;
            }

            $this->_layoutByStartTime[$itemBegin->Timestamp()] = $schedulePeriods[$i];
            $this->_layoutIndexByEndTime[$endTime->Timestamp()] = $i;
        }
    }

    public function GetFirstLayoutTime()
    {
        return $this->_firstLayoutTime;
    }

    public function GetLastLayoutTime()
    {
        return $this->_lastLayoutTime;
    }

    public function LayoutByStartTime()
    {
        return $this->_layoutByStartTime;
    }

    public function LayoutIndexByEndTime()
    {
        return $this->_layoutIndexByEndTime;
    }
}
