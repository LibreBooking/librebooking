<?php

require_once(ROOT_DIR . 'lib/WebService/namespace.php');
require_once(ROOT_DIR . 'Pages/Pages.php');
require_once(ROOT_DIR . 'lib/Server/QueryStringKeys.php');

class AttachmentResponse
{
    public $url;
    public $fileName;

    public function __construct(IRestServer $server, $fileId, $fileName, $referenceNumber)
    {
        $this->fileName = $fileName;

        $page = Pages::RESERVATION_FILE;
        $qsAttachment = QueryStringKeys::ATTACHMENT_FILE_ID;
        $qsRefNum = QueryStringKeys::REFERENCE_NUMBER;

        $this->url = $server->GetUrl(). "/attachments/$page?$qsAttachment=$fileId&$qsRefNum=$referenceNumber";
    }

    public static function Example()
    {
        return new ExampleAttachmentResponse();
    }
}

class ExampleAttachmentResponse extends AttachmentResponse
{
    public function __construct()
    {
        $this->url = 'http://example/attachments/url';
    }
}
