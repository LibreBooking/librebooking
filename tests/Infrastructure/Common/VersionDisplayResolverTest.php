<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/namespace.php');

class VersionDisplayResolverTest extends TestBase
{
    private const BASE_VERSION = '4.1.0';
    private const MISSING_SUFFIX_FILE = ROOT_DIR . 'tests/data/does-not-exist-version-suffix.txt';
    private const MISSING_CUSTOM_VERSION_FILE = ROOT_DIR . 'tests/data/does-not-exist-custom-version.txt';

    private array $tempPaths = [];

    public function testReturnsBaseVersionWhenSuffixFileIsMissing()
    {
        $resolver = new VersionDisplayResolver(self::MISSING_SUFFIX_FILE, self::MISSING_CUSTOM_VERSION_FILE);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0', $displayVersion);
    }

    public function testAppendsValidSuffix()
    {
        $suffixFile = $this->createTempMetadataFile('abc123');
        $resolver = new VersionDisplayResolver($suffixFile, self::MISSING_CUSTOM_VERSION_FILE);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0-abc123', $displayVersion);
    }

    public function testAllowsSingleTrailingNewline()
    {
        $suffixFile = $this->createTempMetadataFile("abc123\n");
        $resolver = new VersionDisplayResolver($suffixFile, self::MISSING_CUSTOM_VERSION_FILE);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0-abc123', $displayVersion);
    }

    public function testReturnsBaseVersionWhenSuffixHasMultipleLines()
    {
        $suffixFile = $this->createTempMetadataFile("abc123\nsecond-line");
        $resolver = new VersionDisplayResolver($suffixFile, self::MISSING_CUSTOM_VERSION_FILE);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0', $displayVersion);
    }

    public function testReturnsBaseVersionWhenSuffixExceedsFortyCharacters()
    {
        $suffixFile = $this->createTempMetadataFile(str_repeat('a', 41));
        $resolver = new VersionDisplayResolver($suffixFile, self::MISSING_CUSTOM_VERSION_FILE);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0', $displayVersion);
    }

    public function testReturnsBaseVersionWhenSuffixContainsInvalidCharacters()
    {
        $suffixFile = $this->createTempMetadataFile('abc/123');
        $resolver = new VersionDisplayResolver($suffixFile, self::MISSING_CUSTOM_VERSION_FILE);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0', $displayVersion);
    }

    public function testUsesCustomVersionWhenValid()
    {
        $customVersionFile = $this->createTempMetadataFile('v4.2.0-36-ge81f46586');
        $resolver = new VersionDisplayResolver(self::MISSING_SUFFIX_FILE, $customVersionFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.2.0-36-ge81f46586 (custom)', $displayVersion);
    }

    public function testCustomVersionAllowsSingleTrailingNewline()
    {
        $customVersionFile = $this->createTempMetadataFile("v4.2.0-36-ge81f46586\n");
        $resolver = new VersionDisplayResolver(self::MISSING_SUFFIX_FILE, $customVersionFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.2.0-36-ge81f46586 (custom)', $displayVersion);
    }

    public function testFallsBackToSuffixWhenCustomVersionHasMultipleLines()
    {
        $suffixFile = $this->createTempMetadataFile('abc123');
        $customVersionFile = $this->createTempMetadataFile("v4.2.0-36-ge81f46586\nsecond-line");
        $resolver = new VersionDisplayResolver($suffixFile, $customVersionFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0-abc123', $displayVersion);
    }

    public function testFallsBackToSuffixWhenCustomVersionExceedsFortyCharacters()
    {
        $suffixFile = $this->createTempMetadataFile('abc123');
        $customVersionFile = $this->createTempMetadataFile(str_repeat('a', 41));
        $resolver = new VersionDisplayResolver($suffixFile, $customVersionFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0-abc123', $displayVersion);
    }

    public function testFallsBackToSuffixWhenCustomVersionContainsInvalidCharacters()
    {
        $suffixFile = $this->createTempMetadataFile('abc123');
        $customVersionFile = $this->createTempMetadataFile('release candidate');
        $resolver = new VersionDisplayResolver($suffixFile, $customVersionFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.1.0-abc123', $displayVersion);
    }

    public function testCustomVersionOverridesSuffixWhenBothAreValid()
    {
        $suffixFile = $this->createTempMetadataFile('abc123');
        $customVersionFile = $this->createTempMetadataFile('v4.2.0-36-ge81f46586');
        $resolver = new VersionDisplayResolver($suffixFile, $customVersionFile);

        $displayVersion = $resolver->GetDisplayVersion(self::BASE_VERSION);

        $this->assertEquals('v4.2.0-36-ge81f46586 (custom)', $displayVersion);
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

    private function createTempMetadataFile(string $contents): string
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'lb-version-meta-');
        if ($tempFile === false) {
            throw new RuntimeException('Failed to create temporary version metadata file');
        }

        file_put_contents($tempFile, $contents);
        $this->tempPaths[] = $tempFile;

        return $tempFile;
    }
}
