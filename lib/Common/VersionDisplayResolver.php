<?php

class VersionDisplayResolver
{
    private const MAX_VERSION_SUFFIX_LENGTH = 40;

    private string $versionSuffixFile;

    public function __construct(?string $versionSuffixFile = null)
    {
        $this->versionSuffixFile = $versionSuffixFile ?? ROOT_DIR . 'config/version-suffix.txt';
    }

    public function GetDisplayVersion(string $baseVersion): string
    {
        if (!is_file($this->versionSuffixFile) || !is_readable($this->versionSuffixFile)) {
            return $baseVersion;
        }

        $versionSuffixContents = file_get_contents($this->versionSuffixFile);
        if ($versionSuffixContents === false) {
            return $baseVersion;
        }

        $versionSuffixContents = rtrim($versionSuffixContents, "\r\n");
        if (preg_match('/\R/', $versionSuffixContents) === 1) {
            Log::Error('Ignoring version suffix in %s because it contains more than one line', $this->versionSuffixFile);
            return $baseVersion;
        }

        $versionSuffix = trim($versionSuffixContents);
        if (empty($versionSuffix)) {
            return $baseVersion;
        }

        if (strlen($versionSuffix) > self::MAX_VERSION_SUFFIX_LENGTH) {
            Log::Error(
                'Ignoring version suffix in %s because it exceeds %d characters',
                $this->versionSuffixFile,
                self::MAX_VERSION_SUFFIX_LENGTH
            );
            return $baseVersion;
        }

        if (preg_match('/^[A-Za-z0-9._-]+$/', $versionSuffix) !== 1) {
            Log::Error(
                'Ignoring version suffix in %s because it contains invalid characters. Allowed characters are letters, numbers, ".", "_", and "-"',
                $this->versionSuffixFile
            );
            return $baseVersion;
        }

        return $baseVersion . '-' . $versionSuffix;
    }
}
