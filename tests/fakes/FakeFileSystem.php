<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/FileSystem/namespace.php');

class FakeFileSystem implements \Booked\IFileSystem
{
    /**
     * @var string
     */
    public $_AddedFileContents;

    /**
     * @var string
     */
    public $_AddedFileName;

    /**
     * @var string
     */
    public $_AddedFilePath;

    /**
     * @var string|string[]
     */
    public $_ExpectedContents;

    /**
     * @var string
     */
    public $_ContentsPath;

    /**
     * @var string[]
     */
    public $_RemovedFiles = [];

    /**
     * @var string[]
     */
    public $_Files = [];

    public $_Saved = true;
    public $_Removed = true;

    /**
     * @var bool|bool[]
     */
    public $_Exists = [];

    public function Save($path, $fileName, $fileContents)
    {
        $this->_AddedFilePath = $path;
        $this->_AddedFileName = $fileName;
        $this->_AddedFileContents = $fileContents;
        return $this->_Saved;
    }

    public function GetFileContents($fullPath)
    {
        $this->_ContentsPath = $fullPath;
        return is_array($this->_ExpectedContents) ? $this->_ExpectedContents[$fullPath] : $this->_ExpectedContents;
    }

    public function RemoveFile($fullPath)
    {
        $this->_RemovedFiles[] = $fullPath;
        return $this->_Removed;
    }

    public function GetReservationAttachmentsPath()
    {
        return 'reservation/attachment/path';
    }

    public function GetFiles($fullPath)
    {
        return $this->_Files;
    }

    public function Exists($fullPath)
    {
        return is_array($this->_Exists) ? $this->_Exists[$fullPath] : $this->_Exists;
    }

    public function FlushSmartyCache()
    {
        // TODO: Implement FlushSmartyCache() method.
    }
}
