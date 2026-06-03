<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Presenters/Dashboard/AnnouncementPresenter.php');
require_once(ROOT_DIR . 'Controls/Dashboard/AnnouncementsControl.php');

class AnnouncementPresenterTest extends TestBase
{
    private $permissionService;
    /**
     * @var AnnouncementPresenter
     */
    private $presenter;

    /**
     * @var FakeAnnouncementsControl
     */
    private $page;

    /**
     * @var FakeAnnouncementRepository
     */
    private $announcements;

    public function setUp(): void
    {
        parent::setup();

        Date::_SetNow(new Date());

        $this->page = new FakeAnnouncementsControl();

        $this->announcements = new FakeAnnouncementRepository();
        $this->permissionService = new FakePermissionService();
        $this->presenter = new AnnouncementPresenter($this->page, $this->announcements, $this->permissionService);
    }

    public function teardown(): void
    {
        parent::teardown();

        Date::_ResetNow();
    }

    public function testShowsAllAnnouncements()
    {
        $now = Date::Now();
        $displayPage = 1;

        $announcement = new Announcement(1, 'text', $now, $now, 1, [], [], $displayPage);
        $this->announcements->_ExpectedAnnouncements = [$announcement];

        $this->presenter->PageLoad();

        $this->assertEquals($this->announcements->_ExpectedAnnouncements, $this->page->_LastAnnouncements);
        $this->assertTrue($this->announcements->_GetFutureCalled);
    }
}

class FakeAnnouncementsControl implements IAnnouncementsControl
{
    public $_LastAnnouncements = [];

    public function SetAnnouncements($announcements)
    {
        $this->_LastAnnouncements = $announcements;
    }
}
