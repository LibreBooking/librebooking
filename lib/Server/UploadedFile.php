<?php

class UploadedFile
{
    private const BYTES_PER_MB = 1048576;

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
     * @return float|int the smallest effective upload limit in MB
     */
    public static function GetMaxSize()
    {
        return self::SmallestLimitInMegabytes([
            (string)ini_get('upload_max_filesize'),
            (string)ini_get('post_max_size'),
            (string)ini_get('memory_limit'),
        ]);
    }

    /**
     * @static
     * @param string[] $limits php.ini sizes such as 512K, 2M or -1
     * @return float|int the smallest limit in MB, ignoring unlimited ones
     */
    public static function SmallestLimitInMegabytes(array $limits)
    {
        $bytes = [];
        foreach ($limits as $limit) {
            $parsed = ini_parse_quantity($limit);
            // 0 and -1 mean unlimited
            if ($parsed > 0) {
                $bytes[] = $parsed;
            }
        }

        return empty($bytes) ? 0 : round(min($bytes) / self::BYTES_PER_MB, 1);
    }

    /**
     * PHP discards the whole request body, form fields included, when it is
     * larger than post_max_size
     * @static
     * @return bool
     */
    public static function ExceedsPostMaxSize()
    {
        $limit = ini_parse_quantity((string)ini_get('post_max_size'));

        return ($_SERVER['REQUEST_METHOD'] ?? '') === 'POST'
            && empty($_POST)
            && empty($_FILES)
            && $limit > 0
            && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > $limit;
    }

    /**
     * PHP discards the request body of an upload larger than post_max_size, which
     * then fails the CSRF check, so log the actual cause
     * @static
     */
    public static function CheckOversizedPost()
    {
        if (self::ExceedsPostMaxSize()) {
            Log::Error('Request body of %s bytes exceeds post_max_size of %s', $_SERVER['CONTENT_LENGTH'], ini_get('post_max_size'));
        }
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
