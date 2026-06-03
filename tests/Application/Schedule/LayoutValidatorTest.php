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

    private function toWeek($slots)
    {
        $s = [];
        foreach (DayOfWeek::Days() as $day) {
            $s[$day] = $slots;
        }

        return $s;
    }
}
