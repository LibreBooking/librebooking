<?php

define('ROOT_DIR', '../../');

require_once(ROOT_DIR . 'Pages/Page.php');
require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

class MigrationPage extends Page
{
    /**
     * @var MigrationPresenter
     */
    private $presenter;

    public function __construct()
    {
        parent::__construct('', 1);
        $this->presenter = new MigrationPresenter($this);
    }

    public function SetError($ex)
    {
        $this->SetJsonError($ex);
    }

    protected function GetShouldAutoLogout()
    {
        return false;
    }

    public function PageLoad()
    {
        $this->presenter->PageLoad();
    }

    public function IsLoggingIn()
    {
        $buttonValue = $this->GetForm('run');

        return !empty($buttonValue);
    }

    public function SetProgress($numberPending)
    {
        $this->SetJson($numberPending);
    }

    public function StartMigration()
    {
        $this->Set('StartMigration', true);
        $this->Display('Install/migrate.tpl');
    }

    public function GetRunTarget()
    {
        return $this->GetQuerystring('start');
    }

    public function DisplayMigrationPrompt()
    {
        $this->Display('Install/migrate.tpl');
    }

    public function GetLegacyUserName()
    {
        return $this->GetForm('legacyUser');
    }

    public function GetLegacyPassword()
    {
        return $this->GetForm('legacyPassword');
    }

    public function GetLegacyHostSpec()
    {
        return $this->GetForm('legacyHostSpec');
    }

    public function GetLegacyDatabaseName()
    {
        return $this->GetForm('legacyDatabaseName');
    }

    public function SetLegacyConnectionSucceeded($wasSuccessful)
    {
        $this->Set('LegacyConnectionFailed', !$wasSuccessful);
    }

    public function SetSchedulesMigrated($schedulesMigrated)
    {
        $this->Set('SchedulesMigratedCount', $schedulesMigrated);
    }

    public function SetResourcesMigrated($resourcesMigrated)
    {
        $this->Set('ResourcesMigratedCount', $resourcesMigrated);
    }

    public function SetAccessoriesMigrated($accessoriesMigrated)
    {
        $this->Set('AccessoriesMigratedCount', $accessoriesMigrated);
    }

    public function SetGroupsMigrated($groupsMigrated)
    {
        $this->Set('GroupsMigratedCount', $groupsMigrated);
    }

    public function SetUsersMigrated($usersMigrated)
    {
        $this->Set('UsersMigratedCount', $usersMigrated);
    }

    public function SetReservationsMigrated($reservationsMigrated)
    {
        $this->Set('ReservationsMigratedCount', $reservationsMigrated);
    }

    public function GetInstallPassword()
    {
        return $this->GetForm('installPassword');
    }

    public function SetInstallPasswordSucceeded($passwordValid)
    {
        $this->Set('InstallPasswordFailed', !$passwordValid);
    }
}

class MigrationSession
{
    public static function SetPasswordOK($value)
    {
        ServiceLocator::GetServer()->SetSession('migrate-password-ok', $value);
    }

    public static function ClearLegacyDb()
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-db', null);
    }

    public static function GetLegacyDb()
    {
        return ServiceLocator::GetServer()->GetSession('migrate-legacy-db');
    }

    public static function SetLegacyDb($legacyUserName, $legacyPassword, $legacyHostSpec, $legacyDatabaseName)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-db', [
            'username' => $legacyUserName,
            'password' => $legacyPassword,
            'hostspec' => $legacyHostSpec,
            'databasename' => $legacyDatabaseName
        ]);
    }

    private static function GetLastId($key)
    {
        $id = ServiceLocator::GetServer()->GetSession($key);
        if (empty($id)) {
            return 0;
        }
        return $id;
    }

    public static function SetLastScheduleRow($schedulesMigrated)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-schedules', $schedulesMigrated);
    }

    public static function GetLastScheduleRow()
    {
        return self::GetLastId('migrate-legacy-schedules');
    }

    public static function GetLastResourceRow()
    {
        return self::GetLastId('migrate-legacy-resources');
    }

    public static function SetLastResourceRow($resourcesMigrated)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-resources', $resourcesMigrated);
    }

    public static function SetLastAccessoryRow($accessoriesMigrated)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-accessories', $accessoriesMigrated);
    }

    public static function GetLastAccessoryRow()
    {
        return self::GetLastId('migrate-legacy-accessories');
    }

    public static function GetLastGroupRow()
    {
        return self::GetLastId('migrate-legacy-groups');
    }

    public static function SetLastGroupRow($groupsMigrated)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-groups', $groupsMigrated);
    }

    public static function GetLastUserRow()
    {
        return self::GetLastId('migrate-legacy-users');
    }

    public static function SetLastUserRow($usersMigrated)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-users', $usersMigrated);
    }

    public static function GetLastReservationRow()
    {
        return self::GetLastId('migrate-legacy-reservations');
    }

    public static function SetLastReservationRow($reservationsMigrated)
    {
        ServiceLocator::GetServer()->SetSession('migrate-legacy-reservations', $reservationsMigrated);
    }
}

class MigrationPresenter
{
    /**
     * @var MigrationPage
     */
    private $page;

    public function __construct(MigrationPage $page)
    {
        $this->page = $page;
    }

    public function PageLoad()
    {
        try {
            $legacyDatabase = new Database($this->GetLegacyConnection());
            $currentDatabase = ServiceLocator::GetDatabase();
            $runTarget = $this->page->GetRunTarget();
            if (!empty($runTarget)) {
                $this->Migrate($runTarget);
            } elseif ($this->page->IsLoggingIn()) {
                if ($this->TestInstallPassword() && $this->TestLegacyConnection()) {
                    $this->page->StartMigration();
                } else {
                    $this->page->DisplayMigrationPrompt();
                }
            } else {
                $this->page->DisplayMigrationPrompt();
            }
        } catch (Exception $ex) {
            Log::Error('General migration exception. %s', $ex);
            throw $ex;
        }
    }

    private function GetLegacyDatabase()
    {
        return new Database($this->GetLegacyConnection());
    }

    private function Migrate($runTarget)
    {
        try {
            $legacyDatabase = $this->GetLegacyDatabase();
            $currentDatabase = ServiceLocator::GetDatabase();

            if ($runTarget == 'schedules') {
                $this->MigrateSchedules($legacyDatabase, $currentDatabase);
            }
            if ($runTarget == 'resources') {
                $this->MigrateResources($legacyDatabase, $currentDatabase);
            }
            if ($runTarget == 'accessories') {
                $this->MigrateAccessories($legacyDatabase, $currentDatabase);
            }
            if ($runTarget == 'groups') {
                $this->MigrateGroups($legacyDatabase, $currentDatabase);
            }
            if ($runTarget == 'users') {
                $this->MigrateUsers($legacyDatabase, $currentDatabase);
            }
            if ($runTarget == 'reservations') {
                $this->MigrateReservations($legacyDatabase, $currentDatabase);
            }
        } catch (Exception $ex) {
            Log::Error('Migration exception. %s', $ex);
            $this->page->SetError($ex);
        }
    }

    /**
     * @return MySqlConnection
     */
    private function GetLegacyConnection()
    {
        $sessionValues = MigrationSession::GetLegacyDb();

        if (!empty($sessionValues)) {
            $legacyUserName = $sessionValues['username'];
            $legacyPassword = $sessionValues['password'];
            $legacyHostSpec = $sessionValues['hostspec'];
            $legacyDatabaseName = $sessionValues['databasename'];
        } else {
            $legacyUserName = $this->page->GetLegacyUserName();
            $legacyPassword = $this->page->GetLegacyPassword();
            $legacyHostSpec = $this->page->GetLegacyHostSpec();
            $legacyDatabaseName = $this->page->GetLegacyDatabaseName();

            MigrationSession::SetLegacyDb($legacyUserName, $legacyPassword, $legacyHostSpec, $legacyDatabaseName);
        }

        return new MySqlConnection($legacyUserName, $legacyPassword, $legacyHostSpec, $legacyDatabaseName);
    }

    /**
     * @return bool
     */
    private function TestLegacyConnection()
    {
        $legacyConnection = $this->GetLegacyConnection();
        try {
            $legacyConnection->Connect();
            $legacyConnection->Disconnect();
            $this->page->SetLegacyConnectionSucceeded(true);
            return true;
        } catch (Exception $ex) {
            MigrationSession::ClearLegacyDb();
            $this->page->SetLegacyConnectionSucceeded(false);
            return false;
        }
    }

    /**
     * @return bool
     */
    private function TestInstallPassword()
    {
        $password = Configuration::Instance()->GetKey(ConfigKeys::INSTALL_PASSWORD);

        if (empty($password) || $password != $this->page->GetInstallPassword()) {
            MigrationSession::SetPasswordOK(null);
            $this->page->SetInstallPasswordSucceeded(false);
            return false;
        }
        $this->page->SetInstallPasswordSucceeded(true);
        MigrationSession::SetPasswordOK(true);
        return true;
    }

    private function MigrateSchedules(Database $legacyDatabase, Database $currentDatabase)
    {
        $schedulesMigrated = 0;

        $scheduleRepo = new ScheduleRepository();

        $getExistingSchedules = new AdHocCommand('select legacyid from schedules where legacyid is not null');
        $reader = $currentDatabase->Query($getExistingSchedules);

        $knownIds = [];
        while ($row = $reader->GetRow()) {
            $knownIds[] = $row['legacyid'];
            $schedulesMigrated++;
        }

        Log::Debug('Start migrating schedules. Starting at row %s', $schedulesMigrated);

        $getLegacySchedules = new AdHocCommand("select scheduleid, scheduletitle, daystart, dayend, timespan,
		                timeformat, weekdaystart, viewdays, usepermissions, ishidden, showsummary, adminemail, isdefault
		                from schedules order by scheduleid limit $schedulesMigrated, 500");

        $reader = $legacyDatabase->Query($getLegacySchedules);

        while ($row = $reader->GetRow()) {
            $legacyScheduleId = $row['scheduleid'];
            if (in_array($legacyScheduleId, $knownIds)) {
                continue;
            }

            $newId = $scheduleRepo->Add(
                new Schedule(null, $row['scheduletitle'], false, $row['weekdaystart'], $row['viewdays']),
                1
            );

            $currentDatabase->Execute(new AdHocCommand("update schedules set legacyid = \"{$row['scheduleid']}\" where schedule_id = $newId"));
            $timezone = Configuration::Instance()->GetDefaultTimezone();

            $available = $this->CreateAvailableTimeSlots($row['daystart'], $row['dayend'], $row['timespan']);
            $unavailable = $this->CreateUnavailableTimeSlots($row['daystart'], $row['dayend'], $row['timespan']);
            $layout = ScheduleLayout::Parse($timezone, $available, $unavailable);

            $scheduleRepo->AddScheduleLayout($newId, $layout);

            $schedulesMigrated++;
            MigrationSession::SetLastScheduleRow($schedulesMigrated);
        }

        Log::Debug('Done migrating schedules (%s schedules)', $schedulesMigrated);

        $getLegacyCount = new AdHocCommand('select count(*) as count from schedules');
        $getMigratedCount = new AdHocCommand('select count(*) as count from schedules where legacyid is not null');

        $progressCounts = $this->GetProgressCounts($getLegacyCount, $getMigratedCount);

        $this->page->SetProgress($progressCounts);
        $this->page->SetSchedulesMigrated($progressCounts->MigratedCount);
        MigrationSession::SetLastScheduleRow($progressCounts->MigratedCount);
    }

    private function MigrateResources(Database $legacyDatabase, Database $currentDatabase)
    {
        $resourcesMigrated = 0;

        $resourceRepo = new ResourceRepository();

        $getExisting = new AdHocCommand('select legacyid from resources where legacyid is not null');
        $reader = $currentDatabase->Query($getExisting);

        $knownIds = [];
        while ($row = $reader->GetRow()) {
            $knownIds[] = $row['legacyid'];
            $resourcesMigrated++;
        }

        Log::Debug('Start migrating resources. Starting at row %s', $resourcesMigrated);

        $getResources = new AdHocCommand("select machid, scheduleid, name, location, rphone, notes, status, minres, maxres, autoassign, approval,
                        allow_multi, max_participants, min_notice_time, max_notice_time
                        from resources order by machid limit $resourcesMigrated, 500");

        $reader = $legacyDatabase->Query($getResources);

        while ($row = $reader->GetRow()) {
            $legacyResourceId = $row['machid'];
            if (in_array($legacyResourceId, $knownIds)) {
                continue;
            }

            $newScheduleReader = $currentDatabase->Query(new AdHocCommand("select schedule_id from schedules where legacyId = \"{$row['scheduleid']}\""));

            $newScheduleId = null;
            if ($srow = $newScheduleReader->GetRow()) {
                $newScheduleId = $srow['schedule_id'];
            }

            $minTimeSeconds = $row['minres'] * 60;
            $maxTimeSeconds = $row['maxres'] * 60;
            $min_notice_time = $row['min_notice_time'] * 60;
            $max_notice_time = $row['max_notice_time'] * 60;

            $newId = $resourceRepo->Add(
                new BookableResource(
                    null,
                    $row['name'],
                    $row['location'],
                    $row['rphone'],
                    $row['notes'],
                    $minTimeSeconds,
                    $maxTimeSeconds,
                    $row['autoassign'],
                    $row['approval'],
                    $row['allow_multi'],
                    $row['max_participants'],
                    $min_notice_time,
                    $max_notice_time,
                    null,
                    $newScheduleId,
                    null,
                    $min_notice_time,
                    $min_notice_time
                )
            );

            $currentDatabase->Execute(new AdHocCommand("update resources set legacyid = \"{$row['machid']}\" where resource_id = $newId"));

            $resourcesMigrated++;
            MigrationSession::SetLastResourceRow($resourcesMigrated);
        }

        Log::Debug('Done migrating resources (%s resources)', $resourcesMigrated);

        $getLegacyCount = new AdHocCommand('select count(*) as count from resources');
        $getMigratedCount = new AdHocCommand('select count(*) as count from resources where legacyid is not null');

        $progressCounts = $this->GetProgressCounts($getLegacyCount, $getMigratedCount);

        $this->page->SetProgress($progressCounts);

        $this->page->SetResourcesMigrated($progressCounts->MigratedCount);
        MigrationSession::SetLastResourceRow($progressCounts->MigratedCount);
    }

    private function MigrateAccessories(Database $legacyDatabase, Database $currentDatabase)
    {
        $accessoriesMigrated = 0;

        $accessoryRepo = new AccessoryRepository();

        $getExisting = new AdHocCommand('select legacyid from accessories where legacyid is not null');
        $reader = $currentDatabase->Query($getExisting);

        $knownIds = [];
        while ($row = $reader->GetRow()) {
            $knownIds[] = $row['legacyid'];
            $accessoriesMigrated++;
        }
        Log::Debug('Start migrating accessories. Starting at row %s', $accessoriesMigrated);

        $getAccessories = new AdHocCommand("select resourceid, name, number_available from additional_resources
		 order by resourceid limit $accessoriesMigrated, 500");

        $reader = $legacyDatabase->Query($getAccessories);

        while ($row = $reader->GetRow()) {
            if (in_array($row['resourceid'], $knownIds)) {
                continue;
            }
            $newId = $accessoryRepo->Add(new Accessory(null, $row['name'], max(0, $row['number_available'])));

            $currentDatabase->Execute(new AdHocCommand("update accessories set legacyid = \"{$row['resourceid']}\" where accessory_id = $newId"));

            $accessoriesMigrated++;
            MigrationSession::SetLastAccessoryRow($accessoriesMigrated);
        }

        Log::Debug('Done migrating accessories (%s accessories)', $accessoriesMigrated);
        $getLegacyCount = new AdHocCommand('select count(*) as count from additional_resources');
        $getMigratedCount = new AdHocCommand('select count(*) as count from accessories where legacyid is not null');

        $progressCounts = $this->GetProgressCounts($getLegacyCount, $getMigratedCount);

        $this->page->SetProgress($progressCounts);
        $this->page->SetAccessoriesMigrated($progressCounts->MigratedCount);
        MigrationSession::SetLastAccessoryRow($progressCounts->MigratedCount);
    }

    private function MigrateGroups(Database $legacyDatabase, Database $currentDatabase)
    {
        $groupsMigrated = 0;
        $groupRepo = new GroupRepository();

        $getExisting = new AdHocCommand('select legacyid from groups where legacyid is not null');
        $reader = $currentDatabase->Query($getExisting);

        $knownIds = [];
        while ($row = $reader->GetRow()) {
            $knownIds[] = $row['legacyid'];
            $groupsMigrated++;
        }

        Log::Debug('Start migrating groups. Starting at row %s', $groupsMigrated);

        $getGroups = new AdHocCommand("select groupid, group_name from groups order by groupid limit $groupsMigrated, 500");

        $reader = $legacyDatabase->Query($getGroups);

        while ($row = $reader->GetRow()) {
            if (in_array($row['groupid'], $knownIds)) {
                continue;
            }

            $newId = $groupRepo->Add(new Group(null, $row['group_name']));

            $currentDatabase->Execute(new AdHocCommand("update groups set legacyid = \"{$row['groupid']}\" where group_id = $newId"));

            $groupsMigrated++;
            MigrationSession::SetLastGroupRow($groupsMigrated);
        }

        Log::Debug('Done migrating groups (%s groups)', $groupsMigrated);

        $getLegacyCount = new AdHocCommand('select count(*) as count from groups');
        $getMigratedCount = new AdHocCommand('select count(*) as count from groups where legacyid is not null');

        $progressCounts = $this->GetProgressCounts($getLegacyCount, $getMigratedCount);
        $this->page->SetProgress($progressCounts);

        $this->page->SetGroupsMigrated($progressCounts->MigratedCount);
        MigrationSession::SetLastGroupRow($progressCounts->MigratedCount);
    }

    private function MigrateUsers(Database $legacyDatabase, Database $currentDatabase)
    {
        $usersMigrated = 0;

        $getExisting = new AdHocCommand('select legacyid from users where legacyid is not null order by legacyid');
        $reader = $currentDatabase->Query($getExisting);

        $knownIds = [];
        while ($row = $reader->GetRow()) {
            $knownIds[] = $row['legacyid'];
            $usersMigrated++;
        }

        Log::Debug('Start migrating users. Starting at row %s', $usersMigrated);

        $getGroups = new AdHocCommand('select groupid, memberid from user_groups');
        $reader = $legacyDatabase->Query($getGroups);

        $userGroups = [];
        while ($row = $reader->GetRow()) {
            $memberId = $row['memberid'];
            if (!array_key_exists($memberId, $userGroups)) {
                $userGroups[$memberId] = [];
            }
            $userGroups[$memberId][] = $row['groupid'];
        }

        $getGroupMapping = new AdHocCommand('select group_id, legacyid from groups');
        $reader = $currentDatabase->Query($getGroupMapping);
        $groupMap = [];
        while ($row = $reader->GetRow()) {
            $groupMap[$row['legacyid']] = $row['group_id'];
        }

        $getResourceMapping = new AdHocCommand('select resource_id, legacyid from resources');
        $reader = $currentDatabase->Query($getResourceMapping);
        $resourceMap = [];
        while ($row = $reader->GetRow()) {
            $resourceMap[$row['legacyid']] = $row['resource_id'];
        }

        $getUsers = new AdHocCommand("select memberid, email, password, fname, lname, phone, institution, position, e_add, e_mod, e_del, e_app, e_html, logon_name, is_admin, lang, timezone
		from login order by memberid limit $usersMigrated, 100");
        $reader = $legacyDatabase->Query($getUsers);

        while ($row = $reader->GetRow()) {
            $legacyId = $row['memberid'];
            if (in_array($legacyId, $knownIds)) {
                Log::Debug("Skipping user %s", $legacyId);
                continue;
            }

            $registerCommand = new RegisterUserCommand(
                $row['logon_name'],
                $row['email'],
                $row['fname'],
                $row['lname'],
                '',
                '',
                Configuration::Instance()->GetDefaultTimezone(),
                empty($row['lang']) ? Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_LANGUAGE) : $row['lang'],
                Pages::DEFAULT_HOMEPAGE_ID,
                $row['phone'],
                $row['institution'],
                $row['position'],
                AccountStatus::ACTIVE,
                null,
                null,
                null
            );

            $newId = ServiceLocator::GetDatabase()->ExecuteInsert($registerCommand);
            $legacypassword = $row['password'];
            $currentDatabase->Execute(new AdHocCommand("update users set legacyid = \"$legacyId\", legacypassword=\"$legacypassword\" where user_id = $newId"));

            // migrate group assignments
            if (array_key_exists($legacyId, $userGroups)) {
                foreach ($userGroups[$legacyId] as $legacyGroupId) {
                    $newGroupId = $groupMap[$legacyGroupId];
                    $currentDatabase->ExecuteInsert(new AddUserGroupCommand($newId, $newGroupId));
                }
            }

            $getPermissions = new AdHocCommand("select * from permission where memberid='$legacyId'");
            $permissionReader = $legacyDatabase->Query($getPermissions);
            $insertPermissionSqls = [];
            while ($row = $permissionReader->GetRow()) {
                $machId = $row['machid'];
                if (array_key_exists($machId, $resourceMap)) {
                    $resourceId = $resourceMap[$machId];
                    $insertPermissionSqls[] = "($resourceId, $newId)";
                }
            }

            if (!empty($insertPermissionSqls)) {
                $insertPermission = "insert ignore into user_resource_permissions (resource_id, user_id) values " . implode(',', $insertPermissionSqls);
                //				die($insertPermission);
                $currentDatabase->ExecuteInsert(new AdHocCommand($insertPermission));
            }

            $usersMigrated++;
            MigrationSession::SetLastUserRow($usersMigrated);
        }

        Log::Debug('Done migrating users (%s users)', $usersMigrated);

        $getLegacyCount = new AdHocCommand('select count(*) as count from login');
        $getMigratedCount = new AdHocCommand('select count(*) as count from users where legacyid is not null');

        $progressCounts = $this->GetProgressCounts($getLegacyCount, $getMigratedCount);
        $this->page->SetProgress($progressCounts);

        $this->page->SetUsersMigrated($progressCounts->MigratedCount);
        MigrationSession::SetLastUserRow($progressCounts->MigratedCount);
    }

    private function MigrateReservations(Database $legacyDatabase, Database $currentDatabase)
    {
        $reservationsMigrated = 0;
        $getMigratedCount = new AdHocCommand('SELECT
				(select count(*) from reservation_series where legacyid is not null) +
				(select count(*) from blackout_series where legacyid is not null )
				as count');

        $reader = ServiceLocator::GetDatabase()->Query($getMigratedCount);
        if ($row = $reader->GetRow()) {
            $reservationsMigrated = $row['count'];
        }

        Log::Debug('Start migrating reservations. Starting at row %s', $reservationsMigrated);

        $reservationRepository = new ReservationRepository();
        $blackoutRepository = new BlackoutRepository();

        $getLegacyReservations = new AdHocCommand("select r.resid, machid, scheduleid, start_date, end_date,
            starttime, endtime, created, modified, parentid, is_blackout, is_pending, summary, allow_participation, allow_anon_participation,
            ru.memberid
            FROM reservations r INNER JOIN reservation_users ru ON r.resid = ru.resid AND owner = 1
            ORDER BY r.resid LIMIT $reservationsMigrated, 100");

        $getExisting = new AdHocCommand('select legacyid from reservation_series where legacyid is not null');
        $reader = $currentDatabase->Query($getExisting);

        $knownIds = [];
        while ($row = $reader->GetRow()) {
            $knownIds[] = $row['legacyid'];
        }

        $getLegacyReservationAccessories = new AdHocCommand('SELECT resid, resourceid from reservation_resources');
        $getLegacyReservationParticipants = new AdHocCommand('SELECT resid, memberid, owner, invited  FROM reservation_users WHERE (owner is null or owner = 0)');

        $getAccessoryMapping = new AdHocCommand('select accessory_id, legacyid from accessories');
        $getUserMapping = new AdHocCommand('select user_id, legacyid from users');
        $getResourceMapping = new AdHocCommand('select resource_id, legacyid from resources');

        $accessoryMapping = [];
        $accessoryMappingReader = $currentDatabase->Query($getAccessoryMapping);
        while ($row = $accessoryMappingReader->GetRow()) {
            $legacyId = $row['legacyid'];
            $accessoryMapping[$legacyId] = $row['accessory_id'];
        }

        $userMapping = [];
        $userMappingReader = $currentDatabase->Query($getUserMapping);
        while ($row = $userMappingReader->GetRow()) {
            $legacyId = $row['legacyid'];
            $userMapping[$legacyId] = $row['user_id'];
        }

        $resourceMapping = [];
        $resourceMappingReader = $currentDatabase->Query($getResourceMapping);
        while ($row = $resourceMappingReader->GetRow()) {
            $legacyId = $row['legacyid'];
            $resourceMapping[$legacyId] = $row['resource_id'];
        }

        $reservationAccessories = [];
        $legacyAccessoryReader = $legacyDatabase->Query($getLegacyReservationAccessories);
        while ($row = $legacyAccessoryReader->GetRow()) {
            $resId = $row['resid'];
            if (!array_key_exists($resId, $reservationAccessories)) {
                $reservationAccessories[$resId] = [];
            }
            $reservationAccessories[$resId][] = $row['resourceid'];
        }

        $reservationParticipants = [];
        $legacyParticipantReader = $legacyDatabase->Query($getLegacyReservationParticipants);
        while ($row = $legacyParticipantReader->GetRow()) {
            $resId = $row['resid'];
            if (!array_key_exists($resId, $reservationParticipants)) {
                $reservationParticipants[$resId] = [];
            }
            $reservationParticipants[$resId][] = ['id' => $row['memberid'], 'invited' => $row['invited']];
        }

        $legacyReservationReader = $legacyDatabase->Query($getLegacyReservations);
        while ($row = $legacyReservationReader->GetRow()) {
            $legacyId = $row['resid'];

            if (in_array($legacyId, $knownIds)) {
                continue;
            }

            $date = $this->BuildDateRange($row['start_date'], $row['starttime'], $row['end_date'], $row['endtime']);

            $mappedUserId = $userMapping[$row['memberid']];
            $mappedResourceId = $resourceMapping[$row['machid']];

            if ($row['is_blackout'] == 1) {
                // handle blackout
                $blackout = BlackoutSeries::Create($mappedUserId, '', $date);
                $blackout->AddResourceId($mappedResourceId);

                $newId = $blackoutRepository->Add($blackout);
                $currentDatabase->Execute(new AdHocCommand("update blackout_series set legacyid = \"$legacyId\" where blackout_series_id = $newId"));
            } else {
                // handle reservation

                $mappedParticipantIds = [];
                $mappedInviteeIds = [];
                if (array_key_exists($legacyId, $reservationParticipants)) {
                    $legacyParticipants = $reservationParticipants[$legacyId];
                    foreach ($legacyParticipants as $legacyParticipantId) {
                        $userId = $userMapping[$legacyParticipantId['id']];
                        if (empty($legacyParticipantId['invited'])) {
                            $mappedParticipantIds[] = $userId;
                        } else {
                            $mappedInviteeIds[] = $userId;
                        }
                    }
                }

                $mappedAccessoryList = [];
                if (array_key_exists($legacyId, $reservationAccessories)) {
                    $legacyAccessories = $reservationAccessories[$legacyId];
                    foreach ($legacyAccessories as $legacyAccessoryId) {
                        if (array_key_exists($legacyAccessoryId, $accessoryMapping)) {
                            $mappedAccessoryId = $accessoryMapping[$legacyAccessoryId];
                            $mappedAccessoryList[] = new ReservationAccessory($mappedAccessoryId, 1);
                        }
                    }
                }

                $currentUser = new UserSession($mappedUserId);
                $currentUser->Timezone = Configuration::Instance()->GetDefaultTimezone();
                $mappedResource = new MigrateBookableResource($mappedResourceId);

                $reservation = ReservationSeries::Create(
                    $mappedUserId,
                    $mappedResource,
                    '',
                    $row['summary'],
                    $date,
                    new RepeatNone(),
                    $currentUser
                );
                foreach ($mappedAccessoryList as $accessory) {
                    $reservation->AddAccessory($accessory);
                }

                $reservation->ChangeParticipants($mappedParticipantIds);
                $reservation->ChangeInvitees($mappedInviteeIds);

                try {
                    $reservationRepository->Add($reservation);

                    $newId = $reservation->SeriesId();
                    $currentDatabase->Execute(new AdHocCommand("update reservation_series set legacyid = \"$legacyId\" where series_id = $newId"));
                } catch (Exception $ex) {
                    Log::Error('Error migrating reservation %s. Exception: %s', $legacyId, $ex);
                }
            }

            $reservationsMigrated++;
            MigrationSession::SetLastReservationRow($reservationsMigrated);
        }

        Log::Debug('Done migrating reservations (%s reservations)', $reservationsMigrated);
        $getLegacyCount = new AdHocCommand('select count(*) as count from reservations');
        //		$getMigratedCount = new AdHocCommand('SELECT
        //		(select count(*) from reservation_series where legacyid is not null) +
        //		(select count(*) from blackout_series where legacyid is not null )
        //		as count');

        $progressCounts = $this->GetProgressCounts($getLegacyCount, $getMigratedCount);
        $this->page->SetProgress($progressCounts);

        Log::Debug('There are %s total legacy reservations and %s already migrated. Progress is %s', $progressCounts->LegacyCount, $progressCounts->MigratedCount, $progressCounts->PercentComplete);

        $this->page->SetReservationsMigrated($progressCounts->MigratedCount);
        MigrationSession::SetLastReservationRow($progressCounts->MigratedCount);
    }

    private function CreateAvailableTimeSlots($start, $end, $interval)
    {
        $times = '';
        for ($time = $start; $time < $end; $time += $interval) {
            $startTime = $time;
            $startString = $this->MinutesToTime($startTime);

            $endTime = $time + $interval;
            $endString = $this->MinutesToTime($endTime);

            $times .= "$startString - $endString\n";
        }

        return $times;
    }

    private function CreateUnavailableTimeSlots($start, $end, $interval)
    {
        $times = '';
        for ($time = 0; $time < $start; $time += $interval) {
            $startTime = $time;
            $startString = $this->MinutesToTime($startTime);

            $endTime = $time + $interval;
            $endString = $this->MinutesToTime($endTime);

            $times .= "$startString - $endString\n";
        }

        for ($time = $end; $time < 1440; $time += $interval) {
            $startTime = $time;
            $startString = $this->MinutesToTime($startTime);

            $endTime = $time + $interval;
            $endString = $this->MinutesToTime($endTime);

            $times .= "$startString - $endString\n";
        }

        return $times;
    }

    private function MinutesToTime($minutes)
    {
        $hour = intval($minutes / 60);
        $min = $minutes % 60;

        $hour = $hour % 24;

        return "$hour:$min";
    }

    private function BuildDateRange($startDate, $startTime, $endDate, $endTime)
    {
        $s = date('Y-m-d', $startDate) . ' ' . $this->MinutesToTime($startTime);
        $e = date('Y-m-d', $endDate) . ' ' . $this->MinutesToTime($endTime);

        return DateRange::Create($s, $e, Configuration::Instance()->GetDefaultTimezone());
    }

    private function GetProgressCounts($legacyCountCommand, $migratedCountCommand)
    {
        $legacyCount = 0;
        $migratedCount = 0;

        $legacyDb = new Database($this->GetLegacyConnection());
        $reader = $legacyDb->Query($legacyCountCommand);
        if ($row = $reader->GetRow()) {
            $legacyCount = $row['count'];
        }

        $reader = ServiceLocator::GetDatabase()->Query($migratedCountCommand);
        if ($row = $reader->GetRow()) {
            $migratedCount = $row['count'];
        }

        return new ProgressCounts($legacyCount, $migratedCount);
    }
}

class ProgressCounts
{
    public $LegacyCount = 0;
    public $MigratedCount = 0;
    public $RemainingCount = 0;
    public $PercentComplete = 0;

    public function __construct($legacyCount, $migratedCount)
    {
        $this->LegacyCount = $legacyCount;
        $this->MigratedCount = $migratedCount;
        $this->RemainingCount = $legacyCount - $migratedCount;
        if ($legacyCount > 0) {
            $this->PercentComplete = round((($migratedCount / $legacyCount) * 100), 2);
        } else {
            $this->PercentComplete = 100;
        }
    }
}

class MigrateBookableResource extends BookableResource
{
    public function __construct($resourceId)
    {
        $this->_resourceId = $resourceId;
    }
}

$page = new MigrationPage();
$page->PageLoad();
