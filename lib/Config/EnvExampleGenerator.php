<?php

declare(strict_types=1);

/**
 * Generates .env.example from ConfigKeys metadata.
 *
 * Produces a .env file with comments and default values,
 * using the same metadata pipeline as ConfigDistGenerator.
 */
class EnvExampleGenerator
{
    /**
     * Render the full .env.example file content.
     */
    public static function render(): string
    {
        $settings = ConfigDistGenerator::generateSettingsArray();
        $lines = [];

        foreach (ConfigKeysMeta::groupFlatEntries(flatEntries: $settings['flat']) as $groupTitle => $entries) {
            self::appendSectionHeader(lines: $lines, title: $groupTitle);
            $lines[] = '';

            foreach ($entries as $entry) {
                self::appendEntry(lines: $lines, entry: $entry);
            }

            self::trimTrailingBlankLine(lines: $lines);
            $lines[] = '';
        }

        foreach ($settings['sections'] as $sectionName => $entries) {
            $sectionTitle = ConfigKeysMeta::sectionTitle(section: $sectionName);
            $lines[] = '';
            self::appendSectionHeader(lines: $lines, title: $sectionTitle);
            $lines[] = '';

            foreach ($entries as $entry) {
                self::appendEntry(lines: $lines, entry: $entry);
            }

            self::trimTrailingBlankLine(lines: $lines);
        }

        $lines[] = '';

        return implode(separator: "\n", array: $lines);
    }

    /**
     * Write the rendered .env.example to a file path.
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
        $defaultPath = dirname(__DIR__, levels: 2) . '/.env.example';
        $mode = $argv[1] ?? '--write';
        $outputPath = $argv[2] ?? $defaultPath;

        try {
            switch ($mode) {
                case '--check':
                    if (self::check(path: $outputPath)) {
                        fwrite(stream: STDOUT, data: "{$outputPath} is up-to-date.\n");
                        return 0;
                    }
                    fwrite(stream: STDERR, data: "{$outputPath} is out of date. Run: composer env-example:generate\n");
                    return 1;

                case '--write':
                    self::writeToFile(path: $outputPath);
                    fwrite(stream: STDOUT, data: "Generated {$outputPath}\n");
                    return 0;

                default:
                    fwrite(stream: STDERR, data: "Usage: generate-env-example.php [--write|--check] [path]\n");
                    return 2;
            }
        } catch (RuntimeException $e) {
            fwrite(stream: STDERR, data: "Error: {$e->getMessage()}\n");
            return 1;
        }
    }

    private static function appendSectionHeader(array &$lines, string $title): void
    {
        $border = str_repeat(string: '#', times: strlen($title) + 2);
        $lines[] = $border;
        $lines[] = "# {$title}";
        $lines[] = $border;
    }

    private static function appendEntry(array &$lines, array $entry): void
    {
        $comment = ConfigKeysMeta::getComment(entry: $entry);
        $choices = $entry['choices'] ?? null;
        $type = $entry['type'] ?? 'string';
        $envKey = ConfigKeysMeta::envKey(configKey: $entry['key']);

        if ($comment !== '') {
            $hasExplicitComment = isset($entry['config_file_comment']) && $entry['config_file_comment'] !== '';
            foreach (explode(separator: "\n", string: $comment) as $paragraph) {
                if ($hasExplicitComment) {
                    $lines[] = "# {$paragraph}";
                } else {
                    $wrapped = wordwrap(string: $paragraph, width: 76, break: "\n", cut_long_words: false);
                    foreach (explode(separator: "\n", string: $wrapped) as $commentLine) {
                        $lines[] = "# {$commentLine}";
                    }
                }
            }
        }

        if ($type === 'boolean' && $comment !== '' && !str_contains(haystack: $comment, needle: 'true/false')) {
            $lastIndex = count($lines) - 1;
            $lines[$lastIndex] .= ' (true/false)';
        }

        if (is_array($choices) && $choices !== [] && !str_contains(haystack: $comment, needle: 'Options:')) {
            $choiceKeys = array_keys($choices);
            $displayChoices = array_filter($choiceKeys, static fn ($c): bool => $c !== '' && $c !== 0);
            if ($displayChoices !== []) {
                $lines[] = '# Options: ' . implode(separator: ', ', array: $displayChoices);
            }
        }

        $rendered = self::renderValue(value: $entry['default'], type: $type);
        $lines[] = "{$envKey}={$rendered}";
        $lines[] = '';
    }

    private static function renderValue(mixed $value, string $type): string
    {
        return match ($type) {
            'boolean' => $value ? 'true' : 'false',
            'integer' => (string)(int)$value,
            default => self::renderStringValue(value: (string)$value),
        };
    }

    private static function renderStringValue(string $value): string
    {
        if ($value === '') {
            return '';
        }

        return "'" . addcslashes(string: $value, characters: "'\\") . "'";
    }

    private static function trimTrailingBlankLine(array &$lines): void
    {
        if ($lines !== [] && end($lines) === '') {
            array_pop($lines);
        }
    }
}
