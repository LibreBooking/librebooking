<?php

declare(strict_types=1);

/**
 * Generates config/config.dist.php from ConfigKeys metadata.
 *
 * Two layers:
 *  - generateSettingsArray(): canonical default settings structure
 *  - render(): full PHP file text with comments
 */
class ConfigDistGenerator
{
    private const FILE_HEADER = <<<'PHP'
<?php

/**
 * LibreBooking configuration file
 *
 * This file contains the default configuration for LibreBooking.
 * It is used to set up the application and can be overridden by environment variables.
 * The settings are grouped into sections for easier management.
 */

PHP;

    /**
     * Build the canonical settings array from ConfigKeys.
     *
     * Keys preserve ConfigKeys constant declaration order.
     * Flat keys (no section) and sectioned keys are separated
     * but maintain their relative declaration order within each group.
     *
     * @return array{flat: array<string, array>, sections: array<string, array<string, array>>}
     */
    public static function generateSettingsArray(): array
    {
        $flat = [];
        $sections = [];

        foreach (ConfigKeys::all() as $entry) {
            $key = $entry['key'];
            $section = $entry['section'] ?? null;

            if ($section === null || $section === '') {
                $flat[$key] = $entry;
            } else {
                $prefix = $section . '.';
                if (str_starts_with(haystack: $key, needle: $prefix)) {
                    $nestedKey = substr(string: $key, offset: strlen($prefix));
                } else {
                    $nestedKey = $key;
                }
                $sections[$section][$nestedKey] = $entry;
            }
        }

        return ['flat' => $flat, 'sections' => $sections];
    }

    /**
     * Render the full config.dist.php file content.
     */
    public static function render(): string
    {
        $settings = self::generateSettingsArray();
        $lines = [];
        $lines[] = self::FILE_HEADER;
        $lines[] = 'return [';
        $lines[] = "    'settings' => [";
        $lines[] = '';

        foreach (ConfigKeysMeta::groupFlatEntries(flatEntries: $settings['flat']) as $groupTitle => $entries) {
            self::appendSectionHeader(lines: $lines, title: $groupTitle, indent: 2);
            $lines[] = '';

            foreach ($entries as $key => $entry) {
                self::appendEntry(lines: $lines, key: $key, entry: $entry, indent: 2);
            }

            self::trimTrailingBlankLine(lines: $lines);
            $lines[] = '';
        }

        foreach ($settings['sections'] as $sectionName => $entries) {
            $sectionTitle = ConfigKeysMeta::sectionTitle(section: $sectionName);
            $lines[] = '';
            self::appendSectionHeader(lines: $lines, title: $sectionTitle, indent: 2);
            $lines[] = '';
            $lines[] = "        '{$sectionName}' => [";

            foreach ($entries as $nestedKey => $entry) {
                self::appendEntry(lines: $lines, key: $nestedKey, entry: $entry, indent: 3);
            }

            self::trimTrailingBlankLine(lines: $lines);
            $lines[] = '        ],';
        }

        $lines[] = '    ],';
        $lines[] = '];';
        $lines[] = '';

        return implode(separator: "\n", array: $lines);
    }

    /**
     * Write the rendered config to a file path.
     */
    public static function writeToFile(string $path): void
    {
        $dir = dirname($path);
        if (!is_dir(filename: $dir)) {
            throw new RuntimeException("Directory does not exist: {$dir}");
        }
        if (is_dir(filename: $path)) {
            throw new RuntimeException("Path is a directory, not a file: {$path}");
        }

        $content = self::render();
        $result = file_put_contents(filename: $path, data: $content);
        if ($result === false) {
            throw new RuntimeException("Failed to write to {$path}");
        }
    }

    /**
     * Check if an existing file matches the generated output.
     *
     * @return bool true if the file is up-to-date
     */
    public static function check(string $path): bool
    {
        if (!file_exists(filename: $path)) {
            return false;
        }

        $existing = file_get_contents(filename: $path);
        if ($existing === false) {
            throw new RuntimeException("Failed to read {$path}");
        }
        $generated = self::render();

        return $existing === $generated;
    }

    /**
     * CLI entry point.
     *
     * @param list<string> $argv
     * @return int exit code
     */
    public static function main(array $argv): int
    {
        $defaultPath = dirname(__DIR__, levels: 2) . '/config/config.dist.php';
        $mode = $argv[1] ?? '--write';
        $outputPath = $argv[2] ?? $defaultPath;

        try {
            switch ($mode) {
                case '--check':
                    if (self::check(path: $outputPath)) {
                        fwrite(stream: STDOUT, data: "{$outputPath} is up-to-date.\n");
                        return 0;
                    }
                    fwrite(stream: STDERR, data: "{$outputPath} is out of date. Run: composer config-dist:generate\n");
                    return 1;

                case '--write':
                    self::writeToFile(path: $outputPath);
                    fwrite(stream: STDOUT, data: "Generated {$outputPath}\n");
                    return 0;

                default:
                    fwrite(stream: STDERR, data: "Usage: generate-config-dist.php [--write|--check] [path]\n");
                    return 2;
            }
        } catch (RuntimeException $e) {
            fwrite(stream: STDERR, data: "Error: {$e->getMessage()}\n");
            return 1;
        }
    }

    private static function appendSectionHeader(array &$lines, string $title, int $indent): void
    {
        $pad = str_repeat(string: '    ', times: $indent);
        $border = str_repeat(string: '#', times: strlen($title) + 2);
        $lines[] = "{$pad}{$border}";
        $lines[] = "{$pad}# {$title}";
        $lines[] = "{$pad}{$border}";
    }

    private static function appendEntry(array &$lines, string $key, array $entry, int $indent): void
    {
        $pad = str_repeat(string: '    ', times: $indent);
        $comment = ConfigKeysMeta::getComment(entry: $entry);
        $choices = $entry['choices'] ?? null;
        $type = $entry['type'] ?? 'string';

        if ($comment !== '') {
            $hasExplicitComment = isset($entry['config_file_comment']) && $entry['config_file_comment'] !== '';
            foreach (explode(separator: "\n", string: $comment) as $paragraph) {
                if ($hasExplicitComment) {
                    // config_file_comment is pre-formatted, don't re-wrap
                    $lines[] = "{$pad}# {$paragraph}";
                } else {
                    $wrapped = wordwrap(string: $paragraph, width: 76, break: "\n", cut_long_words: false);
                    foreach (explode(separator: "\n", string: $wrapped) as $commentLine) {
                        $lines[] = "{$pad}# {$commentLine}";
                    }
                }
            }
        }

        if ($type === 'boolean' && $comment !== '' && !str_contains(haystack: $comment, needle: 'true/false')) {
            // Append (true/false) hint to the last comment line
            $lastIndex = count($lines) - 1;
            $lines[$lastIndex] .= ' (true/false)';
        }

        if (is_array($choices) && $choices !== [] && !str_contains(haystack: $comment, needle: 'Options:')) {
            $choiceKeys = array_keys($choices);
            $displayChoices = array_filter($choiceKeys, static fn ($c): bool => $c !== '' && $c !== 0);
            if ($displayChoices !== []) {
                $lines[] = "{$pad}# Options: " . implode(separator: ', ', array: $displayChoices);
            }
        }

        $rendered = self::renderValue(value: $entry['default'], type: $type);
        $lines[] = "{$pad}'{$key}' => {$rendered},";
        $lines[] = '';
    }

    private static function renderValue(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? 'true' : 'false',
            'integer' => (string)(int)$value,
            default => "'" . addcslashes(string: (string)$value, characters: "'\\") . "'",
        };
    }

    private static function trimTrailingBlankLine(array &$lines): void
    {
        if ($lines !== [] && end($lines) === '') {
            array_pop($lines);
        }
    }

}
