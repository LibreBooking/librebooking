<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/namespace.php');

class SqlFilterTest extends TestBase
{
    public function testEqualsWithNullValueUsesIsNull(): void
    {
        $filter = new SqlFilterEquals('checkin_date', null);

        $this->assertSame('`checkin_date` IS NULL', $filter->Where());
    }

    public function testEqualsWithZeroIntegerComparesValue(): void
    {
        $filter = new SqlFilterEquals('status_id', 0);

        $this->assertSame('`status_id` = @status_id', $filter->Where());
        $this->assertSame(0, $filter->Criteria()[0]->Value);
    }

    public function testEqualsWithZeroStringComparesValue(): void
    {
        $filter = new SqlFilterEquals('status_id', '0');

        $this->assertSame('`status_id` = @status_id', $filter->Where());
    }
}
