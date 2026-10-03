<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');
require_once(ROOT_DIR . 'Domain/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

/**
 * Tests for the admin CSV export templates. The expected strings pin the exact
 * bytes rendered so template refactoring cannot silently change the exported
 * data.
 */
class ExportCsvTemplatesTest extends TestBase
{
    private const ADMIN_GROUP_ID = 10;
    private const RESOURCE_TYPE_ID = 3;
    private const SCHEDULE_ID = 7;
    private const RESOURCE_GROUP_ID_A = 5;
    private const RESOURCE_GROUP_ID_B = 6;
    private const USER_GROUP_ID_A = 20;
    private const USER_GROUP_ID_B = 21;
    private const ATTRIBUTE_ID_A = 100;
    private const ATTRIBUTE_ID_B = 101;
    private const EXPORT_GROUP_ID_A = 30;
    private const EXPORT_GROUP_ID_B = 31;
    private const RESOURCE_ID_A = 40;
    private const RESOURCE_ID_B = 41;

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeResources->SetDateFormat('short_datetime', 'Y-m-d H:i');
        $this->fakeResources->SetDateFormat('res_popup', 'D, m/d g:i A');
        $this->fakeResources->SetDateFormat('general_datetime', 'Y-m-d H:i:s');
    }

    public function testUsersCsvRendersExpectedCsv(): void
    {
        $page = new SmartyPage();
        $page->assign('AttributeList', $this->attributes(category: CustomAttributeCategory::USER));
        $page->assign('users', $this->users());
        $page->assign('statusDescriptions', [
            AccountStatus::ALL => 'All',
            AccountStatus::ACTIVE => 'Active',
            AccountStatus::AWAITING_ACTIVATION => 'Pending',
            AccountStatus::INACTIVE => 'Inactive',
        ]);
        $page->assign('Groups', [
            self::USER_GROUP_ID_A => new Group(self::USER_GROUP_ID_A, 'Staff "A"'),
            self::USER_GROUP_ID_B => new Group(self::USER_GROUP_ID_B, "Guests 'B'"),
        ]);

        $output = $page->fetch('Admin/Users/users_csv.tpl');

        $this->assertSame(self::EXPECTED_USERS_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    public function testResourcesCsvRendersExpectedCsv(): void
    {
        $page = new SmartyPage();
        $page->assign('AttributeList', $this->attributes(category: CustomAttributeCategory::RESOURCE));
        $page->assign('Resources', $this->resources());
        $page->assign('Schedules', [self::SCHEDULE_ID => 'Main "East" Schedule']);
        $page->assign('ResourceTypes', [
            self::RESOURCE_TYPE_ID => new ResourceType(self::RESOURCE_TYPE_ID, "Room 'Large'", 'description'),
        ]);
        $page->assign('GroupLookup', [
            self::ADMIN_GROUP_ID => new GroupItemView(self::ADMIN_GROUP_ID, "Admins 'R' Us"),
        ]);
        $page->assign('ResourceGroupList', [
            self::RESOURCE_GROUP_ID_A => new ResourceGroup(self::RESOURCE_GROUP_ID_A, 'Building 1'),
            self::RESOURCE_GROUP_ID_B => new ResourceGroup(self::RESOURCE_GROUP_ID_B, "Floor 'Two'"),
        ]);

        $output = $page->fetch('Admin/Resources/resources_csv.tpl');

        $this->assertSame(self::EXPECTED_RESOURCES_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    public function testGroupsCsvRendersExpectedCsv(): void
    {
        $page = new SmartyPage();
        $page->assign('Groups', $this->exportGroups());
        $page->assign('Users', [
            self::EXPORT_GROUP_ID_A => [
                $this->userWithEmail(email: "o'brien@example.com"),
                $this->userWithEmail(email: 'bob@example.com'),
            ],
            self::EXPORT_GROUP_ID_B => [],
        ]);
        $page->assign('PermissionsWrite', [
            self::EXPORT_GROUP_ID_A => [
                $this->permission(groupId: self::EXPORT_GROUP_ID_A, resourceId: self::RESOURCE_ID_A, resourceName: "Room 'A'"),
                $this->permission(groupId: self::EXPORT_GROUP_ID_A, resourceId: self::RESOURCE_ID_B, resourceName: 'Projector "X"'),
            ],
            self::EXPORT_GROUP_ID_B => [],
        ]);
        $page->assign('PermissionsRead', [
            self::EXPORT_GROUP_ID_A => [],
            self::EXPORT_GROUP_ID_B => [
                $this->permission(groupId: self::EXPORT_GROUP_ID_B, resourceId: self::RESOURCE_ID_A, resourceName: "Room 'A'"),
            ],
        ]);

        $output = $page->fetch('Admin/Groups/groups_csv.tpl');

        $this->assertSame(self::EXPECTED_GROUPS_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    public function testGroupsCsvImportTemplateRendersHeaderOnly(): void
    {
        $page = new SmartyPage();

        $output = $page->fetch('Admin/Groups/groups_csv.tpl');

        $this->assertSame(self::EXPECTED_GROUPS_TEMPLATE_CSV, $output);
    }

    public function testReservationsCsvRendersExpectedCsv(): void
    {
        $page = new SmartyPage();
        $page->assign('ReservationAttributes', $this->attributes(category: CustomAttributeCategory::RESERVATION));
        $page->assign('reservations', $this->reservations());
        $page->assign('Timezone', 'UTC');

        $output = $page->fetch('Admin/Reservations/reservations_csv.tpl');

        $this->assertSame(self::EXPECTED_RESERVATIONS_CSV, $output);
        $this->assertEveryRowMatchesHeaderColumnCount($output);
    }

    private function assertEveryRowMatchesHeaderColumnCount(string $csv): void
    {
        $handle = fopen('php://memory', 'r+');
        fwrite($handle, $csv);
        rewind($handle);

        $rows = [];
        while (($row = fgetcsv($handle, null, ',', '"', '')) !== false) {
            $rows[] = $row;
        }
        fclose($handle);

        $headerCount = count($rows[0]);
        foreach ($rows as $index => $row) {
            $this->assertCount($headerCount, $row, "CSV row $index does not match the header column count");
        }
    }

    /**
     * @return CustomAttribute[]
     */
    private function attributes(int $category): array
    {
        return [
            new CustomAttribute(
                id: self::ATTRIBUTE_ID_A,
                label: 'Badge Number',
                type: CustomAttributeTypes::SINGLE_LINE_TEXTBOX,
                category: $category,
                regex: null,
                required: false,
                possibleValues: null,
                sortOrder: 1
            ),
            new CustomAttribute(
                id: self::ATTRIBUTE_ID_B,
                label: "Owner's Note",
                type: CustomAttributeTypes::MULTI_LINE_TEXTBOX,
                category: $category,
                regex: null,
                required: false,
                possibleValues: null,
                sortOrder: 2
            ),
        ];
    }

    /**
     * @return UserItemView[]
     */
    private function users(): array
    {
        $full = new UserItemView();
        $full->Id = 1;
        $full->First = "Mary 'Mae'";
        $full->Last = 'O\'Brien, Jr.';
        $full->Username = 'mobrien';
        $full->Email = 'mary@example.com';
        $full->Phone = '555-0100 "work"';
        $full->Organization = "Acme 'Labs'";
        $full->Position = "Lead 'Tech'";
        $full->DateCreated = Date::Parse('2026-01-15 08:30:00', 'UTC');
        $full->LastLogin = Date::Parse('2026-09-30 17:05:00', 'UTC');
        $full->StatusId = AccountStatus::ACTIVE;
        $full->CurrentCreditCount = 12;
        $full->ReservationColor = '#ff0000';
        $full->Timezone = 'America/Chicago';
        $full->Language = 'en_us';
        $full->GroupIds = [self::USER_GROUP_ID_A, self::USER_GROUP_ID_B];
        $full->Attributes->Add(self::ATTRIBUTE_ID_A, 'B-42');
        $full->Attributes->Add(self::ATTRIBUTE_ID_B, "It's mine");

        $minimal = new UserItemView();
        $minimal->Id = 2;
        $minimal->First = 'Bob';
        $minimal->Last = 'Smith';
        $minimal->Username = 'bsmith';
        $minimal->Email = 'bob@example.com';
        $minimal->DateCreated = Date::Parse('2025-12-01 00:00:00', 'UTC');
        $minimal->LastLogin = NullDate::Instance();
        $minimal->StatusId = AccountStatus::INACTIVE;
        $minimal->Timezone = 'UTC';
        $minimal->Language = 'en_us';

        return [$full, $minimal];
    }

    /**
     * @return BookableResource[]
     */
    private function resources(): array
    {
        $full = new BookableResource(
            resourceId: 1,
            name: "Conference 'A', North",
            location: "Bldg 'One'",
            contact: 'x1234',
            notes: "Bring 'adapter'",
            minLength: 1800,
            maxLength: 7200,
            autoAssign: true,
            requiresApproval: true,
            allowMultiday: true,
            maxParticipants: 12,
            minNoticeAdd: 3600,
            maxNotice: 86400,
            description: 'Big "room" with \'view\'',
            scheduleId: self::SCHEDULE_ID,
            adminGroupId: self::ADMIN_GROUP_ID,
            minNoticeUpdate: 1800,
            minNoticeDelete: 900,
            bufferTime: 600,
            groupIds: [self::RESOURCE_GROUP_ID_A, self::RESOURCE_GROUP_ID_B],
            resourceTypeId: self::RESOURCE_TYPE_ID,
        );
        $full->SetSortOrder(3);
        $full->SetColor('#00ff00');
        $full->SetCheckin(true, 15);
        $full->SetCreditsPerSlot(2);
        $full->SetPeakCreditsPerSlot(4);
        $full->SetMaxConcurrentReservations(3);
        $full->ChangeStatus(ResourceStatus::AVAILABLE);
        $full->WithAttribute(new AttributeValue(self::ATTRIBUTE_ID_A, 'R-1'));
        $full->WithAttribute(new AttributeValue(self::ATTRIBUTE_ID_B, "Owner's"));

        $noAdminGroup = new BookableResource(
            resourceId: 2,
            name: 'Projector',
            location: null,
            contact: null,
            notes: null,
            minLength: null,
            maxLength: null,
            autoAssign: false,
            requiresApproval: false,
            allowMultiday: false,
            maxParticipants: null,
            minNoticeAdd: null,
            maxNotice: null,
            scheduleId: self::SCHEDULE_ID,
        );
        $noAdminGroup->ChangeStatus(ResourceStatus::UNAVAILABLE);

        $hidden = new BookableResource(
            resourceId: 3,
            name: 'Old Lab',
            location: null,
            contact: null,
            notes: null,
            minLength: null,
            maxLength: null,
            autoAssign: false,
            requiresApproval: false,
            allowMultiday: false,
            maxParticipants: null,
            minNoticeAdd: null,
            maxNotice: null,
            scheduleId: self::SCHEDULE_ID,
        );
        $hidden->ChangeStatus(ResourceStatus::HIDDEN);

        return [$full, $noAdminGroup, $hidden];
    }

    /**
     * @return GroupItemView[]
     */
    private function exportGroups(): array
    {
        return [
            new GroupItemView(
                groupId: self::EXPORT_GROUP_ID_A,
                groupName: 'Staff "A"',
                adminGroupName: "Admins 'R' Us",
                isDefault: 1,
                roles: [RoleLevel::APPLICATION_ADMIN, RoleLevel::RESOURCE_ADMIN]
            ),
            new GroupItemView(
                groupId: self::EXPORT_GROUP_ID_B,
                groupName: "Guests 'B'",
                adminGroupName: null,
                isDefault: 0,
                roles: [RoleLevel::GROUP_ADMIN, RoleLevel::SCHEDULE_ADMIN]
            ),
        ];
    }

    private function userWithEmail(string $email): UserItemView
    {
        $user = new UserItemView();
        $user->Email = $email;

        return $user;
    }

    private function permission(int $groupId, int $resourceId, string $resourceName): GroupResourcePermission
    {
        return GroupResourcePermission::Create([
            ColumnNames::GROUP_ID => $groupId,
            ColumnNames::RESOURCE_ID => $resourceId,
            ColumnNames::RESOURCE_NAME => $resourceName,
            ColumnNames::PERMISSION_TYPE => ResourcePermissionType::Full,
        ]);
    }

    /**
     * @return ReservationItemView[]
     */
    private function reservations(): array
    {
        $full = new ReservationItemView(
            referenceNumber: 'ref-1',
            startDate: Date::Parse('2026-03-02 09:00:00', 'UTC'),
            endDate: Date::Parse('2026-03-02 10:30:00', 'UTC'),
            resourceName: "Room 'A'",
            title: 'Kickoff "Q2"',
            description: "Team's sync, all hands",
            userFirstName: "Mary 'Mae'",
            userLastName: "O'Brien",
        );
        $full->CreatedDate = Date::Parse('2026-02-01 08:00:00', 'UTC');
        $full->ModifiedDate = Date::Parse('2026-02-15 12:30:00', 'UTC');
        $full->CheckinDate = Date::Parse('2026-03-02 09:05:00', 'UTC');
        $full->CheckoutDate = Date::Parse('2026-03-02 10:20:00', 'UTC');
        $full->OriginalEndDate = Date::Parse('2026-03-02 11:00:00', 'UTC');
        $full->Attributes->Add(self::ATTRIBUTE_ID_A, 'B-42');
        $full->Attributes->Add(self::ATTRIBUTE_ID_B, 'Say "cheese"');

        $minimal = new ReservationItemView(
            referenceNumber: 'ref-2',
            startDate: Date::Parse('2026-03-03 14:00:00', 'UTC'),
            endDate: Date::Parse('2026-03-03 15:00:00', 'UTC'),
            resourceName: 'Projector',
            userFirstName: 'Bob',
            userLastName: 'Smith',
        );
        $minimal->CreatedDate = Date::Parse('2026-02-20 10:00:00', 'UTC');

        return [$full, $minimal];
    }

    private const EXPECTED_USERS_CSV = <<<'CSV'
"FirstName","LastName","Username","Email","Phone","Organization","Position","Created","LastLogin","Status","Credits","Color","Timezone","Language","Groups","Badge Number","Owner's Note"
"Mary 'Mae'","O'Brien, Jr.","mobrien","mary@example.com","555-0100 ""work""","Acme 'Labs'","Lead 'Tech'","2026-01-15 08:30","2026-09-30 17:05","Active","12","#ff0000","America/Chicago","en_us","Staff ""A"",Guests 'B'","B-42","It's mine"
"Bob","Smith","bsmith","bob@example.com","","","","2025-12-01 00:00","","Inactive","","","UTC","en_us","","",""
CSV . "\n";

    private const EXPECTED_RESOURCES_CSV = <<<'CSV'
"Name","Status","Schedule","ResourceType","SortOrder","Location","Contact","Description","Notes","ResourceAdministrator","ResourceColor","ResourceMinLengthCsv","ResourceMaxLengthCsv","ResourceBufferTimeCsv","ResourceAllowMultiDay","Capacity","ResourceGroups","ResourceMinNoticeAddCsv","ResourceMinNoticeUpdateCsv","ResourceMinNoticeDeleteCsv","ResourceMaxNotice","ResourceRequiresApproval","ResourcePermissionAutoGranted","RequiresCheckInNotification","AutoReleaseMinutes","CreditsOffPeak","CreditsPeak","MaximumConcurrentReservations","Badge Number","Owner's Note"
"Conference 'A', North","Available","Main ""East"" Schedule","Room 'Large'",3,"Bldg 'One'","x1234","Big ""room"" with 'view'","Bring 'adapter'","Admins 'R' Us","#00ff00","30 minutes","2 hours","10 minutes","1","12","Building 1,Floor 'Two'","1 hours","30 minutes","15 minutes","1 days","1","1","1","15","2","4","3","R-1","Owner's"
"Projector","Unavailable","Main ""East"" Schedule","",0,"","","","","","","","","","","","","","","","","0","","","","0","0","1","",""
"Old Lab","Hidden","Main ""East"" Schedule","",0,"","","","","","","","","","","","","","","","","0","","","","0","0","1","",""
CSV . "\n";

    private const EXPECTED_GROUPS_CSV = <<<'CSV'
"Name","Is Auto Add","Group Administrator","Is Application Admin","Is Group Admin","Is Resource Admin","Is Schedule Admin","Members","Full Permissions","Read Only Permissions"
"Staff "A"","true","Admins \'R\' Us","true","false","true","false","o\'brien@example.com,bob@example.com","Room \'A\',Projector "X"",""
"Guests \'B\'","false","","false","true","false","true","","","Room \'A\'"
CSV . "\n";

    private const EXPECTED_GROUPS_TEMPLATE_CSV = <<<'CSV'
"Name","Is Auto Add","Group Administrator","Is Application Admin","Is Group Admin","Is Resource Admin","Is Schedule Admin","Members","Full Permissions","Read Only Permissions"
CSV . "\n";

    private const EXPECTED_RESERVATIONS_CSV = <<<'CSV'
"User","Resource","Title","Description","BeginDate","EndDate","Duration","Created","LastModified","ReferenceNumber","CheckInTime","CheckOutTime","OriginalEndDate","Badge Number","Owner\'s Note"
"Mary &#039;Mae&#039; O&#039;Brien","Room \'A\'","Kickoff "Q2"","Team\'s sync, all hands","Mon, 03/02 9:00 AM","Mon, 03/02 10:30 AM","1 hours 30 minutes","2026-02-01 08:00:00","2026-02-15 12:30:00","ref-1",2026-03-02 09:05:00,2026-03-02 10:20:00,2026-03-02 11:00:00,"B-42","Say "cheese""
"Bob Smith","Projector","","","Tue, 03/03 2:00 PM","Tue, 03/03 3:00 PM","1 hours","2026-02-20 10:00:00","","ref-2",,,,"",""
CSV . "\n";
}
