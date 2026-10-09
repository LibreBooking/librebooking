<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Server/UploadedFile.php');

class UploadedFileTest extends TestBase
{
    public function tearDown(): void
    {
        unset($_SERVER['REQUEST_METHOD'], $_SERVER['CONTENT_LENGTH']);
        $_POST = [];
        $_FILES = [];
        parent::tearDown();
    }

    public function testPostBodyOverLimitIsDetectedWhenPhpDiscardedIt(): void
    {
        $limit = ini_parse_quantity((string)ini_get('post_max_size'));
        if ($limit <= 0) {
            $this->markTestSkipped('post_max_size is unlimited');
        }

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['CONTENT_LENGTH'] = (string)($limit + 1);
        $_POST = [];
        $_FILES = [];

        $this->assertTrue(UploadedFile::ExceedsPostMaxSize());
    }

    public function testNormalPostIsNotFlagged(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['CONTENT_LENGTH'] = '100';
        $_POST = ['a' => 'b'];

        $this->assertFalse(UploadedFile::ExceedsPostMaxSize());
    }

    public function testBodyWithinPostMaxSizeIsNotFlaggedEvenIfPhpDidNotParseIt(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['CONTENT_LENGTH'] = '100';
        $_POST = [];
        $_FILES = [];

        $this->assertFalse(UploadedFile::ExceedsPostMaxSize());
    }

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

    public function testShorthandLimitsAreConvertedToMegabytes(): void
    {
        $this->assertEquals(0.5, UploadedFile::SmallestLimitInMegabytes(['512K']));
        $this->assertEquals(1024, UploadedFile::SmallestLimitInMegabytes(['1G']));
        $this->assertEquals(2, UploadedFile::SmallestLimitInMegabytes(['2M']));
    }

    public function testSmallestPositiveLimitWins(): void
    {
        $this->assertEquals(2, UploadedFile::SmallestLimitInMegabytes(['8M', '2M', '128M']));
    }

    public function testUnlimitedLimitsAreIgnored(): void
    {
        $this->assertEquals(8, UploadedFile::SmallestLimitInMegabytes(['-1', '0', '8M']));
        $this->assertEquals(0, UploadedFile::SmallestLimitInMegabytes(['-1']));
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
