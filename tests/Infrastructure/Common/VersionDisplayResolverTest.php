<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class VersionDisplayResolverTest extends TestBase
{
    private const BASE_VERSION = '4.1.0';

    private array $tempPaths = [];

    public function testReturnsBaseVersionWhenSuffixFileIsMissing()
    {
        $resolver = new VersionDisplayResolver(ROOT_DIR . 'tests/data/does-not-exist-version-suffix.txt');

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals(self::BASE_VERSION, $displayVersion);
    }

    public function testAppendsValidSuffix()
    {
        $suffixFile = $this->createTempSuffixFile('abc123');
        $resolver = new VersionDisplayResolver($suffixFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('4.1.0-abc123', $displayVersion);
    }

    public function testAllowsSingleTrailingNewline()
    {
        $suffixFile = $this->createTempSuffixFile("abc123\n");
        $resolver = new VersionDisplayResolver($suffixFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('4.1.0-abc123', $displayVersion);
    }

    public function testReturnsBaseVersionWhenSuffixHasMultipleLines()
    {
        $suffixFile = $this->createTempSuffixFile("abc123\nsecond-line");
        $resolver = new VersionDisplayResolver($suffixFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals(self::BASE_VERSION, $displayVersion);
    }

    public function testReturnsBaseVersionWhenSuffixExceedsFortyCharacters()
    {
        $suffixFile = $this->createTempSuffixFile(str_repeat('a', 41));
        $resolver = new VersionDisplayResolver($suffixFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals(self::BASE_VERSION, $displayVersion);
    }

    public function testReturnsBaseVersionWhenSuffixContainsInvalidCharacters()
    {
        $suffixFile = $this->createTempSuffixFile('abc/123');
        $resolver = new VersionDisplayResolver($suffixFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals(self::BASE_VERSION, $displayVersion);
    }

    public function tearDown(): void
    {
        foreach ($this->tempPaths as $tempPath) {
            if (file_exists($tempPath)) {
                unlink($tempPath);
            }
        }

        $this->tempPaths = [];

        parent::tearDown();
    }

    private function createTempSuffixFile(string $contents): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'lb-version-suffix-');
        if ($tempFile === false) {
            throw new RuntimeException('Failed to create temporary version suffix file');
        }

        file_put_contents($tempFile, $contents);
        $this->tempPaths[] = $tempFile;

        return $tempFile;
    }
}
