<?php

require_once(ROOT_DIR . 'Domain/Access/AnnouncementRepository.php');

class FakeAnnouncementRepository implements IAnnouncementRepository
{
    public $_GetFutureCalled = false;
    public $_ExpectedAnnouncements;

    public function __construct()
    {
        $this->_ExpectedAnnouncements = $this->GetExpectedRows();
    }

    public function GetRows()
    {
        return [
            [
                ColumnNames::ANNOUNCEMENT_ID => 1,
                ColumnNames::ANNOUNCEMENT_TEXT => 'announcement 1',
                ColumnNames::ANNOUNCEMENT_START => null,
                ColumnNames::ANNOUNCEMENT_END => null,
                ColumnNames::ANNOUNCEMENT_PRIORITY => null,
                ColumnNames::GROUP_IDS => null,
                ColumnNames::RESOURCE_IDS => null,
                ColumnNames::ANNOUNCEMENT_DISPLAY_PAGE => 1,
            ],
            [
                ColumnNames::ANNOUNCEMENT_ID => 1,
                ColumnNames::ANNOUNCEMENT_TEXT => 'announcement 2',
                ColumnNames::ANNOUNCEMENT_START => null,
                ColumnNames::ANNOUNCEMENT_END => null,
                ColumnNames::ANNOUNCEMENT_PRIORITY => null,
                ColumnNames::GROUP_IDS => null,
                ColumnNames::RESOURCE_IDS => null,
                ColumnNames::ANNOUNCEMENT_DISPLAY_PAGE => 1,
            ],
            [
                ColumnNames::ANNOUNCEMENT_ID => 1,
                ColumnNames::ANNOUNCEMENT_TEXT => 'announcement 3',
                ColumnNames::ANNOUNCEMENT_START => null,
                ColumnNames::ANNOUNCEMENT_END => null,
                ColumnNames::ANNOUNCEMENT_PRIORITY => null,
                ColumnNames::GROUP_IDS => null,
                ColumnNames::RESOURCE_IDS => null,
                ColumnNames::ANNOUNCEMENT_DISPLAY_PAGE => 1,
            ],
        ];
    }

    public function GetFuture($displayPage = -1)
    {
        $this->_GetFutureCalled = true;
        return $this->_ExpectedAnnouncements;
    }

    public function GetExpectedRows()
    {
        $expectedAnnouncements = [];
        $rows = $this->GetRows();
        foreach ($rows as $item) {
            $expectedAnnouncements[] = $item[ColumnNames::ANNOUNCEMENT_TEXT];
        }

        return $expectedAnnouncements;
    }

    public function GetAll($sortField = null, $sortDirection = null)
    {
        return null;
    }

    /**
     * @param Announcement $announcement
     */
    public function Add(Announcement $announcement)
    {
    }

    /**
     * @param int $announcementId
     */
    public function Delete($announcementId)
    {
    }

    /**
     * @param Announcement $announcement
     */
    public function Update(Announcement $announcement)
    {
    }

    /**
     * @param int $announcementId
     * @return Announcement
     */
    public function LoadById($announcementId)
    {
        return null;
    }
}
