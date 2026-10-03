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

    public function setUp(): void
    {
        parent::setUp();
        $this->fakeResources->SetDateFormat('short_datetime', 'Y-m-d H:i');
    }

    public function testUsersCsvOutputIsUnchanged(): void
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

    public function testResourcesCsvOutputIsUnchanged(): void
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

    private const EXPECTED_USERS_CSV = <<<'CSV'
"FirstName","LastName","Username","Email","Phone","Organization","Position","Created","LastLogin","Status","Credits","Color","Timezone","Language","Groups","Badge Number","Owner's Note"
"Mary 'Mae'","O'Brien, Jr.","mobrien","mary@example.com","555-0100 ""work""","Acme 'Labs'","Lead 'Tech'","2026-01-15 08:30","2026-09-30 17:05","Active","12","#ff0000","America/Chicago","en_us","Staff ""A"",Guests 'B'","B-42","It's mine"
"Bob","Smith","bsmith","bob@example.com","","","","2025-12-01 00:00","","Inactive","","","UTC","en_us","","",""
CSV . "\n";

    private const EXPECTED_RESOURCES_CSV = <<<'CSV'
"Name","Status","Schedule","ResourceType","SortOrder","Location","Contact","Description","Notes","ResourceAdministrator","ResourceColor","ResourceMinLengthCsv","ResourceMaxLengthCsv","ResourceBufferTimeCsv","ResourceAllowMultiDay","Capacity","ResourceGroups","ResourceMinNoticeAddCsv","ResourceMinNoticeUpdateCsv","ResourceMinNoticeDeleteCsv","ResourceMaxNotice","ResourceRequiresApproval","ResourcePermissionAutoGranted","RequiresCheckInNotification","AutoReleaseMinutes","CreditsOffPeak","CreditsPeak","MaximumConcurrentReservations,"Badge Number","Owner's Note"
"Conference 'A', North","Available","Main ""East"" Schedule","Room 'Large'",3,"Bldg 'One'","x1234","Big ""room"" with 'view'","Bring 'adapter'","Admins 'R' Us","#00ff00","30 minutes","2 hours","10 minutes","1","12","Building 1,Floor 'Two'","1 hours","30 minutes","15 minutes","1 days","1","1","1","15","2","4","R-1","Owner's"
"Projector","Unavailable","Main ""East"" Schedule","",0,"","","","","","","","","","","","","","","","","0","","","","0","0","",""
"Old Lab","Hidden","Main ""East"" Schedule","",0,"","","","","","","","","","","","","","","","","0","","","","0","0","",""
CSV . "\n";
}
