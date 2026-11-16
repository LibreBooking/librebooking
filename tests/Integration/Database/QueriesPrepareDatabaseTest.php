<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

/**
 * Prepares every statement in Queries against the real schema without
 * executing it. MySQL checks syntax, table and column names and the
 * placeholder count at prepare time, so this catches typos and schema drift
 * in queries that no other test runs.
 */
class QueriesPrepareDatabaseTest extends DatabaseTestCase
{
    /**
     * Constants that are not complete statements: WHERE fragments appended
     * to another query, and the report template whose [TOKEN]s are filled in
     * by Queries::Build().
     */
    private const NOT_STATEMENTS = [
        'GET_ATTRIBUTES_BY_CATEGORY_WHERE',
        'GET_ATTRIBUTE_BY_ID_WHERE',
        'GET_RESERVATION_LIST_TEMPLATE',
    ];

    private const PLACEHOLDER_PATTERN = '/@[A-Za-z0-9_]+/';
    private const STATEMENT_PATTERN = '/^\s*(SELECT|INSERT|UPDATE|DELETE|REPLACE)\b/i';

    private \mysqli $link;

    public function setUp(): void
    {
        parent::setUp();
        $this->requireLoadedSchema();

        $this->link = mysqli_connect($this->dbHost, $this->dbUser, $this->dbPassword, $this->dbName, (int)$this->dbPort);
        mysqli_set_charset($this->link, 'utf8mb4');
    }

    public function tearDown(): void
    {
        if (isset($this->link)) {
            mysqli_close($this->link);
        }

        parent::tearDown();
    }

    public function testEveryQueryPreparesAgainstTheSchema(): void
    {
        $failures = [];
        $prepared = 0;
        $skipped = [];

        foreach ((new ReflectionClass(Queries::class))->getConstants() as $name => $sql) {
            if (!is_string($sql)) {
                continue;
            }
            if (!preg_match(self::STATEMENT_PATTERN, $sql) || in_array($name, self::NOT_STATEMENTS, true)) {
                $skipped[] = $name;
                continue;
            }

            foreach ((new MySqlCommandAdapter($this->command($sql)))->GetStatements() as $statement) {
                $error = $this->prepareError($statement['query']);
                if ($error !== null) {
                    $failures[] = "$name: $error";
                }
                $prepared++;
            }
        }

        $this->assertSame([], $failures, "Statements that failed to prepare:\n" . implode("\n", $failures));
        $this->assertSame(self::NOT_STATEMENTS, $skipped, 'Only the known fragments and templates should be skipped');
        $this->assertGreaterThan(200, $prepared, 'Expected to prepare every statement in Queries');
    }

    /**
     * A command with one dummy parameter per placeholder. Statements joined
     * with ';' are split so each part can be prepared on its own.
     */
    private function command(string $sql): SqlCommand
    {
        $command = new class ($sql) extends SqlCommand {
            public function IsMultiQuery()
            {
                return str_contains((string)$this->GetQuery(), ';');
            }
        };

        preg_match_all(self::PLACEHOLDER_PATTERN, $sql, $matches);
        foreach (array_unique($matches[0]) as $placeholder) {
            $command->AddParameter(new Parameter($placeholder, '1'));
        }

        return $command;
    }

    private function prepareError(string $query): ?string
    {
        try {
            $statement = mysqli_prepare($this->link, $query);
        } catch (mysqli_sql_exception $exception) {
            return $exception->getMessage();
        }

        if ($statement === false) {
            return mysqli_error($this->link);
        }

        mysqli_stmt_close($statement);

        return null;
    }
}
