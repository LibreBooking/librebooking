<?php

interface IEmailMessage
{
    /**
     * @return string
     */
    public function Charset();

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function To();

    /**
     * @return EmailAddress
     */
    public function From();

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function CC();

    /**
     * @return array|EmailAddress[]|EmailAddress
     */
    public function BCC();

    /**
     * @return string
     */
    public function Subject();

    /**
     * @return string
     */
    public function Body();

    /**
     * @return EmailAddress
     */
    public function ReplyTo();

    /**
     * @abstract
     * @param string $contents
     * @param string $fileName
     * @param string|null $mimeType Full Content-Type value (e.g. 'text/calendar; method=REQUEST'). Null auto-detects from $fileName.
     */
    public function AddStringAttachment($contents, $fileName, ?string $mimeType = null);

    /**
     * @abstract
     * @return bool
     */
    public function HasStringAttachment();

    /**
     * @abstract
     * @return string|null
     */
    public function AttachmentContents();

    /**
     * @abstract
     * @return string|null
     */
    public function AttachmentFileName();

    /**
     * @abstract
     */
    public function AttachmentMimeType(): ?string;
}
