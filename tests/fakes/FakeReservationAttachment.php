<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/ReservationAttachment.php');

class FakeReservationAttachment extends ReservationAttachment
{
    public function __construct($fileId = 1)
    {
        $this->fileId = $fileId;
    }

    public function SetSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;
    }

    public function SetExtension($extension)
    {
        $this->fileExtension = $extension;
    }
}
