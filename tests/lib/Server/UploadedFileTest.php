<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Server/UploadedFile.php');

class UploadedFileTest extends TestBase
{
    public function testEmptyFileInputIsNotRejected(): void
    {
        $file = new UploadedFile(['error' => UPLOAD_ERR_NO_FILE]);

        $this->assertTrue($file->IsError());
        $this->assertFalse($file->IsRejected());
    }

    public function testFileOverTheIniLimitIsRejected(): void
    {
        $file = new UploadedFile(['error' => UPLOAD_ERR_INI_SIZE]);

        $this->assertTrue($file->IsRejected());
    }

    public function testFileWithoutErrorIsNotRejected(): void
    {
        $file = new UploadedFile(['error' => UPLOAD_ERR_OK]);

        $this->assertFalse($file->IsRejected());
    }

    public function testRejectedMessagesNameOnlyRejectedFilesAndEscapeTheName(): void
    {
        $rejected = new UploadedFile(['name' => '<b>big</b>.pdf', 'error' => UPLOAD_ERR_INI_SIZE]);
        $empty = new UploadedFile(['name' => '', 'error' => UPLOAD_ERR_NO_FILE]);
        $ok = new UploadedFile(['name' => 'fine.pdf', 'error' => UPLOAD_ERR_OK]);

        $messages = UploadedFile::GetRejectedMessages([$rejected, $empty, $ok, null]);

        $this->assertCount(1, $messages);
        $this->assertStringContainsString('&lt;b&gt;big&lt;/b&gt;.pdf', $messages[0]);
    }

    public function testNoRejectedMessagesWithoutFiles(): void
    {
        $this->assertSame([], UploadedFile::GetRejectedMessages(null));
        $this->assertSame([], UploadedFile::GetRejectedMessages([]));
    }

    public function testErrorDescribesExtensionAndUnknownErrorCodes(): void
    {
        $this->assertNotEmpty((new UploadedFile(['error' => UPLOAD_ERR_EXTENSION]))->Error());
        $this->assertSame('Unknown upload error', (new UploadedFile(['error' => 99]))->Error());
    }
}
