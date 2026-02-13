<?php

require_once(ROOT_DIR . 'lib/Server/UploadedFile.php');

class FakeUploadedFile extends UploadedFile
{
    public function __construct()
    {
    }

    /**
     * @var string
     */
    public $OriginalName = 'orig';

    /**
     * @var string
     */
    public $TemporaryName = 'temp';

    /**
     * @var string
     */
    public $MimeType = 'mime';

    /**
     * @var int
     */
    public $Size = 100;

    /**
     * @var string
     */
    public $Extension = 'ext';

    /**
     * @var string
     */
    public $Contents = 'contents';

    /**
     * @var bool
     */
    public $IsError = false;

    /**
     * @var string
     */
    public $Error;

    public function OriginalName()
    {
        return $this->OriginalName;
    }

    public function TemporaryName()
    {
        return $this->TemporaryName;
    }

    public function MimeType()
    {
        return $this->MimeType;
    }

    public function Size()
    {
        return $this->Size;
    }

    public function Extension()
    {
        return $this->Extension;
    }

    public function Contents()
    {
        return $this->Contents;
    }

    public function IsError()
    {
        return $this->IsError;
    }

    public function Error()
    {
        $this->Error;
    }
}
