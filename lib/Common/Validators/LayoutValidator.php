<?php

class LayoutValidator extends ValidatorBase implements IValidator
{
    /**
     * @var string|string[]
     */
    private $reservableSlots;

    /**
     * @var string|string[]
     */
    private $blockedSlots;

    /**
     * @var bool
     */
    private $validateSingle;

    /**
     * @param string|string[] $reservableSlots
     * @param string|string[] $blockedSlots
     * @param bool $validateSingle
     */
    public function __construct($reservableSlots, $blockedSlots, $validateSingle = true)
    {
        $this->reservableSlots = $reservableSlots;
        $this->blockedSlots = $blockedSlots;
        $this->validateSingle = $validateSingle;
    }

    /**
     * @return void
     */
    public function Validate()
    {
        try {
            $this->isValid = true;

            $days = [null];

            if (!$this->validateSingle) {
                Log::Debug('Validating daily layout');
                if (count($this->reservableSlots) != DayOfWeek::NumberOfDays || count($this->blockedSlots) != DayOfWeek::NumberOfDays) {
                    $this->isValid = false;
                    return;
                }
                $layout = ScheduleLayout::ParseDaily('UTC', $this->reservableSlots, $this->blockedSlots);
                $days = DayOfWeek::Days();
            } else {
                Log::Debug('Validating single layout');
                $layout = ScheduleLayout::Parse('UTC', $this->reservableSlots, $this->blockedSlots);
            }

            foreach ($days as $day) {
                if (is_null($day)) {
                    $day = 0;
                }
                $slots = $layout->GetLayout(Date::Now()->AddDays($day)->ToUtc());

                /** @var Date $firstDate */
                $firstDate = $slots[0]->BeginDate();
                /** @var Date $lastDate */
                $lastDate = $slots[count($slots) - 1]->EndDate();
                if (!$firstDate->IsMidnight() || !$lastDate->IsMidnight()) {
                    Log::Debug('Dates are not midnight');
                    $this->isValid = false;
                }

                if (count($slots) == 0 && $slots[0]->BeginDate()->IsMidnight() && $slots[0]->EndDate()->IsMidnight()) {
                    Log::Debug('Both dates are midnight');
                    $this->isValid = true;
                    return;
                }

                for ($i = 0; $i < count($slots) - 1; $i++) {
                    if (!$slots[$i]->EndDate()->Equals($slots[$i + 1]->BeginDate())) {
                        $this->isValid = false;
                    }
                }
            }
        } catch (Exception $ex) {
            Log::Error('Error during LayoutValidator %s', $ex);
            $this->isValid = false;
        }
    }
}
