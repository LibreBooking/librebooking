<?php

declare(strict_types=1);

namespace LibreBooking\Tests\Calendar;

use LibreBooking\Calendar\IcsMethod;
use PHPUnit\Framework\TestCase;

class IcsMethodTest extends TestCase
{
    public function testValuesAreTheRfc5546MethodNames(): void
    {
        $this->assertSame('PUBLISH', IcsMethod::PUBLISH->value);
        $this->assertSame('REQUEST', IcsMethod::REQUEST->value);
        $this->assertSame('CANCEL', IcsMethod::CANCEL->value);
    }

    public function testOnlyTheMethodsOfReservationsAreSupported(): void
    {
        $this->assertCount(3, IcsMethod::cases());
        $this->assertNull(IcsMethod::tryFrom('REPLY'));
    }
}
