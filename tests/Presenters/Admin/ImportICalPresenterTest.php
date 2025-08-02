<?php

require_once(ROOT_DIR . 'Presenters/Admin/Import/ICalImportPresenter.php');

class ImportICalPresenterTest extends TestBase
{
    public function testCreatesEverything()
    {
        $page = new FakeICalImportPage();
        $userRepo = new FakeUserRepository();
        $resourceRepo = new FakeResourceAccess();
        $reservationRepo = new FakeReservationRepository();
        $registration = new FakeRegistration();
        $scheduleRepo = new FakeScheduleRepository();

        $page->_File = new FakeUploadedFile();
        $page->_File->Contents = $this->GetEvents();

        $userRepo->_User = new FakeUser(2);

        $resource1 = new FakeBookableResource(1);
        $resource2 = new FakeBookableResource(1);

        $resourceRepo->_NamedResources = ['name1' => $resource1, 'name2' => $resource2];

        $presenter = new ICalImportPresenter($page, $userRepo, $resourceRepo, $reservationRepo, $registration, $scheduleRepo);

        $presenter->Import();

        $title = 'test 11';
        $description = 'Project xyz Review Meeting Minutes\n
 Agenda\n1. Review of project version 1.0 requirements.\n2.
 Definition
  of project processes.\n3. Review of project schedule.\n
 Participants: John Smith, Jane Doe, Jim Dandy\n-It was
  decided that the requirements need to be signed off by
  product marketing.\n-Project processes were accepted.\n
 -Project schedule needs to account for scheduled holidays
  and employee vacation time. Check with HR for specific
  dates.\n-New schedule will be distributed by Friday.\n-
 Next weeks meeting is cancelled. No meeting until 3/23.';

        $date = DateRange::Create('20160112', '20160116', 'UTC');

        $reservation = ReservationSeries::Create($userRepo->_User->Id(), $resource1, $title, $description, $date, new RepeatNone(), $this->fakeUser);
        $reservation->ChangeParticipants([2, 2]);

        $firstAddedReservation = $reservationRepo->_FirstAddedReservation;
        $this->assertEquals($userRepo->_User->Id(), $firstAddedReservation->UserId());
        $this->assertEquals($resource1, $firstAddedReservation->Resource());
        $this->assertEquals($title, $firstAddedReservation->Title());
        $this->assertEquals($date, $firstAddedReservation->CurrentInstance()->Duration());
        $this->assertEquals($this->fakeUser, $firstAddedReservation->BookedBy());
    }

    public function testParseIcs()
    {
        $this->markTestSkipped('just parses ics file');
        $ical   = new ICal(ROOT_DIR . 'tests/Presenters/Admin/MyCal.ics');
        $events = $ical->events();

        //		var_dump($events[1]);
        foreach ($events as $event) {

//			$user = $this->GetOrCreateUser($event['ORGANIZER']);
            //			$resource = $this->GetOrCreateResource($event['LOCATION']);
//
            //			$reservation = ReservationSeries::Create($user->Id(), $resource, $title, $description, $date, new RepeatNone(), $bookedBy);
            //			$reservation->ChangeParticipants($participantIds);
            if (array_key_exists('ATTENDEE_array', $event)) {
                foreach ($event['ATTENDEE_array'] as $attendee) {
                    var_dump($attendee);
                }
            }
            $ts =  date('Y-m-d H:i:s', $ical->iCalDateToUnixTimestamp($event['DTSTART']));
            $parsed = Date::Parse($ts, 'UTC');
            echo $parsed . '\n';
            $start = Date::Parse($event['DTSTART'], 'UTC');
            echo $start->ToString() . '\n';
            echo 'SUMMARY: ' . @$event['SUMMARY'] . "<br />\n";
            echo 'DTSTART: ' . $event['DTSTART'] . ' - UNIX-Time: ' . $ical->iCalDateToUnixTimestamp($event['DTSTART']) . "<br />\n";
            echo 'DTEND: ' . $event['DTEND'] . "<br />\n";
            echo 'DTSTAMP: ' . $event['DTSTAMP'] . "<br />\n";
            echo 'UID: ' . @$event['UID'] . "<br />\n";
            echo 'CREATED: ' . @$event['CREATED'] . "<br />\n";
            echo 'LAST-MODIFIED: ' . @$event['LAST-MODIFIED'] . "<br />\n";
            echo 'DESCRIPTION: ' . @$event['DESCRIPTION'] . "<br />\n";
            echo 'LOCATION: ' . @$event['LOCATION'] . "<br />\n";
            echo 'SEQUENCE: ' . @$event['SEQUENCE'] . "<br />\n";
            echo 'STATUS: ' . @$event['STATUS'] . "<br />\n";
            echo 'TRANSP: ' . @$event['TRANSP'] . "<br />\n";
            echo 'ORGANIZER: ' . @$event['ORGANIZER'] . "<br />\n";
            echo 'ATTENDEE(S): ' . @$event['ATTENDEE'] . "<br />\n";
            echo '<hr/>';
        }
    }

    private function GetEvents()
    {
        return <<<EOF
BEGIN:VCALENDAR
PRODID:-//Google Inc//Google Calendar 70.9054//EN
VERSION:2.0
CALSCALE:GREGORIAN
METHOD:PUBLISH
X-WR-CALNAME:Testkalender
X-WR-TIMEZONE:Europe/Berlin
X-WR-CALDESC:Nur zum testen vom Google Kalender
BEGIN:VEVENT
ATTENDEE;CN="Page, Larry <l.page@google.com> (l.page@google.com)";
 ROLE=REQ-PARTICIPANT;RSVP=FALSE:mailto:l.page@google.com
ATTENDEE;CN="Page, Larry <l.page@google.com> (l.page@google.com)";
 ROLE=REQ-PARTICIPANT;RSVP=FALSE:mailto:l.page@google.com
DTSTART;VALUE=DATE:20160112
DTEND;VALUE=DATE:20160116
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:1koigufm110c5hnq6ln57murd4@google.com
CREATED:20110119T142901Z
DESCRIPTION;LANGUAGE=en-gb:Project xyz Review Meeting Minutes
 Agenda 1. Review of project version 1.0 requirements. 2.
 Definition of project processes.\\n3. Review of project schedule.
 Participants: John Smith, Jane Doe, Jim Dandy\\n-It was
 decided that the requirements need to be signed off by
 product marketing.\\n-Project processes were accepted.\\n
 -Project schedule needs to account for scheduled holidays
 and employee vacation time. Check with HR for specific
 dates.\\n-New schedule will be distributed by Friday.\\n-
 Next weeks meeting is cancelled. No meeting until 3/23.
LAST-MODIFIED:20150409T150000Z
LOCATION:name1
SEQUENCE:2
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 11
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160118
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:4dnsuc3nknin15kv25cn7ridss@google.com
CREATED:20110119T142059Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:name2
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 9
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160117
DTEND;VALUE=DATE:20160122
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:h6f7sdjbpt47v3dkral8lnsgcc@google.com
CREATED:20110119T142040Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160117
DTEND;VALUE=DATE:20160118
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:up56hlrtkpqdum73rk6tl10ook@google.com
CREATED:20110119T142034Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 8
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160118
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:8ltm205uhshsbc1huv0ooeg4nc@google.com
CREATED:20110119T142014Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 7
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160119
DTEND;VALUE=DATE:20160121
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:opklai3nm8enffdf5vpna4o5fo@google.com
CREATED:20110119T141918Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 5
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160119
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:kmbj764g57tcvua11hir61c4b8@google.com
CREATED:20110119T141923Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 6
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160119
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:shvr7hvqdag08vjqlmj5lj0i2s@google.com
CREATED:20110119T141913Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 4
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160119
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:77gpemlb9es0r0gtjolv3mtap0@google.com
CREATED:20110119T141909Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 3
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160119
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:rq8jng4jgq0m1lvpj8486fttu0@google.com
CREATED:20110119T141904Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
RRULE:FREQ=YEARLY;BYDAY=-1SU;BYMONTH=10
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 2
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20160119
DTEND;VALUE=DATE:20160120
DTSTAMP;TZID="GMT Standard Time":20110121T195741Z
UID:dh3fki5du0opa7cs5n5s87ca00@google.com
CREATED:20110119T141901Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20150409T150000Z
LOCATION:
RRULE:FREQ=WEEKLY;COUNT=5;INTERVAL=2;BYDAY=TU
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:test 1
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:20400201
DTEND;VALUE=DATE:20400202
DTSTAMP;TZID="GMT Standard Time":20400101T195741Z
UID:dh3fki5du0opa7cs5n5s87ca01@google.com
CREATED:20400101T141901Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20400101T141901Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:Year 2038 problem test
TRANSP:TRANSPARENT
END:VEVENT
BEGIN:VEVENT
DTSTART;VALUE=DATE:19410512
DTEND;VALUE=DATE:19410512
DTSTAMP;TZID="GMT Standard Time":19410512T195741Z
UID:dh3fki5du0opa7cs5n5s87ca02@google.com
CREATED:20400101T141901Z
DESCRIPTION;LANGUAGE=en-gb:
LAST-MODIFIED:20400101T141901Z
LOCATION:
SEQUENCE:0
STATUS:CONFIRMED
SUMMARY;LANGUAGE=en-gb:Before 1970-Test: Konrad Zuse invents the Z3, the first digital Computer
TRANSP:TRANSPARENT
END:VEVENT
END:VCALENDAR
EOF;
    }
}

class FakeICalImportPage implements IICalImportPage
{
    /**
     * @var FakeUploadedFile
     */
    public $_File;

    public function __construct()
    {
        $this->_File = new FakeUploadedFile();
    }

    /**
     * @return UploadedFile
     */
    public function GetImportFile()
    {
        return $this->_File;
    }

    public function TakingAction()
    {
        // TODO: Implement TakingAction() method.
    }

    public function GetAction()
    {
        // TODO: Implement GetAction() method.
    }

    public function RequestingData()
    {
        // TODO: Implement RequestingData() method.
    }

    public function GetDataRequest()
    {
        // TODO: Implement GetDataRequest() method.
    }

    public function PageLoad()
    {
        // TODO: Implement PageLoad() method.
    }

    public function Redirect($url)
    {
        // TODO: Implement Redirect() method.
    }

    public function RedirectToError($errorMessageId = ErrorMessages::UNKNOWN_ERROR, $lastPage = '')
    {
        // TODO: Implement RedirectToError() method.
    }

    public function IsPostBack()
    {
        // TODO: Implement IsPostBack() method.
    }

    public function IsValid()
    {
        // TODO: Implement IsValid() method.
    }

    public function GetLastPage($defaultPage = '')
    {
        // TODO: Implement GetLastPage() method.
    }

    public function RegisterValidator($validatorId, $validator)
    {
        // TODO: Implement RegisterValidator() method.
    }

    public function EnforceCSRFCheck()
    {
        // TODO: Implement EnforceCSRFCheck() method.
    }

    /**
     * @param int $numberImported
     * @param int $numberSkipped
     */
    public function SetNumberImported($numberImported, $numberSkipped)
    {
        // TODO: Implement SetNumberImported() method.
    }

    public function GetSortField()
    {
        // TODO: Implement GetSortField() method.
    }

    public function GetSortDirection()
    {
        // TODO: Implement GetSortDirection() method.
    }
}
