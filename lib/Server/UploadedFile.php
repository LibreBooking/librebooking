<?php

class UploadedFile
{
    private $file;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * @return string
     */
    public function OriginalName()
    {
        return $this->file['name'];
    }

    /**
     * @return string
     */
    public function TemporaryName()
    {
        return $this->file['tmp_name'];
    }

    /**
     * @return string
     */
    public function MimeType()
    {
        return $this->file['type'];
    }

    /**
     * @return int total bytes
     */
    public function Size()
    {
        return $this->file['size'];
    }

    /**
     * @return string
     */
    public function Extension()
    {
        $info = pathinfo($this->OriginalName());
        return $info['extension'];
    }

    /**
     * @return string
     */
    public function Contents()
    {
        $tmpName = $this->TemporaryName();
        $fp = fopen($tmpName, 'r');
        $content = fread($fp, filesize($tmpName));
        fclose($fp);

        return trim($content);
    }

    public function IsError()
    {
        return $this->file['error'] != UPLOAD_ERR_OK;
    }

    /**
     * An empty file input is reported as UPLOAD_ERR_NO_FILE, which is not a failure
     * @return bool
     */
    public function IsRejected()
    {
        return $this->IsError() && $this->file['error'] != UPLOAD_ERR_NO_FILE;
    }

    /**
     * @static
     * @param UploadedFile[]|null $files
     * @return string[] a message for each rejected upload, which is also logged
     */
    public static function GetRejectedMessages($files)
    {
        $messages = [];
        foreach ($files ?? [] as $file) {
            if ($file != null && $file->IsRejected()) {
                Log::Error('Error attaching file %s. %s', $file->OriginalName(), $file->Error());
                $messages[] = Resources::GetInstance()->GetString('AttachmentUploadFailed', [htmlspecialchars($file->OriginalName()), self::GetMaxSize()]);
            }
        }

        return $messages;
    }

    public function Error()
    {
        $messages = [
            UPLOAD_ERR_OK => '',
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the maximum file size',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the maximum file size',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary storage folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk, check folder permissions of configured upload directory',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload'
        ];

        return $messages[$this->file['error']] ?? 'Unknown upload error';
    }

    /**
     * @static
     * @return int
     */
    public static function GetMaxSize()
    {
        $max_upload = (int)(ini_get('upload_max_filesize'));
        $max_post = (int)(ini_get('post_max_size'));
        $memory_limit = (int)(ini_get('memory_limit'));
        return min($max_upload, $max_post, $memory_limit);
    }

    /**
     * @static
     * @return int
     */
    public static function GetMaxUploadCount()
    {
        return (int)(ini_get('max_file_uploads'));
    }
}
