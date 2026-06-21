<?php

use Sabre\VObject\Component\VAlarm;
use Sabre\VObject\Component\VCalendar;
use Sabre\VObject\Component\VEvent;

require_once(ROOT_DIR . 'Pages/Page.php');

class CalendarExportDisplay extends Page
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @param $reservations iCalendarReservationView[]
     * @param string|null $calendarName Optional display name rendered as X-WR-CALNAME
     * @return string
     */
    public function Render(array $reservations, ?string $calendarName = null): string
    {
        $vcal = new VCalendar();
        $vcal->PRODID = '-//LibreBooking//NONSGML ' . Configuration::VERSION . '//EN';

        if ($calendarName !== null && $calendarName !== '') {
            $vcal->add('NAME', $calendarName);
            $vcal->add('X-WR-CALNAME', $calendarName);
        }

        // ScriptUrl is used to generate iCal UIDs. Avoid slashes per
        // https://bugzilla.mozilla.org/show_bug.cgi?id=465853
        $uid = parse_url(Configuration::Instance()->GetScriptUrl(), PHP_URL_HOST) ?? '';
        $isoFormat = 'Ymd\THis\Z';

        foreach ($reservations as $res) {
            /** @var iCalendarReservationView $res */
            $event = new VEvent($vcal, 'VEVENT');
            $vcal->add($event);
            // VEvent auto-generates UID and DTSTAMP in getDefaults(); use = to replace them.
            $event->UID = $res->ReferenceNumber . '&' . $uid;
            $event->DTSTAMP = $res->DateCreated->Format($isoFormat);
            $event->add('CLASS', $res->Classification);
            $event->add('CREATED', $res->DateCreated->Format($isoFormat));
            $event->add('DESCRIPTION', $res->Description);
            $event->add('DTSTART', $res->DateStart->Format($isoFormat));
            $event->add('DTEND', $res->DateEnd->Format($isoFormat));
            $event->add('LAST-MODIFIED', $res->LastModified->Format($isoFormat));
            $event->add('LOCATION', $res->Location);
            $event->add('ORGANIZER', 'mailto:' . $res->OrganizerEmail, ['CN' => $res->Organizer]);
            $event->add('STATUS', $res->IsPending ? 'TENTATIVE' : 'CONFIRMED');
            $event->add('SUMMARY', $res->Summary);
            $event->add('SEQUENCE', 0);
            $event->add('URL', $res->ReservationUrl);
            $event->add('X-MICROSOFT-CDO-BUSYSTATUS', 'BUSY');

            if ($res->RecurRule) {
                $event->add('RRULE', $res->RecurRule);
            }

            if (!empty($res->ExtraIcalLines)) {
                foreach (preg_split('/\r?\n/', trim((string)$res->ExtraIcalLines)) as $line) {
                    $line = rtrim($line, "\r");
                    if ($line !== '' && str_contains($line, ':')) {
                        [$prop, $val] = explode(':', $line, 2);
                        $event->add(trim($prop), $val);
                    }
                }
            }

            if ($res->StartReminder !== null) {
                $alarm = new VAlarm($vcal, 'VALARM');
                $event->add($alarm);
                $alarm->add('TRIGGER', '-PT' . $res->StartReminder->MinutesPrior() . 'M', ['RELATED' => 'START']);
                $alarm->add('ACTION', 'DISPLAY');
                $alarm->add('DESCRIPTION', $res->Description);
            }

            if ($res->EndReminder !== null) {
                $alarm = new VAlarm($vcal, 'VALARM');
                $event->add($alarm);
                $alarm->add('TRIGGER', '-PT' . $res->EndReminder->MinutesPrior() . 'M', ['RELATED' => 'END']);
                $alarm->add('ACTION', 'DISPLAY');
                $alarm->add('DESCRIPTION', $res->Summary);
            }
        }

        return $vcal->serialize();
    }

    public function PageLoad()
    {
        // no-op
    }
}
