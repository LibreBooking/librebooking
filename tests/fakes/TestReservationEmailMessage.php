<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/ReservationEmailMessage.php');

class TestReservationEmailMessage extends ReservationEmailMessage
{
    public function Subject()
    {
        return 'Test Subject';
    }

    protected function GetTemplateName()
    {
        return 'ReservationCreated.tpl';
    }

    /**
     * @param Reservation $currentInstance
     * @param Attribute[] $attributeValues
     */
    public function PopulateIcsAttachmentForTest($currentInstance, $attributeValues = [])
    {
        $this->PopulateIcsAttachment($currentInstance, $attributeValues);
    }
}
