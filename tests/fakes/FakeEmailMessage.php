<?php

declare(strict_types=1);

class FakeEmailMessage implements IEmailMessage
{
    public $_Charset = 'UTF-8';
    public $_To = [];
    public $_From;
    public $_CC = [];
    public $_BCC = [];
    public $_Subject = '';
    public $_Body = '';
    public $_ReplyTo;
    public $_AttachmentContents;
    public $_AttachmentFileName;
    public ?string $_AttachmentMimeType = null;

    public function __construct()
    {
        $this->_From = new EmailAddress('from@example.com', 'From');
        $this->_ReplyTo = new EmailAddress('replyto@example.com', 'ReplyTo');
    }

    public function Charset()
    {
        return $this->_Charset;
    }

    public function To()
    {
        return $this->_To;
    }

    public function From()
    {
        return $this->_From;
    }

    public function CC()
    {
        return $this->_CC;
    }

    public function BCC()
    {
        return $this->_BCC;
    }

    public function Subject()
    {
        return $this->_Subject;
    }

    public function Body()
    {
        return $this->_Body;
    }

    public function ReplyTo()
    {
        return $this->_ReplyTo;
    }

    public function AddStringAttachment($contents, $fileName, ?string $mimeType = null)
    {
        $this->_AttachmentContents = $contents;
        $this->_AttachmentFileName = $fileName;
        $this->_AttachmentMimeType = $mimeType;
    }

    public function HasStringAttachment()
    {
        return !empty($this->_AttachmentContents);
    }

    public function AttachmentContents()
    {
        return $this->_AttachmentContents;
    }

    public function AttachmentFileName()
    {
        return $this->_AttachmentFileName;
    }

    public function AttachmentMimeType(): ?string
    {
        return $this->_AttachmentMimeType;
    }
}
