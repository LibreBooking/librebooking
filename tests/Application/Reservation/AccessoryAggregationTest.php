<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/AccessoryAggregation.php');

class AccessoryAggregationTest extends TestBase
{
    private const int ACCESSORY_ID = 1;
    private const int OTHER_ACCESSORY_ID = 2;

    public function testReturnsZeroWhenNothingAdded(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');

        $this->assertSame(0, $aggregation->GetQuantity(self::ACCESSORY_ID));
    }

    public function testSumsReservationsThatOverlapEachOther(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '08:30', end: '09:45', quantity: 2));
        $aggregation->Add($this->Reservation(referenceNumber: 'B', start: '09:30', end: '10:30', quantity: 3));

        $this->assertSame(5, $aggregation->GetQuantity(self::ACCESSORY_ID));
    }

    public function testBackToBackReservationsAreNotConcurrent(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '08:30', end: '09:30', quantity: 2));
        $aggregation->Add($this->Reservation(referenceNumber: 'B', start: '09:30', end: '10:30', quantity: 3));

        $this->assertSame(3, $aggregation->GetQuantity(self::ACCESSORY_ID));
    }

    public function testReturnsPeakAcrossChainOfReservations(): void
    {
        $aggregation = $this->CreateAggregation(start: '08:00', end: '12:00');
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '08:00', end: '09:00', quantity: 4));
        $aggregation->Add($this->Reservation(referenceNumber: 'B', start: '08:30', end: '10:00', quantity: 1));
        $aggregation->Add($this->Reservation(referenceNumber: 'C', start: '09:30', end: '11:00', quantity: 2));
        $aggregation->Add($this->Reservation(referenceNumber: 'D', start: '10:30', end: '12:00', quantity: 2));

        // 08:30-09:00: A + B = 5; 09:30-10:00: B + C = 3; 10:30-11:00: C + D = 4
        $this->assertSame(5, $aggregation->GetQuantity(self::ACCESSORY_ID));
    }

    public function testIgnoresReservationsOutsideRange(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '08:00', end: '09:00', quantity: 2));
        $aggregation->Add($this->Reservation(referenceNumber: 'B', start: '10:00', end: '11:00', quantity: 3));

        $this->assertSame(0, $aggregation->GetQuantity(self::ACCESSORY_ID));
    }

    public function testTracksAccessoriesSeparately(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '09:00', end: '10:00', quantity: 2));
        $aggregation->Add($this->Reservation(
            referenceNumber: 'B',
            start: '09:00',
            end: '10:00',
            quantity: 3,
            accessoryId: self::OTHER_ACCESSORY_ID
        ));

        $this->assertSame(2, $aggregation->GetQuantity(self::ACCESSORY_ID));
        $this->assertSame(3, $aggregation->GetQuantity(self::OTHER_ACCESSORY_ID));
    }

    public function testCountsSameReservationAndAccessoryOnlyOnce(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '09:00', end: '10:00', quantity: 2));
        $aggregation->Add($this->Reservation(referenceNumber: 'A', start: '09:00', end: '10:00', quantity: 2));

        $this->assertSame(2, $aggregation->GetQuantity(self::ACCESSORY_ID));
    }

    public function testDistinctReferenceAndAccessoryPairsAreNotTreatedAsDuplicates(): void
    {
        $aggregation = $this->CreateAggregation(start: '09:00', end: '10:00');
        // Concatenating reference number and accessory id would give "123" for both
        $aggregation->Add($this->Reservation(referenceNumber: '1', start: '09:00', end: '10:00', quantity: 2, accessoryId: 23));
        $aggregation->Add($this->Reservation(referenceNumber: '12', start: '09:00', end: '10:00', quantity: 3, accessoryId: 3));

        $this->assertSame(2, $aggregation->GetQuantity(23));
        $this->assertSame(3, $aggregation->GetQuantity(3));
    }

    private function CreateAggregation(string $start, string $end): AccessoryAggregation
    {
        return new AccessoryAggregation(
            accessories: [],
            duration: new DateRange(begin: $this->Time(time: $start), end: $this->Time(time: $end))
        );
    }

    private function Reservation(
        string $referenceNumber,
        string $start,
        string $end,
        int $quantity,
        int $accessoryId = self::ACCESSORY_ID
    ): AccessoryReservation {
        return new AccessoryReservation(
            referenceNumber: $referenceNumber,
            startDate: $this->Time(time: $start),
            endDate: $this->Time(time: $end),
            accessoryId: $accessoryId,
            quantityReserved: $quantity
        );
    }

    private function Time(string $time): Date
    {
        return Date::Parse('2026-11-25 ' . $time, 'UTC');
    }
}
