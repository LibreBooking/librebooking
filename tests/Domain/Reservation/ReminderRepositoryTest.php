<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/ReminderRepository.php');

class ReminderRepositoryTest extends TestBase
{
    /**
     * @var ReminderRepository
     */
    public $repository;

    public function setUp(): void
    {
        parent::setup();
        $this->repository = new ReminderRepository();
    }

    public function testGetsAllReservationsWithReminderDateOfThisMinute()
    {
        $seriesId = 123;
        $instanceId = 456;
        $referenceNumber = 'refnum1';
        $startDate = Date::Now()->AddDays(1)->ToDatabase();
        $endDate = Date::Now()->AddDays(2)->ToDatabase();
        $title = 'reservation title';
        $description = 'reservation description';
        $resourceName = 'resource name';
        $emailAddress = 'e@m.com';
        $fname = 'first';
        $lname = 'last';
        $timezone = 'America/Chicago';
        $reminder_minutes = 100;
        $now = Date::Now();
        $language = 'en_us';

        $row1 = new ReminderNoticeRow($seriesId, $instanceId, $referenceNumber, $startDate, $endDate, $title, $description, $resourceName, $emailAddress, $fname, $lname, $timezone, $reminder_minutes, $language);
        $row2 = new ReminderNoticeRow();
        $rows = array_merge(
            $row1->Rows(),
            $row2->Rows()
        );
        $this->db->SetRows($rows);

        $reminderNotices = $this->repository->GetReminderNotices($now, ReservationReminderType::Start);

        $expectedCommand = new GetReminderNoticesCommand($now->ToTheMinute(), ReservationReminderType::Start);

        $this->assertEquals(2, count($reminderNotices));
        $this->assertEquals($expectedCommand, $this->db->_LastCommand);

        $expectedReminderNotice = ReminderNotice::FromRow($rows[0]);

        $this->assertEquals($expectedReminderNotice, $reminderNotices[0]);
    }
}
