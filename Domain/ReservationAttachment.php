<?php

class ReservationAttachment
{
    /**
     * @var int
     */
    protected $fileId;

    /**
     * @var string
     */
    protected $fileName;

    /**
     * @var string
     */
    protected $fileType;

    /**
     * @var int
     */
    protected $fileSize;

    /**
     * @var string
     */
    protected $fileContent;

    /**
     * @var string
     */
    protected $fileExtension;

    /**
     * @var int
     */
    protected $seriesId;

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
     * @return string
     */
    public function FileContents()
    {
        return $this->fileContent;
    }

    /**
     * @return string
     */
    public function FileExtension()
    {
        return $this->fileExtension;
    }

    /**
     * @return int
     */
    public function FileSize()
    {
        return $this->fileSize;
    }

    /**
     * @return string
     */
    public function FileType()
    {
        return $this->fileType;
    }

    /**
     * @return int
     */
    public function SeriesId()
    {
        return $this->seriesId;
    }

    protected function __construct()
    {
    }

    /**
     * @static
     * @param string $fileName
     * @param string $fileType
     * @param int $fileSize
     * @param mixed $fileContent
     * @param string $fileExtension
     * @param int $seriesId
     * @return ReservationAttachment
     */
    public static function Create($fileName, $fileType, $fileSize, $fileContent, $fileExtension, $seriesId)
    {
        $file = new ReservationAttachment();
        $file->fileName = self::NormalizeFileName($fileName);
        $file->fileType = $fileType;
        $file->fileSize = $fileSize;
        $file->fileContent = $fileContent;
        $file->fileExtension = $fileExtension;
        $file->seriesId = $seriesId;

        return $file;
    }

    public static function NormalizeFileName(string $fileName): string
    {
        $normalized = trim((string)$fileName);
        $normalized = str_replace(['/', '\\'], '_', $normalized);
        $normalized = preg_replace('/[[:cntrl:]<>:"|?*]+/', '_', $normalized) ?? '';

        if ($normalized === '' || trim($normalized, '._ ') === '') {
            return 'attachment';
        }

        return $normalized;
    }

    /**
     * @param $fileId int
     */
    public function WithFileId($fileId)
    {
        $this->fileId = $fileId;
    }

    /**
     * @param $seriesId int
     */
    public function WithSeriesId($seriesId)
    {
        $this->seriesId = $seriesId;
    }
}
