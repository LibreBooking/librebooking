<?php

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class ReservationDeletedEmail extends ReservationEmailMessage
{
    /**
     * @return string
     */
    public function Subject()
    {
        return $this->Translate('ReservationDeletedSubjectWithResource', [$this->primaryResource->GetName()]);
    }

    public function PopulateTemplate()
    {
        parent::PopulateTemplate();
        if (method_exists($this->reservationSeries, 'GetDeleteReason')) {
            $this->Set('DeleteReason', $this->reservationSeries->GetDeleteReason());
        }
        $this->Set("Deleted", true);
    }

    protected function GetTemplateName()
    {
        return 'ReservationDeleted.tpl';
    }

    protected function PopulateIcsAttachment($currentInstance, $attributeValues)
    {
        $title = $this->reservationSeries->Title();
        if (empty($title)) {
            $title = 'Бронь ' . $this->primaryResource->GetName(); 
        }
        $description = $this->reservationSeries->Description();
        $cancelText = "\r\n\r\nЭта бронь отменена.";
        $reason = '';
        if (method_exists($this->reservationSeries, 'GetDeleteReason') && $reason = $this->reservationSeries->GetDeleteReason()) {
            $cancelText .= "\r\nПричина: " . $reason;
        }
        $description .= $cancelText;

        $rv = new ReservationItemView(
            $currentInstance->ReferenceNumber(),
            $currentInstance->StartDate()->ToUTC(),
            $currentInstance->EndDate()->ToUTC(),
            $this->reservationSeries->Resource()->GetName(),
            $this->reservationSeries->Resource()->GetResourceId(),
            $currentInstance->ReservationId(),
            null,
            'Отменена: ' . $title,  
            $description,
            $this->reservationSeries->ScheduleId(),
            $this->reservationOwner->FirstName(),
            $this->reservationOwner->LastName(),
            $this->reservationOwner->Id(),
            $this->reservationOwner->GetAttribute(UserAttribute::Phone),
            $this->reservationOwner->GetAttribute(UserAttribute::Organization),
            $this->reservationOwner->GetAttribute(UserAttribute::Position)
        );

        $ca = new CustomAttributes();
        foreach ($attributeValues as $attribute) {
            $ca->Add($attribute->Id(), $attribute->Value());
        }
        $rv->Attributes = $ca;
        $rv->UserPreferences = $this->reservationOwner->GetPreferences();
        $rv->OwnerEmailAddress = $this->reservationOwner->EmailAddress();

        $rv->InvitedGuests = $currentInstance->InvitedGuests();
        $rv->ParticipatingGuests = $currentInstance->ParticipatingGuests();
        $rv->ParticipantIds = $currentInstance->Participants();
        $rv->InviteeIds = $currentInstance->Invitees();

        $icsView = new iCalendarReservationView(
            $rv,
            $this->reservationSeries->BookedBy(),
            new NullPrivacyFilter(),
            null,
            $this->userRepository
        );

        $display = new CalendarExportDisplay();
        $icsContents = $display->Render([$icsView]);

        $icsContents = preg_replace('/METHOD:(REQUEST|PUBLISH)/i', 'METHOD:CANCEL', $icsContents);

        if (stripos($icsContents, 'STATUS:') === false) {
            $icsContents = preg_replace('/(BEGIN:VEVENT\r?\n)/i', '$1STATUS:CANCELLED\r\n', $icsContents, 1);
        } else {
            $icsContents = preg_replace('/STATUS:(CONFIRMED|TENTATIVE)/i', 'STATUS:CANCELLED', $icsContents);
        }

        $icsContents = preg_replace_callback('/SEQUENCE:(\d+)/i', function($m) {
            return 'SEQUENCE:' . ((int)$m[1] + 1);
        }, $icsContents);

        $icsContents = preg_replace('/PARTSTAT=NEEDS-ACTION/i', 'PARTSTAT=DECLINED', $icsContents);
        $icsContents = preg_replace('/;RSVP=TRUE/i', '', $icsContents);

        $icsContents = preg_replace('/X-MICROSOFT-CDO-BUSYSTATUS:BUSY/i', 'X-MICROSOFT-CDO-BUSYSTATUS:FREE', $icsContents);

        $this->AddStringAttachment($icsContents, 'cancel_reservation.ics');  
    }

}
