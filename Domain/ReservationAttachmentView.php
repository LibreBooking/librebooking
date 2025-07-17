<?php

class ReservationAttachmentView
{
    private int $fileId;
    private int $seriesId;
    private string $fileName;

    /**
     * @param int $fileId
     * @param int $seriesId
     * @param string $fileName
     */
    public function __construct($fileId, $seriesId, $fileName)
    {
        $this->fileId = $fileId;
        $this->seriesId = $seriesId;
        $this->fileName = $fileName;
    }

    /**
     * @return int
     */
    public function FileId()
    {
        return $this->fileId;
    }

    /**
     * @return string
     */
    public function FileName()
    {
        return $this->fileName;
    }

    /**
     * @return int
     */
    public function SeriesId()
    {
        return $this->seriesId;
    }
}
