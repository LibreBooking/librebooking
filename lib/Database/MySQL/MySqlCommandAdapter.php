<?php

declare(strict_types=1);

/**
 * Converts an ISqlCommand into the form mysqli prepared statements need.
 *
 * The command's named placeholders (@userid, @name, ...) become positional
 * '?' placeholders and the values to bind are collected in the order the
 * placeholders appear in the query. Values never become part of the SQL
 * text; the database driver binds them separately.
 *
 * ParameterRaw values are the exception: they hold identifiers such as an
 * ORDER BY column, which cannot be bound, so they are written into the text.
 */
class MySqlCommandAdapter
{
    /**
     * @var list<array{query: string, values: list<string|null>}>
     */
    private array $statements = [];

    public function __construct(ISqlCommand $command)
    {
        $parameters = self::parametersByName($command->GetParameters());
        $sql = (string) $command->GetQuery();

        $pieces = $command->IsMultiQuery() ? self::splitStatements($sql) : [$sql];
        foreach ($pieces as $piece) {
            $this->statements[] = self::convert($piece, $parameters);
        }
    }

    /**
     * One entry per SQL statement. A command that is not a multi-query
     * command always yields exactly one entry.
     *
     * @return list<array{query: string, values: list<string|null>}>
     */
    public function GetStatements(): array
    {
        return $this->statements;
    }

    /**
     * The converted SQL with '?' placeholders. Multi-query commands are
     * joined with '; ' for logging; execute them via GetStatements().
     */
    public function GetQuery(): string
    {
        return implode('; ', array_column($this->statements, 'query'));
    }

    /**
     * The values to bind, in placeholder order, across all statements.
     *
     * @return list<string|null>
     */
    public function GetValues(): array
    {
        return array_merge(...array_column($this->statements, 'values'));
    }

    /**
     * @return array<string, Parameter> keyed by placeholder name; a name
     *     added more than once keeps the last parameter
     */
    private static function parametersByName(Parameters $parameters): array
    {
        $byName = [];
        for ($p = 0; $p < $parameters->Count(); $p++) {
            $parameter = $parameters->Items($p);
            $byName[$parameter->Name] = $parameter;
        }

        return $byName;
    }

    /**
     * @param array<string, Parameter> $parameters
     * @return array{query: string, values: list<string|null>}
     */
    private static function convert(string $sql, array $parameters): array
    {
        if ($parameters === []) {
            return ['query' => $sql, 'values' => []];
        }

        // Single pass so an inserted value is never searched for later placeholder names.
        // Longest names first so '@userid' is not consumed by '@user'.
        $names = array_keys($parameters);
        usort($names, fn ($a, $b) => strlen($b) <=> strlen($a));
        $pattern = '/' . implode('|', array_map(fn ($name) => preg_quote($name, '/'), $names)) . '/';

        $values = [];
        $query = preg_replace_callback(
            $pattern,
            function (array $match) use ($parameters, &$values): string {
                $parameter = $parameters[$match[0]];

                if ($parameter instanceof ParameterRaw) {
                    return (string) $parameter->Value;
                }

                if (is_array($parameter->Value)) {
                    // An empty list binds one empty string, so "IN (@ids)" stays valid SQL and matches nothing.
                    $list = $parameter->Value === [] ? [''] : array_values($parameter->Value);
                    foreach ($list as $value) {
                        $values[] = self::bindable($value) ?? '';
                    }

                    return implode(',', array_fill(0, count($list), '?'));
                }

                $values[] = self::bindable($parameter->Value);

                return '?';
            },
            $sql
        );

        return ['query' => $query, 'values' => $values];
    }

    /**
     * mysqli binds every value as a string; null is bound as SQL NULL.
     */
    private static function bindable(mixed $value): ?string
    {
        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    /**
     * Splits a multi-statement command on semicolons outside quoted strings.
     * Prepared statements can only hold one statement each.
     *
     * @return list<string>
     */
    private static function splitStatements(string $sql): array
    {
        $statements = [];
        $current = '';
        $quote = null;
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if ($quote !== null) {
                $current .= $char;
                if ($char === '\\' && $i + 1 < $length) {
                    $current .= $sql[++$i];
                } elseif ($char === $quote) {
                    $quote = null;
                }
                continue;
            }

            if ($char === "'" || $char === '"' || $char === '`') {
                $quote = $char;
                $current .= $char;
                continue;
            }

            if ($char === ';') {
                $statements[] = $current;
                $current = '';
                continue;
            }

            $current .= $char;
        }
        $statements[] = $current;

        return array_values(array_filter(array_map('trim', $statements), fn (string $statement) => $statement !== ''));
    }
}
