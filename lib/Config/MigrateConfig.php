<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Common/Converters/namespace.php');

class MigrateConfig
{
    public static function WriteStderr(string $message): void
    {
        fwrite(STDERR, $message . PHP_EOL);
    }

    public static function Usage(): void
    {
        self::WriteStderr('Usage: php scripts/migrate-config.php <old-config.php> [output.php] [--dist=/path/to/config.dist.php]');
        self::WriteStderr('If output.php is omitted, a sibling file ending in .migrated.php is written.');
    }

    public static function ExportValue(mixed $value, ?array $configDef = null): string
    {
        $configType = is_array($configDef) ? ($configDef['type'] ?? null) : null;

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if ($configType === ConfigSettingType::Boolean) {
            return BooleanConverter::ConvertValue($value) ? 'true' : 'false';
        }

        if ($configType === ConfigSettingType::Integer) {
            return (string) ((int) $value);
        }

        if (is_int($value)) {
            return (string) $value;
        }

        return var_export($value, true);
    }

    public static function ExportArray(array $array, int $indentLevel = 0, array $pathParts = []): string
    {
        $indent = str_repeat('    ', $indentLevel);
        $nextIndent = str_repeat('    ', $indentLevel + 1);
        $lines = ['['];

        foreach ($array as $key => $value) {
            $exportedKey = var_export($key, true);
            $currentPathParts = $pathParts;
            if (!($indentLevel === 0 && $key === Configuration::SETTINGS)) {
                $currentPathParts[] = (string) $key;
            }

            $exportedValue = is_array($value)
                ? self::ExportArray($value, $indentLevel + 1, $currentPathParts)
                : self::ExportValue($value, ConfigKeys::findByKey(implode('.', $currentPathParts)));

            $lines[] = "$nextIndent$exportedKey => $exportedValue,";
        }

        $lines[] = "$indent]";

        return implode(PHP_EOL, $lines);
    }

    public static function WriteConfigFile(string $path, array $settings): void
    {
        $php = "<?php\n\n";
        $php .= '// LibreBooking configuration file migrated at ' . date('c') . "\n\n";
        $php .= 'return ' . self::ExportArray([Configuration::SETTINGS => $settings]) . ";\n";

        if (file_put_contents($path, $php) === false) {
            throw new RuntimeException('Failed to write migrated config: ' . $path);
        }
    }

    public static function LoadSettings(string $configPath): array
    {
        // Legacy config files assign to $conf via side-effect instead of using return.
        $conf = [];
        $loaded = require $configPath;

        if (is_array($loaded) && isset($loaded['settings'])) {
            return $loaded['settings'];
        }

        if (isset($conf['settings'])) {
            self::WriteStderr('[CONFIG] Legacy config format detected in ' . $configPath . '. Please consider updating to the new format.');
            return $conf['settings'];
        }

        throw new RuntimeException("Invalid config file '$configPath': 'settings' section missing");
    }

    public static function NormalizeSettings(array $settings): array
    {
        $configFile = new ConfigurationFile([
            Configuration::SETTINGS => $settings,
        ]);

        return $configFile->GetValues();
    }

    public static function GetNormalizedSettings(string $configPath): array
    {
        return self::NormalizeSettings(self::LoadSettings($configPath));
    }

    public static function MergeSettings(array $currentSettings, array $templateSettings, array $pathParts = []): array
    {
        $merged = [];

        foreach ($currentSettings as $key => $currentValue) {
            $currentPathParts = [...$pathParts, (string) $key];
            $fullKey = implode('.', $currentPathParts);

            if (array_key_exists($key, $templateSettings)) {
                $templateValue = $templateSettings[$key];

                if (is_array($currentValue) && is_array($templateValue)) {
                    $merged[$key] = self::MergeSettings($currentValue, $templateValue, $currentPathParts);
                } else {
                    $merged[$key] = $currentValue;
                }
            } else {
                self::WriteStderr('Warning: preserving key not present in config.dist.php: ' . $fullKey);
                $merged[$key] = $currentValue;
            }
        }

        foreach ($templateSettings as $key => $templateValue) {
            if (!array_key_exists($key, $currentSettings)) {
                $merged[$key] = $templateValue;
            }
        }

        return $merged;
    }

    public static function Main(array $args): int
    {
        $source = null;
        $output = null;
        $dist = ROOT_DIR . 'config/config.dist.php';

        foreach (array_slice($args, 1) as $arg) {
            if ($arg === '--help' || $arg === '-h') {
                self::Usage();
                return 0;
            }

            if (str_starts_with($arg, '--dist=')) {
                $dist = substr($arg, strlen('--dist='));
                continue;
            }

            if ($source === null) {
                $source = $arg;
                continue;
            }

            if ($output === null) {
                $output = $arg;
                continue;
            }

            self::Usage();
            return 1;
        }

        if ($source === null) {
            self::Usage();
            return 1;
        }

        $source = realpath($source) ?: $source;
        $dist = realpath($dist) ?: $dist;

        if (!file_exists($source)) {
            self::WriteStderr('Source config file not found: ' . $source);
            return 1;
        }

        if (!file_exists($dist)) {
            self::WriteStderr('Template config file not found: ' . $dist);
            return 1;
        }

        if ($output === null) {
            $pathInfo = pathinfo($source);
            $dirname = $pathInfo['dirname'] ?? '.';
            $filename = $pathInfo['filename'] ?? 'config';
            $output = $dirname . '/' . $filename . '.migrated.php';
        }

        $resolvedOutput = realpath($output) ?: $output;
        if ($resolvedOutput === $source) {
            self::WriteStderr('Output file matches the source file and will not be overwritten: ' . $output);
            return 1;
        }

        if (file_exists($output)) {
            self::WriteStderr('Output file already exists and will not be overwritten: ' . $output);
            return 1;
        }

        try {
            $currentSettings = self::GetNormalizedSettings($source);
            $mergedSettings = self::MergeSettings($currentSettings, self::GetNormalizedSettings($dist));

            self::WriteConfigFile($output, $mergedSettings);

            fwrite(STDOUT, 'Migrated config written to: ' . $output . PHP_EOL);
        } catch (Throwable $e) {
            self::WriteStderr($e->getMessage());
            return 1;
        }

        return 0;
    }
}
