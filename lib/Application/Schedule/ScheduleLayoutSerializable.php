<?php

class ScheduleLayoutSerializable
{
    /**
     * @var array|PeriodSerializable[]
     */
    public $periods;

    /**
     * @param array|SchedulePeriod[] $periods
     * @param Date|null $layoutDate the date the periods were requested for
     */
    public function __construct($periods, ?Date $layoutDate = null)
    {
        foreach ($periods as $period) {
            $this->periods[] = new PeriodSerializable($period, $layoutDate);
        }
    }
}

class PeriodSerializable
{
    public $begin;
    public $beginDate;
    public $end;
    public $endDate;
    public $label;
    public $labelEnd;
    public $isReservable;

    /**
     * @var bool false when the period began the day before the requested date
     */
    public $beginsOnDate = true;

    /**
     * @var bool false when the period runs past midnight into the day after the requested date
     */
    public $endsOnDate = true;

    public function __construct(SchedulePeriod $period, ?Date $layoutDate = null)
    {
        if ($layoutDate != null) {
            $nextMidnight = $layoutDate->GetDate()->AddDays(1);
            $this->beginsOnDate = $period->BeginDate()->DateEquals($layoutDate);
            $this->endsOnDate = $period->EndDate()->DateEquals($layoutDate) || $period->EndDate()->Equals($nextMidnight);
        }

        $this->begin = $period->Begin()->__toString();
        $this->end = $period->End()->__toString();
        $this->beginDate = $period->BeginDate()->__toString();
        $this->endDate = $period->EndDate()->__toString();
        $this->isReservable = $period->IsReservable();
        $this->label = $period->Label();
        $this->labelEnd = $period->LabelEnd();
    }
}
