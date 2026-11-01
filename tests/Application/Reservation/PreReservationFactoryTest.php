<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\DataProvider;

require_once(ROOT_DIR . 'lib/Application/Reservation/ReservationHandler.php');

class PreReservationFactoryTest extends TestBase
{
    private const USER_ID = 123;
    private const RESOURCE_ID = 456;
    private const OTHER_RESOURCE_ID = 457;
    private const SCHEDULE_ID = 789;
    private const GROUP_ID = 321;

    private PreReservationFactory $factory;
    private Schedule $schedule;
    private ScheduleLayout $layout;
    private FakeBookableResource $resource;
    private FakeUserRepository $users;
    private FakeReservationViewRepository $reservations;
    private array $rows = [];

    public function setUp(): void
    {
        parent::setUp();
        Date::_SetNow(Date::Parse('2026-10-11 08:00', 'UTC'));
        $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_START_TIME_CONSTRAINT, ReservationStartTimeConstraint::FUTURE);
        $this->schedule = new Schedule(self::SCHEDULE_ID, 'Kiosk', false, DayOfWeek::SUNDAY, 7, 'UTC');
        $this->layout = new ScheduleLayout('UTC');
        $this->layout->AppendPeriod(Time::Parse('10:00'), Time::Parse('11:00'));
        $this->layout->AppendPeriod(Time::Parse('11:00'), Time::Parse('12:00'));
        $schedules = $this->createMock(ScheduleRepository::class);
        $schedules->method('LoadById')->willReturn($this->schedule);
        $schedules->method('GetLayout')->willReturn($this->layout);
        $this->resource = new FakeBookableResource(self::RESOURCE_ID, 'Room');
        $this->resource->SetScheduleId(self::SCHEDULE_ID);
        $this->resource->SetAllowMultiday(true);
        $this->users = new FakeUserRepository();
        $this->users->_User = new FakeUser(self::USER_ID);
        // The persisted account remains an administrator throughout these tests.
        $this->users->_User->_IsAdminForUser = true;
        $this->users->_User->_IsResourceAdmin = true;
        $this->reservations = new FakeReservationViewRepository();
        $this->factory = new class ($schedules, $this->users, $this->reservations) extends PreReservationFactory {
            public function __construct($schedules, $users, $reservations)
            {
                parent::__construct();
                $this->scheduleRepository = $schedules;
                $this->userRepository = $users;
                $this->reservationRepository = $reservations;
            }
        };
        $plugins = $this->getMockBuilder(FakePluginManager::class)->onlyMethods(['LoadAuthorization'])->getMock();
        $plugins->method('LoadAuthorization')->willReturn(new AuthorizationService($this->users));
        PluginManager::SetInstance($plugins);
        // Real permission/attribute/quota repositories, with rows selected by command,
        // so the test does not depend on the number or ordering of database reads.
        $db = $this->getMockBuilder(FakeDatabase::class)->onlyMethods(['Query'])->getMock();
        $db->method('Query')->willReturnCallback(fn (ISqlCommand $command) => new FakeReader($this->rows[get_class($command)] ?? []));
        ServiceLocator::SetDatabase($db);
        $permission = [ColumnNames::RESOURCE_ID => self::RESOURCE_ID, ColumnNames::RESOURCE_NAME => 'Room', ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full];
        $this->rows[GetUserPermissionsCommand::class] = [$permission];
        $this->rows[SelectUserGroupResourceAdminPermissions::class] = [$permission];
    }

    public static function roles(): array
    {
        return [['IsAdmin'], ['IsGroupAdmin'], ['IsResourceAdmin'], ['IsScheduleAdmin']];
    }

    public static function violations(): iterable
    {
        $cases = [
            'permission' => 'NoResourcePermission',
            'start' => 'StartIsInPast',
            'minimum notice' => 'MinNoticeError',
            'maximum notice' => 'MaxNoticeError',
            'title' => 'TitleRequiredRule',
            'description' => 'DescriptionRequiredRule',
            'minimum duration' => 'MinDurationError',
            'maximum duration' => 'MaxDurationError',
            'cross day' => 'MultiDayRule',
            'resource count' => 'InvalidNumberOfResourcesError',
            'quota' => 'QuotaConfiguration',
            'attribute' => 'CustomAttributeErrors',
            'credits' => 'CreditsRule',
            'concurrent' => 'ScheduleTotalReservationsError',
            'participation' => 'MaxParticipantsError',
            'buffer' => 'ConflictingReservationDates',
        ];
        foreach (self::roles() as [$role]) {
            foreach ($cases as $case => $error) {
                yield "$role: $case" => [$role, $case, $error];
            }
        }
    }

    #[DataProvider('violations')]
    public function testRestrictedAdminMustFollowOrdinaryRules(string $role, string $case, string $error): void
    {
        $session = $this->adminSession($role)->WithoutAdminPrivileges();
        $start = Date::Parse('2026-10-11 10:00', 'UTC');
        $end = $start->AddHours(1);
        $title = 'Meeting';
        $description = 'Meeting';
        switch ($case) {
            case 'permission':
                // Admin-only resource access remains in the database, but is insufficient.
                $this->rows[GetUserPermissionsCommand::class] = [];
                break;
            case 'start':
                Date::_SetNow($end);
                break;
            case 'minimum notice':
                $this->resource->SetMinNoticeAdd('4h');
                break;
            case 'maximum notice':
                $this->resource->SetMaxNotice('1h');
                break;
            case 'title':
                $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_TITLE_REQUIRED, true);
                $title = '';
                break;
            case 'description':
                $this->fakeConfig->SetKey(ConfigKeys::RESERVATION_DESCRIPTION_REQUIRED, true);
                $description = '';
                break;
            case 'minimum duration':
                $this->resource->SetMinLength('2h');
                break;
            case 'maximum duration':
                $this->resource->SetMaxLength('30m');
                break;
            case 'cross day':
                $this->resource->SetAllowMultiday(false);
                $end = $end->AddDays(1);
                break;
            case 'resource count':
                $this->schedule->SetMaxResourcesPerReservation(1);
                $this->rows[GetUserPermissionsCommand::class][] = [ColumnNames::RESOURCE_ID => self::OTHER_RESOURCE_ID, ColumnNames::RESOURCE_NAME => 'Other', ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full];
                break;
            case 'quota':
                $this->rows[GetAllQuotasCommand::class] = [[
                    ColumnNames::QUOTA_ID => 1, ColumnNames::QUOTA_LIMIT => 0,
                    ColumnNames::QUOTA_UNIT => QuotaUnit::Reservations, ColumnNames::QUOTA_DURATION => QuotaDuration::Day,
                    ColumnNames::RESOURCE_ID => null, ColumnNames::GROUP_ID => null, ColumnNames::SCHEDULE_ID => null,
                    ColumnNames::ENFORCED_START_TIME => null, ColumnNames::ENFORCED_END_TIME => null, ColumnNames::ENFORCED_DAYS => null,
                    ColumnNames::QUOTA_SCOPE => QuotaScope::IncludeCompleted,
                    'group_name' => '', 'resource_name' => '', 'schedule_name' => '',
                ]];
                break;
            case 'attribute':
                $this->rows[GetAttributesByCategoryCommand::class] = [[
                    ColumnNames::ATTRIBUTE_ID => 1, ColumnNames::ATTRIBUTE_LABEL => 'Required field',
                    ColumnNames::ATTRIBUTE_TYPE => CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
                    ColumnNames::ATTRIBUTE_CATEGORY => CustomAttributeCategory::RESERVATION,
                    ColumnNames::ATTRIBUTE_CONSTRAINT => '', ColumnNames::ATTRIBUTE_REQUIRED => true,
                    ColumnNames::ATTRIBUTE_POSSIBLE_VALUES => '', ColumnNames::ATTRIBUTE_SORT_ORDER => 0,
                    ColumnNames::ATTRIBUTE_ADMIN_ONLY => false,
                ]];
                break;
            case 'credits':
                $this->fakeConfig->SetKey(ConfigKeys::CREDITS_ENABLED, true);
                $this->resource->SetCreditsPerSlot(1);
                break;
            case 'concurrent':
                $this->schedule->SetTotalConcurrentReservations(1);
                $this->reservations->_Reservations = [new TestReservationItemView(1, $start, $end, self::OTHER_RESOURCE_ID)];
                break;
            case 'participation':
                $this->resource->SetMaxParticipants(1);
                break;
            case 'buffer':
                $this->resource->SetBufferTime('30m');
                $conflict = new TestReservationItemView(1, $end, $end->AddHours(1), self::RESOURCE_ID);
                $conflict->WithBufferTime(30 * 60);
                $this->reservations->_Reservations = [$conflict];
                break;
        }
        $series = ReservationSeries::Create(self::USER_ID, $this->resource, $title, $description, new DateRange($start, $end), new RepeatNone(), $session);
        $series->AcceptTerms(true);
        if ($case === 'resource count') {
            $series->AddResource(new FakeBookableResource(self::OTHER_RESOURCE_ID));
        }
        if ($case === 'participation') {
            $series->ChangeParticipants([self::USER_ID, self::USER_ID + 1]);
        }
        if ($case === 'credits') {
            $series->CalculateCredits($this->layout);
            $this->assertGreaterThan(0, $series->GetCreditsRequired());
        }
        $result = $this->factory->CreatePreAddService($session)->Validate($series);
        $this->assertFalse($result->CanBeSaved());
        $this->assertStringContainsString($error, implode(' ', $result->GetErrors()));

        if (in_array($case, ['permission', 'start', 'minimum notice', 'maximum notice', 'title', 'description', 'attribute'], true)) {
            $authenticated = $this->adminSession($role);
            $normalBooking = ReservationSeries::Create(self::USER_ID, $this->resource, $title, $description, new DateRange($start, $end), new RepeatNone(), $authenticated);
            $normalResult = $this->factory->CreatePreAddService($authenticated)->Validate($normalBooking);
            $this->assertTrue($normalResult->CanBeSaved(), 'Authenticated admin exemptions must remain available: ' . implode(' ', $normalResult->GetErrors()));
        }
    }

    #[DataProvider('roles')]
    public function testRestrictedBookingsRequireApprovalButAuthenticatedAdminsKeepExemptions(string $role): void
    {
        $source = $this->adminSession($role);
        $this->resource->RequiresApproval(true);
        $restricted = $source->WithoutAdminPrivileges();
        foreach ([$restricted, $source] as $session) {
            $series = ReservationSeries::Create(self::USER_ID, $this->resource, 'Meeting', 'Meeting', DateRange::Create('2026-10-11 10:00', '2026-10-11 11:00', 'UTC'), new RepeatNone(), $session);
            $result = $this->factory->CreatePreAddService($session)->Validate($series);
            $this->assertTrue($result->CanBeSaved(), implode(' ', $result->GetErrors()));
            $this->assertSame($session === $restricted, $series->RequiresApproval());
            $this->assertSame(self::USER_ID, $series->UserId());
        }
        $this->assertTrue($source->$role);
        $this->assertNull($this->users->_UpdatedUser);
        $this->assertNull($this->users->_LoginDataUpdatedUser);
    }

    public function testOrdinaryGroupPermissionStillAllowsRestrictedBooking(): void
    {
        $this->rows[SelectUserGroupPermissions::class] = [array_merge($this->rows[GetUserPermissionsCommand::class][0], [ColumnNames::GROUP_ID => self::GROUP_ID])];
        $this->rows[GetUserPermissionsCommand::class] = [];
        $session = $this->adminSession('IsAdmin')->WithoutAdminPrivileges();
        $series = ReservationSeries::Create(self::USER_ID, $this->resource, 'Meeting', 'Meeting', DateRange::Create('2026-10-11 10:00', '2026-10-11 11:00', 'UTC'), new RepeatNone(), $session);
        $result = $this->factory->CreatePreAddService($session)->Validate($series);
        $this->assertTrue($result->CanBeSaved(), implode(' ', $result->GetErrors()));
    }

    public function testConfiguredPermissionPluginCanStillDenyRestrictedBooking(): void
    {
        $permission = $this->createMock(IPermissionService::class);
        $permission->expects($this->once())->method('CanBookResource')->willReturn(false);
        $plugins = $this->getMockBuilder(FakePluginManager::class)->onlyMethods(['LoadPermission'])->getMock();
        $plugins->method('LoadPermission')->willReturn($permission);
        PluginManager::SetInstance($plugins);
        $session = $this->adminSession('IsAdmin')->WithoutAdminPrivileges();
        $series = ReservationSeries::Create(self::USER_ID, $this->resource, 'Meeting', 'Meeting', DateRange::Create('2026-10-11 10:00', '2026-10-11 11:00', 'UTC'), new RepeatNone(), $session);
        $result = $this->factory->CreatePreAddService($session)->Validate($series);
        $this->assertFalse($result->CanBeSaved());
        $this->assertSame(['NoResourcePermission'], $result->GetErrors());
    }

    private function adminSession(string $role): UserSession
    {
        $session = new UserSession(self::USER_ID);
        $session->Timezone = 'UTC';
        $session->$role = true;
        $session->Groups = [self::GROUP_ID];
        $session->AdminGroups = [self::GROUP_ID];
        return $session;
    }
}
