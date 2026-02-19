<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/AnnouncementRepository.php');

class AnnouncementRepositoryTest extends TestBase
{
    /**
     * @var AnnouncementRepository
     */
    private $repository;

    /**
     * @var FakePermissionService
     */
    private $permissionService;

    public function setUp(): void
    {
        parent::setup();

        Date::_SetNow(new Date());

        $this->repository = new AnnouncementRepository();
        $this->permissionService = new FakePermissionService();
    }

    public function teardown(): void
    {
        parent::teardown();

        Date::_ResetNow();
    }

    public function testGetFutureCallDBCorrectly()
    {
        $now = Date::Now();

        $fakeAnc = new FakeAnnouncementRepository();

        $rows = $fakeAnc->GetRows();
        $this->db->SetRow(0, $rows);

        $expectedAnnouncements = [];
        foreach ($rows as $item) {
            $expectedAnnouncements[] = Announcement::FromRow($item);
        }

        $actualRows = $this->repository->GetFuture(1);

        $this->assertEquals(new GetDashboardAnnouncementsCommand($now, 1), $this->db->_Commands[0]);
        $this->assertTrue($this->db->GetReader(0)->_FreeCalled);
        $this->assertEquals($expectedAnnouncements, $actualRows);
    }

    public function testGetsAllAnnouncements()
    {
        $text1 = 'text1';
        $text2 = 'text2';
        $start1 = null;
        $start2 = '2011-01-01';
        $end1 = null;
        $end2 = '2011-01-01';
        $priority1 = 3;
        $priority2 = null;
        $groups1 = '1,2,3';
        $resources1 = '9,10,11';
        $displayPage = 1;

        $rows = [];
        $rows[] = $this->GetAnnouncementRow(1, $text1, $start1, $end1, $priority1, $groups1, $resources1, $displayPage);
        $rows[] = $this->GetAnnouncementRow(2, $text2, $start2, $end2, $priority2);
        $this->db->SetRows($rows);
        $announcements = $this->repository->GetAll();

        $expectedAnnouncements = [
            new Announcement(1, $text1, Date::FromDatabase($start1), Date::FromDatabase($end1), $priority1, [1,2,3], [9,10,11], $displayPage),
            new Announcement(2, $text2, Date::FromDatabase($start2), Date::FromDatabase($end2), $priority2, [], [], $displayPage),
        ];

        $this->assertEquals(new GetAllAnnouncementsCommand(), $this->db->_LastCommand);
        $this->assertEquals($expectedAnnouncements, $announcements);
    }

    public function testAddsAnnouncement()
    {
        $announcementId = 1;
        $this->db->_ExpectedInsertId = $announcementId;
        $text = 'text';
        $start = Date::Parse('2011-01-01', 'America/Chicago');
        $end = NullDate::Instance();
        $priority = 1;
        $groups = [1,2,3];
        $resources = [9,10,11];
        $displayPage = 1;

        $announcement = Announcement::Create($text, $start, $end, $priority, $groups, $resources, $displayPage);

        $this->repository->Add($announcement);
        $this->assertEquals(new AddAnnouncementCommand($text, $start, $end, $priority, $displayPage), $this->db->_Commands[0]);
        $this->assertEquals(new AddAnnouncementGroupCommand($announcementId, $groups[0]), $this->db->_Commands[1]);
        $this->assertEquals(new AddAnnouncementGroupCommand($announcementId, $groups[1]), $this->db->_Commands[2]);
        $this->assertEquals(new AddAnnouncementGroupCommand($announcementId, $groups[2]), $this->db->_Commands[3]);
        $this->assertEquals(new AddAnnouncementResourceCommand($announcementId, $resources[0]), $this->db->_Commands[4]);
        $this->assertEquals(new AddAnnouncementResourceCommand($announcementId, $resources[1]), $this->db->_Commands[5]);
        $this->assertEquals(new AddAnnouncementResourceCommand($announcementId, $resources[2]), $this->db->_Commands[6]);
    }

    public function testDeletesAnnouncement()
    {
        $id = 1232;
        $this->repository->Delete($id);
        $this->assertEquals(new DeleteAnnouncementCommand($id), $this->db->_LastCommand);
    }

    public function testLoadsAnnouncement()
    {
        $text1 = 'text1';
        $start1 = null;
        $end1 = null;
        $priority1 = 3;
        $groups1 = '1,2,3';
        $resources1 = '9,10,11';
        $displayPage = 1;

        $rows = [$this->GetAnnouncementRow(1, $text1, $start1, $end1, $priority1, $groups1, $resources1)];
        $this->db->SetRows($rows);

        $id = 1232;
        $actual = $this->repository->LoadById($id);

        $expected = new Announcement(1, $text1, Date::FromDatabase($start1), Date::FromDatabase($end1), $priority1, [1,2,3], [9, 10, 11], $displayPage);
        $this->assertEquals(new GetAnnouncementByIdCommand($id), $this->db->_LastCommand);
        $this->assertEquals($actual, $expected);
    }

    public function testUpdatesAnnouncement()
    {
        $id = 1232;
        $text1 = 'text1';
        $start1 = Date::Parse('2011-01-01', 'UTC');
        $end1 = Date::Parse('2011-01-01', 'UTC');
        $priority1 = 3;
        $groupIds = [1, 2, 3];
        $resourceIds = [9, 10, 11];
        $displayPage = 1;
        $a = new Announcement($id, $text1, $start1, $end1, $priority1, $groupIds, $resourceIds, $displayPage);

        $this->repository->Update($a);
        $this->assertEquals(new DeleteAnnouncementCommand($id), $this->db->_Commands[0]);
        $this->assertEquals(new AddAnnouncementCommand($text1, $start1, $end1, $priority1, $displayPage), $this->db->_Commands[1]);
    }

    public function testAppliesToUserIfNoGroupsOrResources()
    {
        $displayPage = 1;
        $announcement = Announcement::Create('a', Date::Now(), Date::Now(), 1, [], [], $displayPage);
        $this->assertTrue($announcement->AppliesToUser($this->fakeUser, $this->permissionService));
    }

    public function testAppliesToUserIfResourceAllowed()
    {
        $displayPage = 1;
        $announcement = Announcement::Create('a', Date::Now(), Date::Now(), 1, [], [1,2,3], $displayPage);
        $this->permissionService->ReturnValues = [true, true, true];
        $this->assertTrue($announcement->AppliesToUser($this->fakeUser, $this->permissionService));
    }

    public function testAppliesToUserIfInGroup()
    {
        $displayPage = 1;
        $announcement = Announcement::Create('a', Date::Now(), Date::Now(), 1, [1,2,3], [], $displayPage);
        $this->fakeUser->Groups = [2];
        $this->assertTrue($announcement->AppliesToUser($this->fakeUser, $this->permissionService));
    }

    private function GetAnnouncementRow($id, $text, $startDate, $endDate, $priority, $groups = '', $resources = '', $displayPage = 1)
    {
        return [
            ColumnNames::ANNOUNCEMENT_ID => $id,
            ColumnNames::ANNOUNCEMENT_TEXT => $text,
            ColumnNames::ANNOUNCEMENT_START => $startDate,
            ColumnNames::ANNOUNCEMENT_END => $endDate,
            ColumnNames::ANNOUNCEMENT_PRIORITY => $priority,
            ColumnNames::GROUP_IDS => $groups,
            ColumnNames::RESOURCE_IDS => $resources,
            ColumnNames::ANNOUNCEMENT_DISPLAY_PAGE => $displayPage,
        ];
    }
}
