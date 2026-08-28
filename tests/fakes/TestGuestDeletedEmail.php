<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Email/Messages/GuestDeletedEmail.php');

class TestGuestDeletedEmail extends GuestDeletedEmail
{
    /**
     * @param Reservation $currentInstance
     * @param Attribute[] $attributeValues
     */
    public function PopulateIcsAttachmentForTest($currentInstance, $attributeValues = [])
    {
        $this->PopulateIcsAttachment($currentInstance, $attributeValues);
    }
}
