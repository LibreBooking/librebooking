<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/CalendarPage.php');

use PHPUnit\Framework\Attributes\DataProvider;

class UserCalendarFilterTest extends TestBase
{
    public function testSerializeRoundTrips(): void
    {
        $filter = new UserCalendarFilter(resourceId: 10, scheduleId: 2, groupId: null);

        $serialized = $filter->Serialize();
        $restored = UserCalendarFilter::Deserialize($serialized);

        $this->assertSame('10|2|', $serialized);
        $this->assertSame(10, $restored->ResourceId);
        $this->assertSame(2, $restored->ScheduleId);
        $this->assertNull($restored->GroupId);
    }

    #[DataProvider('storedFilterProvider')]
    public function testDeserializeAcceptsOnlyPositiveIntegerIds(
        ?string $stored,
        ?int $resourceId,
        ?int $scheduleId,
        ?int $groupId
    ): void {
        $filter = UserCalendarFilter::Deserialize($stored);

        $this->assertSame($resourceId, $filter->ResourceId);
        $this->assertSame($scheduleId, $filter->ScheduleId);
        $this->assertSame($groupId, $filter->GroupId);
    }

    /**
     * @return array<string, array{0: string|null, 1: int|null, 2: int|null, 3: int|null}>
     */
    public static function storedFilterProvider(): array
    {
        return [
            'all IDs' => ['10|2|7', 10, 2, 7],
            'group only' => ['|2|7', null, 2, 7],
            'legacy array resource ID' => ['Array|2|7', null, 2, 7],
            'zero IDs' => ['0|0|0', null, null, null],
            'non-numeric IDs' => ['abc|1.5|-3', null, null, null],
            'too few parts' => ['10', 10, null, null],
            'empty preference' => ['', null, null, null],
            'missing preference' => [null, null, null, null],
        ];
    }
}
