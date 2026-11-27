<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');
require_once(ROOT_DIR . 'lib/Application/Reservation/Validation/namespace.php');
require_once(ROOT_DIR . 'Pages/Ajax/AvailableAccessoriesPage.php');

/**
 * Checks accessory availability against real reservations stored in the test
 * database, using the accessories from database_schema/sample-data-utf8.sql.
 *
 * Covers the save-time check (AccessoryAvailabilityRule) and the availability
 * shown in the reservation form (AvailableAccessoriesPresenter), both of which
 * read existing bookings with ReservationViewRepository::GetAccessoriesWithin().
 *
 * Reservations are created on a date far from any sample data and removed
 * afterwards.
 *
 * @see https://github.com/LibreBooking/librebooking/issues/2058
 */
class AccessoryAvailabilityDatabaseTest extends DatabaseTestCase
{
    /**
     * "accessory limited to 10" in the sample data.
     */
    private const int ACCESSORY_LIMITED_TO_10 = 1;
    /**
     * "accessory limited to 2" in the sample data.
     */
    private const int ACCESSORY_LIMITED_TO_2 = 2;
    /**
     * "unlimited accessory" in the sample data.
     */
    private const int UNLIMITED_ACCESSORY = 3;

    private const string DAY = '2099-03-10';
    private const string NEXT_DAY = '2099-03-11';
    private const string TIMEZONE = 'UTC';
    private const string REFERENCE_PREFIX = 'accessory-db-test-';

    private Database $database;
    private AccessoryAvailabilityRule $rule;
    /**
     * @var int[]
     */
    private array $createdSeriesIds = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->requireLoadedSchema();

        $this->database = $this->realDatabase();
        ServiceLocator::SetDatabase($this->database);
        $this->requireSampleAccessories();

        $this->rule = new AccessoryAvailabilityRule(
            reservationRepository: new ReservationViewRepository(),
            accessoryRepository: new AccessoryRepository(),
            timezone: self::TIMEZONE
        );
    }

    public function tearDown(): void
    {
        if (isset($this->database)) {
            foreach ($this->createdSeriesIds as $seriesId) {
                // Instances and accessory rows are removed by ON DELETE CASCADE
                $this->database->Execute($this->command(
                    sql: 'DELETE FROM `reservation_series` WHERE `series_id` = @seriesId',
                    parameters: ['@seriesId' => $seriesId]
                ));
            }
        }
        ServiceLocator::SetDatabase(new FakeDatabase());

        parent::tearDown();
    }

    /**
     * The scenario from the issue: the new booking overlaps two bookings that
     * follow each other, so at most 6 units are in use alongside it.
     */
    public function testBookingAcrossBackToBackReservationsIsAccepted(): void
    {
        $this->storeReservation(start: '08:30', end: '09:30', quantities: [self::ACCESSORY_LIMITED_TO_10 => 4]);
        $this->storeReservation(start: '09:30', end: '10:30', quantities: [self::ACCESSORY_LIMITED_TO_10 => 6]);

        $result = $this->validate(start: '09:00', end: '10:00', accessoryId: self::ACCESSORY_LIMITED_TO_10, quantity: 4);

        $this->assertTrue($result->IsValid(), (string)$result->ErrorMessage());
    }

    public function testBookingAcrossBackToBackReservationsIsRejectedWhenPeakExceedsQuantity(): void
    {
        $this->storeReservation(start: '08:30', end: '09:30', quantities: [self::ACCESSORY_LIMITED_TO_10 => 4]);
        $this->storeReservation(start: '09:30', end: '10:30', quantities: [self::ACCESSORY_LIMITED_TO_10 => 6]);

        $result = $this->validate(start: '09:00', end: '10:00', accessoryId: self::ACCESSORY_LIMITED_TO_10, quantity: 5);

        $this->assertFalse($result->IsValid());
    }

    public function testReservationsThatOverlapEachOtherAreAddedTogether(): void
    {
        $this->storeReservation(start: '13:00', end: '14:00', quantities: [self::ACCESSORY_LIMITED_TO_2 => 1]);
        $this->storeReservation(start: '13:30', end: '14:30', quantities: [self::ACCESSORY_LIMITED_TO_2 => 1]);

        $result = $this->validate(start: '13:00', end: '15:00', accessoryId: self::ACCESSORY_LIMITED_TO_2, quantity: 1);

        $this->assertFalse($result->IsValid());
    }

    public function testChainOfReservationsIsCheckedAtItsBusiestMoment(): void
    {
        // 08:30-09:00 has 4 + 3 = 7 in use; 09:30-10:00 has 3 + 2 = 5; 10:30-11:00 has 2 + 1 = 3
        $this->storeReservation(start: '08:00', end: '09:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 4]);
        $this->storeReservation(start: '08:30', end: '10:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 3]);
        $this->storeReservation(start: '09:30', end: '11:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 2]);
        $this->storeReservation(start: '10:30', end: '12:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 1]);

        $fits = $this->validate(start: '08:00', end: '12:00', accessoryId: self::ACCESSORY_LIMITED_TO_10, quantity: 3);
        $tooMany = $this->validate(start: '08:00', end: '12:00', accessoryId: self::ACCESSORY_LIMITED_TO_10, quantity: 4);

        $this->assertTrue($fits->IsValid(), (string)$fits->ErrorMessage());
        $this->assertFalse($tooMany->IsValid());
    }

    public function testDeletedReservationsAreIgnored(): void
    {
        $this->storeReservation(
            start: '09:00',
            end: '10:00',
            quantities: [self::ACCESSORY_LIMITED_TO_10 => 10],
            statusId: ReservationStatus::Deleted
        );

        $result = $this->validate(start: '09:00', end: '10:00', accessoryId: self::ACCESSORY_LIMITED_TO_10, quantity: 10);

        $this->assertTrue($result->IsValid(), (string)$result->ErrorMessage());
    }

    public function testEditingAReservationDoesNotCountItsOwnAccessories(): void
    {
        $referenceNumber = $this->storeReservation(start: '09:00', end: '10:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 10]);

        $series = new TestReservationSeries();
        $series->WithAccessory(new ReservationAccessory(accessoryId: self::ACCESSORY_LIMITED_TO_10, quantityReserved: 10));
        $series->WithCurrentInstance(new TestReservation(
            referenceNumber: $referenceNumber,
            reservationDate: $this->range(start: '09:00', end: '10:00')
        ));

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid(), (string)$result->ErrorMessage());
    }

    public function testUnlimitedAccessoryIsAlwaysAvailable(): void
    {
        $this->storeReservation(start: '09:00', end: '10:00', quantities: [self::UNLIMITED_ACCESSORY => 200]);

        $result = $this->validate(start: '09:00', end: '10:00', accessoryId: self::UNLIMITED_ACCESSORY, quantity: 200);

        $this->assertTrue($result->IsValid(), (string)$result->ErrorMessage());
    }

    public function testMultiDayBookingIsCheckedAgainstEachDaysPeak(): void
    {
        $this->storeReservation(start: '10:00', end: '11:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 6]);
        $this->storeReservation(start: '10:00', end: '11:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 6], day: self::NEXT_DAY);

        $series = new TestReservationSeries();
        $series->WithAccessory(new ReservationAccessory(accessoryId: self::ACCESSORY_LIMITED_TO_10, quantityReserved: 4));
        $series->WithDuration(new DateRange(
            begin: $this->date(time: '08:00'),
            end: $this->date(time: '18:00', day: self::NEXT_DAY)
        ));

        $result = $this->rule->Validate($series, null);

        $this->assertTrue($result->IsValid(), (string)$result->ErrorMessage());
    }

    public function testReservationFormShowsAvailabilityAtTheBusiestMoment(): void
    {
        $this->storeReservation(start: '08:30', end: '09:30', quantities: [
            self::ACCESSORY_LIMITED_TO_10 => 4,
            self::ACCESSORY_LIMITED_TO_2 => 1,
        ]);
        $this->storeReservation(start: '09:30', end: '10:30', quantities: [
            self::ACCESSORY_LIMITED_TO_10 => 6,
            self::ACCESSORY_LIMITED_TO_2 => 1,
        ]);

        $availability = $this->availabilityShownInForm(startDay: self::DAY, start: '09:00', endDay: self::DAY, end: '10:00');

        $this->assertEquals(4, $availability[self::ACCESSORY_LIMITED_TO_10]);
        $this->assertEquals(1, $availability[self::ACCESSORY_LIMITED_TO_2]);
        $this->assertNull($availability[self::UNLIMITED_ACCESSORY]);
    }

    public function testReservationFormShowsAvailabilityForMultiDayBooking(): void
    {
        $this->storeReservation(start: '10:00', end: '11:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 6]);
        $this->storeReservation(start: '10:00', end: '11:00', quantities: [self::ACCESSORY_LIMITED_TO_10 => 6], day: self::NEXT_DAY);

        $availability = $this->availabilityShownInForm(startDay: self::DAY, start: '08:00', endDay: self::NEXT_DAY, end: '18:00');

        $this->assertEquals(4, $availability[self::ACCESSORY_LIMITED_TO_10]);
    }

    /**
     * Stores a one-instance reservation series with the given accessory quantities.
     *
     * @param array<int, int> $quantities quantity by accessory id
     * @return string the instance's reference number
     */
    private function storeReservation(
        string $start,
        string $end,
        array $quantities,
        string $day = self::DAY,
        int $statusId = ReservationStatus::Created
    ): string {
        $seriesId = (int)$this->database->ExecuteInsert($this->command(
            sql: 'INSERT INTO `reservation_series`
                (`date_created`, `title`, `allow_participation`, `allow_anon_participation`, `type_id`, `status_id`, `owner_id`)
                VALUES (UTC_TIMESTAMP(), @title, 0, 0, @typeId, @statusId, (SELECT MIN(`user_id`) FROM `users`))',
            parameters: [
                '@title' => 'Accessory availability test',
                '@typeId' => ReservationTypes::Reservation,
                '@statusId' => $statusId,
            ]
        ));
        $this->createdSeriesIds[] = $seriesId;

        $referenceNumber = self::REFERENCE_PREFIX . $seriesId;
        $this->database->Execute($this->command(
            sql: 'INSERT INTO `reservation_instances` (`start_date`, `end_date`, `reference_number`, `series_id`)
                VALUES (@startDate, @endDate, @referenceNumber, @seriesId)',
            parameters: [
                '@startDate' => $this->date(time: $start, day: $day)->ToDatabase(),
                '@endDate' => $this->date(time: $end, day: $day)->ToDatabase(),
                '@referenceNumber' => $referenceNumber,
                '@seriesId' => $seriesId,
            ]
        ));

        foreach ($quantities as $accessoryId => $quantity) {
            $this->database->Execute($this->command(
                sql: 'INSERT INTO `reservation_accessories` (`series_id`, `accessory_id`, `quantity`)
                    VALUES (@seriesId, @accessoryId, @quantity)',
                parameters: ['@seriesId' => $seriesId, '@accessoryId' => $accessoryId, '@quantity' => $quantity]
            ));
        }

        return $referenceNumber;
    }

    private function validate(string $start, string $end, int $accessoryId, int $quantity): ReservationRuleResult
    {
        $series = new TestReservationSeries();
        $series->WithAccessory(new ReservationAccessory(accessoryId: $accessoryId, quantityReserved: $quantity));
        $series->WithDuration($this->range(start: $start, end: $end));

        return $this->rule->Validate($series, null);
    }

    /**
     * @return array<int, int|null> quantity available by accessory id, null when unlimited
     */
    private function availabilityShownInForm(string $startDay, string $start, string $endDay, string $end): array
    {
        $page = new class ($startDay, $start, $endDay, $end) implements IAvailableAccessoriesPage {
            /**
             * @var AccessoryAvailability[]
             */
            public array $bound = [];

            public function __construct(
                private readonly string $startDay,
                private readonly string $start,
                private readonly string $endDay,
                private readonly string $end
            ) {
            }

            public function GetStartDate(): string
            {
                return $this->startDay;
            }

            public function GetEndDate(): string
            {
                return $this->endDay;
            }

            public function GetStartTime(): string
            {
                return $this->start;
            }

            public function GetEndTime(): string
            {
                return $this->end;
            }

            public function GetReferenceNumber(): ?string
            {
                return null;
            }

            /**
             * @param AccessoryAvailability[] $realAvailability
             */
            public function BindAvailability($realAvailability): void
            {
                $this->bound = $realAvailability;
            }
        };

        $this->fakeUser->Timezone = self::TIMEZONE;
        $presenter = new AvailableAccessoriesPresenter(
            page: $page,
            accessoryRepository: new AccessoryRepository(),
            reservationViewRepository: new ReservationViewRepository(),
            userSession: $this->fakeUser
        );
        $presenter->PageLoad();

        $availability = [];
        foreach ($page->bound as $accessory) {
            $availability[(int)$accessory->id] = $accessory->quantity;
        }

        return $availability;
    }

    private function requireSampleAccessories(): void
    {
        $repository = new AccessoryRepository();
        $expected = [
            self::ACCESSORY_LIMITED_TO_10 => 10,
            self::ACCESSORY_LIMITED_TO_2 => 2,
            self::UNLIMITED_ACCESSORY => null,
        ];

        foreach ($expected as $id => $quantity) {
            $accessory = $repository->LoadById($id);
            if ($accessory === null || $accessory->GetQuantityAvailable() != $quantity) {
                $this->markTestSkipped('Load database_schema/sample-data-utf8.sql (tests/Integration/setup-test-database.sh) to run these tests.');
            }
        }
    }

    private function range(string $start, string $end): DateRange
    {
        return new DateRange(begin: $this->date(time: $start), end: $this->date(time: $end));
    }

    private function date(string $time, string $day = self::DAY): Date
    {
        return Date::Parse($day . ' ' . $time, self::TIMEZONE);
    }

    /**
     * @param array<string, int|string|null> $parameters
     */
    private function command(string $sql, array $parameters): SqlCommand
    {
        $command = new SqlCommand($sql);
        foreach ($parameters as $name => $value) {
            $command->AddParameter(new Parameter(name: $name, value: $value));
        }

        return $command;
    }
}
