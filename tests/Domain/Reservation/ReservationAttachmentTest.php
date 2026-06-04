<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/ReservationAttachment.php');
require_once(ROOT_DIR . 'Domain/ReservationAttachmentView.php');

class ReservationAttachmentTest extends TestBase
{
    public function testCreateNormalizesStoredFileName()
    {
        $attachment = ReservationAttachment::Create(
            '<img src=x onerror=alert(\'XSS\')>.pdf',
            'application/pdf',
            100,
            'contents',
            'pdf',
            1
        );

        $this->assertEquals('_img src=x onerror=alert(\'XSS\')_.pdf', $attachment->FileName());
    }

    public function testAttachmentViewNormalizesFileNameFromStorage()
    {
        $attachment = new ReservationAttachmentView(
            1,
            2,
            "../bad\r\nname?.pdf"
        );

        $this->assertEquals('.._bad_name_.pdf', $attachment->FileName());
    }

    public function testNormalizeFileNameFallsBackForEmptyNames()
    {
        $this->assertEquals('attachment', ReservationAttachment::NormalizeFileName("\r\n<>"));
    }

    public function testNormalizeFileNamePreservesHarmlessRepeatedUnderscores()
    {
        $this->assertEquals('my__file.pdf', ReservationAttachment::NormalizeFileName('my__file.pdf'));
    }
}
