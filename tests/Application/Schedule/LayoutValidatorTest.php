<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/Validators/namespace.php');

class LayoutValidatorTest extends TestBase
{
    public function testInvalidUnlessThereIsASlotCoveringEveryMinuteForTheWholeDay()
    {
        $missingMiddleHour = new LayoutValidator("00:00-08:00\n08:00-14:00\n15:00-21:00", '21:00-00:00');
        $startsOffMidnight = new LayoutValidator('08:00-21:00', '21:00-00:00');
        $endsOffMidnight = new LayoutValidator("00:00-15:00\n21:00-23:30", '15:00-21:00');
        $unableToParse = new LayoutValidator("00-15:00\n21:00-23:30", '15:00-21:00');
        $overlap = new LayoutValidator("00:00-15:00\n21:00-00:00", '14:00-21:00');

        $missingMiddleHour->Validate();
        $startsOffMidnight->Validate();
        $endsOffMidnight->Validate();
        $unableToParse->Validate();
        $overlap->Validate();

        $this->assertFalse($missingMiddleHour->IsValid());
        $this->assertFalse($startsOffMidnight->IsValid());
        $this->assertFalse($endsOffMidnight->IsValid());
        $this->assertFalse($unableToParse->IsValid());
        $this->assertFalse($overlap->IsValid());
    }

    public function testEmptyLayoutIsInvalid(): void
    {
        $empty = new LayoutValidator(reservableSlots: '', blockedSlots: '');
        $blankLines = new LayoutValidator(reservableSlots: "\n", blockedSlots: "\n");

        $empty->Validate();
        $blankLines->Validate();

        $this->assertFalse($empty->IsValid());
        $this->assertFalse($blankLines->IsValid());
    }

    public function testValidWhenFullDayIsProvided()
    {
        $v1 = new LayoutValidator("00:00-08:00\n08:00-14:00\n14:30-21:00", "21:00-00:00\n14:00-14:30");
        $v2 = new LayoutValidator("00:00-10:00\n10:30-21:00", "10:00-10:30\n21:00-00:00");
        $v3 = new LayoutValidator("00:00-00:30\n21:00-23:30", "00:30-21:00\n23:30-00:00");
        $v4 = new LayoutValidator("08:00 - 08:30\n08:30 - 09:00\n09:00 - 09:30\n09:30 - 10:00\n10:00 - 10:30\n10:30 - 11:00\n11:00 - 11:30\n11:30 - 12:00\n12:00 - 12:30\n12:30 - 13:00\n13:00 - 13:30\n13:30 - 14:00\n14:00 - 14:30\n14:30 - 15:00\n15:00 - 15:30\n15:30 - 16:00\n16:00 - 16:30\n16:30 - 17:00\n17:00 - 17:30\n17:30 - 18:00", "00:00-08:00\n18:00-00:00");
        $v5 = new LayoutValidator('00:00-00:00', '');
        $v6 = new LayoutValidator('', '00:00-00:00');

        $v1->Validate();
        $v2->Validate();
        $v3->Validate();
        $v4->Validate();
        $v5->Validate();
        $v6->Validate();

        $this->assertTrue($v1->IsValid());
        $this->assertTrue($v2->IsValid());
        $this->assertTrue($v3->IsValid());
        $this->assertTrue($v4->IsValid());
        $this->assertTrue($v5->IsValid());
        $this->assertTrue($v6->IsValid());
    }

    public function testInvalidUnlessThereIsASlotCoveringEveryMinuteForTheWholeDayForWholeWeek()
    {
        $partialWeek = $this->toWeek('00:00-00:00');
        unset($partialWeek[3]);

        $missingDay = new LayoutValidator($partialWeek, '', false);
        $missingMiddleHour = new LayoutValidator($this->toWeek("00:00-08:00\n08:00-14:00\n15:00-21:00"), $this->toWeek('21:00-00:00'), false);
        $startsOffMidnight = new LayoutValidator($this->toWeek('08:00-21:00'), $this->toWeek('21:00-00:00'), false);
        $endsOffMidnight = new LayoutValidator($this->toWeek("00:00-15:00\n21:00-23:30"), $this->toWeek('15:00-21:00'), false);
        $unableToParse = new LayoutValidator($this->toWeek("00-15:00\n21:00-23:30"), $this->toWeek('15:00-21:00'), false);
        $overlap = new LayoutValidator($this->toWeek("00:00-15:00\n21:00-00:00"), $this->toWeek('14:00-21:00'), false);

        $missingDay->Validate();
        $missingMiddleHour->Validate();
        $startsOffMidnight->Validate();
        $endsOffMidnight->Validate();
        $unableToParse->Validate();
        $overlap->Validate();

        $this->assertFalse($missingDay->IsValid());
        $this->assertFalse($missingMiddleHour->IsValid());
        $this->assertFalse($startsOffMidnight->IsValid());
        $this->assertFalse($endsOffMidnight->IsValid());
        $this->assertFalse($unableToParse->IsValid());
        $this->assertFalse($overlap->IsValid());
    }

    public function testValidWhenFullDayIsProvidedForWholeWeek()
    {
        $v1 = new LayoutValidator($this->toWeek("00:00-08:00\n08:00-14:00\n14:30-21:00"), $this->toWeek("21:00-00:00\n14:00-14:30"), false);
        $v2 = new LayoutValidator($this->toWeek("00:00-10:00\n10:30-21:00"), $this->toWeek("10:00-10:30\n21:00-00:00"), false);
        $v3 = new LayoutValidator($this->toWeek("00:00-00:30\n21:00-23:30"), $this->toWeek("00:30-21:00\n23:30-00:00"), false);
        $v4 = new LayoutValidator($this->toWeek("08:00 - 08:30\n08:30 - 09:00\n09:00 - 09:30\n09:30 - 10:00\n10:00 - 10:30\n10:30 - 11:00\n11:00 - 11:30\n11:30 - 12:00\n12:00 - 12:30\n12:30 - 13:00\n13:00 - 13:30\n13:30 - 14:00\n14:00 - 14:30\n14:30 - 15:00\n15:00 - 15:30\n15:30 - 16:00\n16:00 - 16:30\n16:30 - 17:00\n17:00 - 17:30\n17:30 - 18:00"), $this->toWeek("00:00-08:00\n18:00-00:00"), false);

        $v1->Validate();
        $v2->Validate();
        $v3->Validate();
        $v4->Validate();

        $this->assertTrue($v1->IsValid());
        $this->assertTrue($v2->IsValid());
        $this->assertTrue($v3->IsValid());
        $this->assertTrue($v4->IsValid());
    }

    public function testSingleLayoutMayHaveASlotThatRunsPastMidnight(): void
    {
        $reservableOvernight = new LayoutValidator(reservableSlots: '22:00-06:00', blockedSlots: '06:00-22:00');
        $blockedOvernight = new LayoutValidator(reservableSlots: "06:00-14:00\n14:00-22:00", blockedSlots: '22:00-06:00');
        $splitNight = new LayoutValidator(reservableSlots: "20:00-23:00\n23:00-02:00\n02:00-05:00", blockedSlots: '05:00-20:00');

        $reservableOvernight->Validate();
        $blockedOvernight->Validate();
        $splitNight->Validate();

        $this->assertTrue($reservableOvernight->IsValid());
        $this->assertTrue($blockedOvernight->IsValid());
        $this->assertTrue($splitNight->IsValid());
    }

    public function testSingleLayoutWithOvernightSlotStillHasToCoverTheWholeDay(): void
    {
        $gapAfterOvernight = new LayoutValidator(reservableSlots: '22:00-06:00', blockedSlots: '07:00-22:00');
        $overlapsOvernight = new LayoutValidator(reservableSlots: '22:00-06:00', blockedSlots: '00:00-22:00');
        $twoOvernight = new LayoutValidator(reservableSlots: '22:00-06:00', blockedSlots: '06:00-22:00' . "\n" . '23:00-05:00');
        $sameStartAndEnd = new LayoutValidator(reservableSlots: '22:00-22:00', blockedSlots: '');

        $gapAfterOvernight->Validate();
        $overlapsOvernight->Validate();
        $twoOvernight->Validate();
        $sameStartAndEnd->Validate();

        $this->assertFalse($gapAfterOvernight->IsValid());
        $this->assertFalse($overlapsOvernight->IsValid());
        $this->assertFalse($twoOvernight->IsValid());
        $this->assertFalse($sameStartAndEnd->IsValid());
    }

    public function testDailyLayoutMayHaveASlotThatRunsIntoTheNextDay(): void
    {
        // every night 22:00 - 06:00, so every day starts at 06:00
        $everyNight = new LayoutValidator(
            reservableSlots: $this->toWeek('22:00-06:00'),
            blockedSlots: $this->toWeek('06:00-22:00'),
            validateSingle: false
        );

        // only Monday night, so only Tuesday starts at 06:00
        $reservable = $this->toWeek('08:00-22:00');
        $blocked = $this->toWeek("00:00-08:00\n22:00-00:00");
        $reservable[DayOfWeek::MONDAY] = "08:00-22:00\n22:00-06:00";
        $blocked[DayOfWeek::MONDAY] = '00:00-08:00';
        $blocked[DayOfWeek::TUESDAY] = "06:00-08:00\n22:00-00:00";
        $mondayNight = new LayoutValidator(reservableSlots: $reservable, blockedSlots: $blocked, validateSingle: false);

        // Saturday night runs into Sunday
        $reservable = $this->toWeek('00:00-00:00');
        $blocked = $this->toWeek('');
        $reservable[DayOfWeek::SATURDAY] = "00:00-20:00\n20:00-04:00";
        $reservable[DayOfWeek::SUNDAY] = '04:00-00:00';
        $saturdayNight = new LayoutValidator(reservableSlots: $reservable, blockedSlots: $blocked, validateSingle: false);

        $everyNight->Validate();
        $mondayNight->Validate();
        $saturdayNight->Validate();

        $this->assertTrue($everyNight->IsValid());
        $this->assertTrue($mondayNight->IsValid());
        $this->assertTrue($saturdayNight->IsValid());
    }

    public function testDailyLayoutNextDayHasToStartWhereTheOvernightSlotEnds(): void
    {
        $reservable = $this->toWeek('08:00-22:00');
        $blocked = $this->toWeek("00:00-08:00\n22:00-00:00");
        $reservable[DayOfWeek::MONDAY] = "08:00-22:00\n22:00-06:00";
        $blocked[DayOfWeek::MONDAY] = '00:00-08:00';

        // Tuesday still starts at midnight, overlapping Monday's overnight slot
        $overlap = new LayoutValidator(reservableSlots: $reservable, blockedSlots: $blocked, validateSingle: false);

        // Tuesday starts at 08:00, leaving 06:00 - 08:00 uncovered
        $blocked[DayOfWeek::TUESDAY] = '22:00-00:00';
        $gap = new LayoutValidator(reservableSlots: $reservable, blockedSlots: $blocked, validateSingle: false);

        // a slot that runs past midnight has to be the last slot of its day
        $reservable = $this->toWeek('00:00-00:00');
        $reservable[DayOfWeek::MONDAY] = "00:00-10:00\n10:00-02:00\n12:00-00:00";
        $notLast = new LayoutValidator(reservableSlots: $reservable, blockedSlots: $this->toWeek(''), validateSingle: false);

        $overlap->Validate();
        $gap->Validate();
        $notLast->Validate();

        $this->assertFalse($overlap->IsValid());
        $this->assertFalse($gap->IsValid());
        $this->assertFalse($notLast->IsValid());
    }

    private function toWeek($slots)
    {
        $s = [];
        foreach (DayOfWeek::Days() as $day) {
            $s[$day] = $slots;
        }

        return $s;
    }
}
