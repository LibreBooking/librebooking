<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Schedule/namespace.php');

class BlackoutItemViewTest extends TestBase
{
    public function testBlackoutHasEmptyBufferTime(): void
    {
        $start = Date::Parse('2026-11-10 09:00', 'UTC');
        $blackout = new TestBlackoutItemView(1, $start, $start->AddHours(2), 1);

        $buffer = $blackout->GetBufferTime();

        $this->assertSame(0, $buffer->TotalSeconds());
        $this->assertFalse($blackout->HasBufferTime());
    }

    public function testBlackoutListItemHasNoBufferTime(): void
    {
        $start = Date::Parse('2026-11-10 09:00', 'UTC');
        $end = $start->AddHours(2);
        $item = new BlackoutListItem(new TestBlackoutItemView(1, $start, $end, 1));

        $this->assertFalse($item->HasBufferTime());
        $this->assertEquals($start, $item->BufferedStartDate());
        $this->assertEquals($end, $item->BufferedEndDate());
    }
}
