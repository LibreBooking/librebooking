<?php

class VersionDisplayResolver
{
    private const MAX_VERSION_METADATA_LENGTH = 40;

    private string $customVersionFile;
    private string $versionSuffixFile;

    public function __construct(?string $versionSuffixFile = null, ?string $customVersionFile = null)
    {
        $this->customVersionFile = $customVersionFile ?? ROOT_DIR . 'config/custom-version.txt';
        $this->versionSuffixFile = $versionSuffixFile ?? ROOT_DIR . 'config/version-suffix.txt';
    }

    public function GetDisplayVersion(string $baseVersion): string
    {
        $customVersion = $this->GetValidatedValue($this->customVersionFile, 'custom version');
        if ($customVersion !== null) {
            if (is_file($this->versionSuffixFile)) {
                Log::Error(
                    'Ignoring version suffix in %s because custom version in %s takes precedence',
                    $this->versionSuffixFile,
                    $this->customVersionFile
                );
            }

            return $customVersion . ' (custom)';
        }

        $displayBaseVersion = 'v' . $baseVersion;
        $versionSuffix = $this->GetValidatedValue($this->versionSuffixFile, 'version suffix');
        if ($versionSuffix === null) {
            return $displayBaseVersion;
        }

        return $displayBaseVersion . '-' . $versionSuffix;
    }

    private function GetValidatedValue(string $filePath, string $label): ?string
    {
        if (!is_file($filePath) || !is_readable($filePath)) {
            return null;
        }

        $contents = file_get_contents($filePath);
        if ($contents === false) {
            return null;
        }

        $contents = rtrim($contents, "\r\n");
        if (preg_match('/\R/', $contents) === 1) {
            Log::Error('Ignoring %s in %s because it contains more than one line', $label, $filePath);
            return null;
        }

        $value = trim($contents);
        if (empty($value)) {
            Log::Error('Ignoring %s in %s because it is empty', $label, $filePath);
            return null;
        }

        if (strlen($value) > self::MAX_VERSION_METADATA_LENGTH) {
            Log::Error(
                'Ignoring %s in %s because it exceeds %d characters',
                $label,
                $filePath,
                self::MAX_VERSION_METADATA_LENGTH
            );
            return null;
        }

        if (preg_match('/^[A-Za-z0-9._-]+$/', $value) !== 1) {
            Log::Error(
                'Ignoring %s in %s because it contains invalid characters. Allowed characters are letters, numbers, ".", "_", and "-"',
                $label,
                $filePath
            );
            return null;
        }

        return $value;
    }
}
