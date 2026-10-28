<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');

/**
 * Runs Database/MySqlConnection against a real database. Uses its own scratch
 * table, so it does not need the LibreBooking schema to be loaded.
 */
class MySqlConnectionDatabaseTest extends DatabaseTestCase
{
    private const TABLE = 'integration_affected_rows';

    private Database $database;

    public function setUp(): void
    {
        parent::setUp();

        $this->database = $this->realDatabase();
        $this->database->Execute(new AdHocCommand('DROP TABLE IF EXISTS `' . self::TABLE . '`'));
        $this->database->Execute(new AdHocCommand(
            'CREATE TABLE `' . self::TABLE . '` (`id` INT UNSIGNED NOT NULL PRIMARY KEY, `name` VARCHAR(50) NOT NULL) ENGINE=InnoDB'
        ));
        $this->database->Execute(new AdHocCommand(
            'INSERT INTO `' . self::TABLE . "` (`id`, `name`) VALUES (1, 'one'), (2, 'two')"
        ));
    }

    public function tearDown(): void
    {
        if (isset($this->database)) {
            $this->database->Execute(new AdHocCommand('DROP TABLE IF EXISTS `' . self::TABLE . '`'));
        }

        parent::tearDown();
    }

    public function testExecuteAffectedRowsCountsADeletedRow(): void
    {
        $affected = $this->database->ExecuteAffectedRows($this->command('DELETE FROM `' . self::TABLE . '` WHERE `id` = @id', ['@id' => 1]));

        $this->assertSame(1, $affected);
        $this->assertSame(1, $this->rowCount());
    }

    public function testExecuteAffectedRowsIsZeroWhenNothingMatches(): void
    {
        $affected = $this->database->ExecuteAffectedRows($this->command('DELETE FROM `' . self::TABLE . '` WHERE `id` = @id', ['@id' => 99]));

        $this->assertSame(0, $affected);
        $this->assertSame(2, $this->rowCount());
    }

    public function testExecuteAffectedRowsCountsAConditionalInsert(): void
    {
        $insertIfMissing = 'INSERT INTO `' . self::TABLE . '` (`id`, `name`) SELECT @id, @name FROM DUAL '
            . 'WHERE NOT EXISTS (SELECT 1 FROM `' . self::TABLE . '` WHERE `name` = @name)';

        $this->assertSame(1, $this->database->ExecuteAffectedRows($this->command($insertIfMissing, ['@id' => 3, '@name' => 'three'])));
        $this->assertSame(0, $this->database->ExecuteAffectedRows($this->command($insertIfMissing, ['@id' => 4, '@name' => 'three'])));
        $this->assertSame(3, $this->rowCount());
    }

    public function testExecuteAffectedRowsCountsOnlyChangedRowsForUpdate(): void
    {
        $update = 'UPDATE `' . self::TABLE . '` SET `name` = @name WHERE `id` = @id';

        $this->assertSame(1, $this->database->ExecuteAffectedRows($this->command($update, ['@id' => 1, '@name' => 'uno'])));
        // MySQL/MariaDB count only rows whose values changed, as documented on ExecuteAffectedRows().
        $this->assertSame(0, $this->database->ExecuteAffectedRows($this->command($update, ['@id' => 1, '@name' => 'uno'])));
    }

    public function testExecuteAffectedRowsPropagatesErrorsAndTheConnectionStillWorks(): void
    {
        try {
            $this->database->ExecuteAffectedRows(new AdHocCommand('DELETE FROM `no_such_table_for_integration_test`'));
            $this->fail('Expected an exception for a query against a missing table');
        } catch (Exception $exception) {
            // mysqli throws mysqli_sql_exception itself (its default since PHP 8.1).
            $this->assertStringContainsString('no_such_table_for_integration_test', $exception->getMessage());
        }

        $this->assertSame(2, $this->rowCount(), 'The database should still be usable after a failed statement');
    }

    /**
     * @param array<string, int|string> $parameters
     */
    private function command(string $sql, array $parameters): SqlCommand
    {
        $command = new SqlCommand($sql);
        foreach ($parameters as $name => $value) {
            $command->AddParameter(new Parameter($name, $value));
        }

        return $command;
    }

    private function rowCount(): int
    {
        $row = $this->database->Query(new AdHocCommand('SELECT COUNT(*) AS `count` FROM `' . self::TABLE . '`'))->GetRow();

        return (int)$row['count'];
    }
}
