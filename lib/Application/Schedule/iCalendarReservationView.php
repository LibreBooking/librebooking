<?php

class iCalendarReservationView
{
    public $Classification;
    public $DateCreated;
    public $DateEnd;
    public $DateStart;
    public $Description;
    public $Organizer;
    public $OrganizerEmail;
    public $RecurRule;
    public $ReferenceNumber;
    public $Summary;
    public $ReservationUrl;
    public $Location;
    public $StartReminder;
    public $EndReminder;
    public $LastModified;
    public $IsPending;
    public $ExtraIcalLines;

    /**
     * Готовые строки ATTENDEE для вставки в VEVENT (каждая строка оканчивается \n)
     * @var string|null
     */
    public $AttendeeLines;

    /**
     * @var ExportFactory
     */
    private $ExportFactory;

    /**
     * @var ReservationItemView
     */
    public $ReservationItemView;

    /**
     * @param ReservationItemView $res
     * @param UserSession $currentUser
     * @param IPrivacyFilter $privacyFilter
     * @param string|null $summaryFormat
     * @param mixed|null $userRepository (ожидается объект с методом GetById($id)->EmailAddress()/FullName())
     */
    public function __construct($res, UserSession $currentUser, IPrivacyFilter $privacyFilter, $summaryFormat = null, $userRepository = null)
    {
        if ($summaryFormat == null) {
            $summaryFormat = Configuration::Instance()->GetKey(ConfigKeys::RESERVATION_LABELS_ICS_SUMMARY);
        }
        $factory = new SlotLabelFactory($currentUser);
        $this->ReservationItemView = $res;
        $canViewUser = $privacyFilter->CanViewUser($currentUser, $res, $res->OwnerId);
        $canViewDetails = $privacyFilter->CanViewDetails($currentUser, $res, $res->OwnerId);

        $this->ExportFactory = PluginManager::Instance()->LoadExport();

        $privateNotice = 'Private';

        $this->Classification = method_exists($this->ExportFactory, 'GetIcalendarClassification') ? $this->ExportFactory->GetIcalendarClassification($res) : 'PUBLIC';
        if ($res->DateCreated) {
            $this->DateCreated = $res->DateCreated;
        } else {
            $this->DateCreated = Date::Now();
        }

        $this->DateEnd = $res->EndDate;
        $this->DateStart = $res->StartDate;
        $this->Description = $canViewDetails ? $factory->Format($res, $summaryFormat) : $privateNotice;
        $fullName = new FullName($res->OwnerFirstName, $res->OwnerLastName);
        $this->Organizer = $canViewUser ? $fullName->__toString() : $privateNotice;
        $this->OrganizerEmail = $canViewUser ? $res->OwnerEmailAddress : $privateNotice;
        $this->RecurRule = $this->CreateRecurRule($res);
        $this->ReferenceNumber = $res->ReferenceNumber;
        $this->Summary = $canViewDetails ? $res->Title : $privateNotice;
        $this->ReservationUrl = sprintf(
            "%s/%s?%s=%s",
            Configuration::Instance()->GetScriptUrl(),
            Pages::RESERVATION,
            QueryStringKeys::REFERENCE_NUMBER,
            $res->ReferenceNumber
        );
        $this->Location = $res->ResourceName;

        $this->StartReminder = $res->StartReminder;
        $this->EndReminder = $res->EndReminder;
        $this->LastModified = empty($res->ModifiedDate) || $res->ModifiedDate->ToString() == '' ? $this->DateCreated : $res->ModifiedDate;
        $this->IsPending = $res->RequiresApproval;

        if ($res->OwnerId == $currentUser->UserId) {
            $this->OrganizerEmail = str_replace('@', '-noreply@', $res->OwnerEmailAddress);
        }

        $this->ExtraIcalLines = method_exists($this->ExportFactory, 'GetIcalendarExtraLines') ? $this->ExportFactory->GetIcalendarExtraLines($res) : null;

        // NEW: сформировать ATTENDEE-линии (participants/invitees/guests)
        $this->AttendeeLines = $this->BuildAttendeeLines($res, $canViewUser, $userRepository);
    }

    private function BuildAttendeeLines($res, $canViewUser, $userRepository)
    {
        if (!$canViewUser) {
            return null;
        }

        // Если репозиторий не передали — попробуем подхватить дефолтный (если существует)
        if ($userRepository == null && class_exists('UserRepository')) {
            $userRepository = new UserRepository();
        }
        if ($userRepository == null || !method_exists($userRepository, 'GetById')) {
            // Без репозитория не достанем email пользователей => ATTENDEE корректно не собрать
            return null;
        }

        $lines = [];
        $seen = [];

        // Организатора тоже добавим как attendee (как часто делает Exchange)
        if (!empty($this->OrganizerEmail) && $this->OrganizerEmail !== 'Private') {
            $this->AddAttendeeLine($lines, $seen, 'REQ-PARTICIPANT', $this->Organizer, $this->OrganizerEmail);
        }

        // Обязательные участники
        if (!empty($res->ParticipantIds) && is_array($res->ParticipantIds)) {
            foreach ($res->ParticipantIds as $id) {
                $user = $userRepository->GetById($id);
                if ($user == null) {
                    continue;
                }
                $email = method_exists($user, 'EmailAddress') ? $user->EmailAddress() : null;
                $name = method_exists($user, 'FullName') ? $user->FullName() : null;
                $this->AddAttendeeLine($lines, $seen, 'REQ-PARTICIPANT', $name, $email);
            }
        }

        // Необязательные (invitees)
        if (!empty($res->InviteeIds) && is_array($res->InviteeIds)) {
            foreach ($res->InviteeIds as $id) {
                $user = $userRepository->GetById($id);
                if ($user == null) {
                    continue;
                }
                $email = method_exists($user, 'EmailAddress') ? $user->EmailAddress() : null;
                $name = method_exists($user, 'FullName') ? $user->FullName() : null;
                $this->AddAttendeeLine($lines, $seen, 'OPT-PARTICIPANT', $name, $email);
            }
        }

        // Гости-участники (строки обычно "Name <mail>" или просто mail)
        if (!empty($res->ParticipatingGuests) && is_array($res->ParticipatingGuests)) {
            foreach ($res->ParticipatingGuests as $guest) {
                [$name, $email] = $this->ParseGuest($guest);
                $this->AddAttendeeLine($lines, $seen, 'REQ-PARTICIPANT', $name, $email);
            }
        }

        // Гости-приглашённые
        if (!empty($res->InvitedGuests) && is_array($res->InvitedGuests)) {
            foreach ($res->InvitedGuests as $guest) {
                [$name, $email] = $this->ParseGuest($guest);
                $this->AddAttendeeLine($lines, $seen, 'OPT-PARTICIPANT', $name, $email);
            }
        }

        if (empty($lines)) {
            return null;
        }

        return implode("\n", $lines) . "\n";
    }

    private function AddAttendeeLine(&$lines, &$seen, $role, $cn, $email)
    {
        $email = is_string($email) ? trim($email) : '';
        if ($email === '' || strpos($email, '@') === false) {
            // Без email нельзя сделать нормальный CAL-ADDRESS (MAILTO:)
            return;
        }

        $key = mb_strtolower($email);
        if (isset($seen[$key])) {
            return;
        }
        $seen[$key] = true;

        $cn = is_string($cn) && trim($cn) !== '' ? trim($cn) : $email;

        $cnEscaped = $this->EscapeIcalParamValue($cn);
        $emailEscaped = $this->EscapeIcalParamValue($email);

        $lines[] = sprintf(
            'ATTENDEE;ROLE=%s;PARTSTAT=NEEDS-ACTION;RSVP=TRUE;CN=%s:MAILTO:%s',
            $role,
            $cnEscaped,
            $emailEscaped
        );
    }

    private function ParseGuest($raw)
    {
        $raw = is_string($raw) ? trim($raw) : '';
        if ($raw === '') {
            return [null, null];
        }

        // "Name <email@domain>"
        if (preg_match('/^(.*?)<([^>]+)>$/u', $raw, $m)) {
            $name = trim($m[1]);
            $email = trim($m[2]);
            return [$name !== '' ? $name : $email, $email];
        }

        // просто email
        if (strpos($raw, '@') !== false) {
            return [$raw, $raw];
        }

        // имя без email — attendee не добавим (email=null)
        return [$raw, null];
    }

    private function EscapeIcalParamValue($value)
    {
        $value = (string)$value;
        $value = str_replace('\\', '\\\\', $value);
        $value = str_replace(';', '\;', $value);
        $value = str_replace(',', '\,', $value);
        $value = str_replace("\r\n", ' ', $value);
        $value = str_replace("\n", ' ', $value);
        $value = str_replace("\r", ' ', $value);
        return $value;
    }

    /**
     * @param ReservationItemView $res
     * @return null|string
     */
    private function CreateRecurRule($res)
    {
        if (is_a($res, 'ReservationItemView')) {
            // don't populate the recurrence rule when a list of reservation is being exported
            return null;
        }
        ### !!!  THIS DOES NOT WORK BECAUSE EXCEPTIONS TO RECURRENCE RULES ARE NOT PROPERLY HANDLED !!!
        ### see bug report http://php.brickhost.com/forums/index.php?topic=11450.0

        if (empty($res->RepeatType) || $res->RepeatType == RepeatType::None) {
            return null;
        }

        $freqMapping = [RepeatType::Daily => 'DAILY', RepeatType::Weekly => 'WEEKLY', RepeatType::Monthly => 'MONTHLY', RepeatType::Yearly => 'YEARLY'];
        $freq = $freqMapping[$res->RepeatType];
        $interval = $res->RepeatInterval;
        $format = Resources::GetInstance()->GetDateFormat('ical');
        $end = $res->RepeatTerminationDate->SetTime($res->EndDate->GetTime())->Format($format);
        $rrule = sprintf('FREQ=%s;INTERVAL=%s;UNTIL=%s', $freq, $interval, $end);

        if ($res->RepeatType == RepeatType::Monthly) {
            if ($res->RepeatMonthlyType == RepeatMonthlyType::DayOfMonth) {
                $rrule .= ';BYMONTHDAY=' . $res->StartDate->Day();
            }
        }

        if (!empty($res->RepeatWeekdays)) {
            $dayMapping = ['SU', 'MO', 'TU', 'WE', 'TH', 'FR', 'SA'];
            $days = '';
            foreach ($res->RepeatWeekdays as $weekDay) {
                $days .= ($dayMapping[$weekDay] . ',');
            }
            $days = substr($days, 0, -1);
            $rrule .= (';BYDAY=' . $days);
        }

        return $rrule;
    }
}