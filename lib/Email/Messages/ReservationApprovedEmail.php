<?php

require_once(ROOT_DIR . 'lib/Email/namespace.php');
require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class ReservationApprovedEmail extends ReservationEmailMessage
{
    public function Subject()
    {
        return $this->Translate('ReservationApprovedSubjectWithResource', [$this->primaryResource->GetName()]);
    }

    protected function PopulateTemplate()
    {
        parent::PopulateTemplate();
        $this->Set('ApprovedBy', self::EscapeHtml(new FullName($this->reservationSeries->BookedBy()->FirstName, $this->reservationSeries->BookedBy()->LastName)));
    }

    /**
     * @return string
     */
    protected function GetTemplateName()
    {
        return 'ReservationCreated.tpl';
    }
}
