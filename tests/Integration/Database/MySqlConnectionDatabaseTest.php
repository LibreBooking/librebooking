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
            'CREATE TABLE `' . self::TABLE . '` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY, `name` VARCHAR(50) NOT NULL, `note` VARCHAR(50) NULL) ENGINE=InnoDB'
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

    public function testBoundValuesAreStoredAndMatchedVerbatim(): void
    {
        $insert = 'INSERT INTO `' . self::TABLE . '` (`name`) VALUES (@name)';
        $select = 'SELECT `name` FROM `' . self::TABLE . '` WHERE `name` = @name';

        foreach (["it's", 'back\\slash', 'what?', '@name', '100%', 'semi;colon', 'ünïcødé'] as $name) {
            $this->database->Execute($this->command($insert, ['@name' => $name]));

            $row = $this->database->Query($this->command($select, ['@name' => $name]))->GetRow();
            $this->assertSame($name, $row['name'], "Value $name should round-trip unchanged");
        }
    }

    public function testAnArrayParameterMatchesEachOfItsValues(): void
    {
        $select = 'SELECT `id` FROM `' . self::TABLE . '` WHERE `id` IN (@ids) ORDER BY `id`';

        // Rows 1 and 2 exist; 99 does not.
        $this->assertSame(2, $this->database->Query($this->command($select, ['@ids' => [1, 2, 99]]))->NumRows());
        $this->assertSame(1, $this->database->Query($this->command($select, ['@ids' => ['2']]))->NumRows());
        $this->assertSame(0, $this->database->Query($this->command($select, ['@ids' => []]))->NumRows());
    }

    public function testANullParameterIsStoredAsNull(): void
    {
        $this->database->Execute($this->command('UPDATE `' . self::TABLE . '` SET `note` = @note WHERE `id` = @id', ['@id' => 1, '@note' => null]));
        $this->database->Execute($this->command('UPDATE `' . self::TABLE . '` SET `note` = @note WHERE `id` = @id', ['@id' => 2, '@note' => '']));

        $rows = $this->database->Query(new AdHocCommand('SELECT `id`, `note` FROM `' . self::TABLE . '` ORDER BY `id`'));

        // Every non-null column is a string, whatever its SQL type.
        $this->assertSame(['id' => '1', 'note' => null], $rows->GetRow());
        $this->assertSame(['id' => '2', 'note' => ''], $rows->GetRow());
    }

    public function testExecuteInsertReturnsTheGeneratedId(): void
    {
        $id = $this->database->ExecuteInsert($this->command('INSERT INTO `' . self::TABLE . '` (`name`) VALUES (@name)', ['@name' => 'three']));

        $this->assertSame(3, $id);
    }

    public function testAMultiQueryCommandAppliesEveryStatement(): void
    {
        $command = $this->multiQueryCommand(
            'UPDATE `' . self::TABLE . '` SET `name` = @name WHERE `id` = @id; '
            . 'INSERT INTO `' . self::TABLE . '` (`id`, `name`) VALUES (@newId, @name)',
            ['@id' => 1, '@newId' => 3, '@name' => 'both']
        );

        $this->database->Execute($command);

        $rows = $this->database->Query(new AdHocCommand('SELECT `id`, `name` FROM `' . self::TABLE . '` ORDER BY `id`'));
        $this->assertSame(['id' => '1', 'name' => 'both'], $rows->GetRow());
        $this->assertSame(['id' => '2', 'name' => 'two'], $rows->GetRow());
        $this->assertSame(['id' => '3', 'name' => 'both'], $rows->GetRow());
    }

    public function testAMultiQueryCommandIsRolledBackWhenALaterStatementFails(): void
    {
        $command = $this->multiQueryCommand(
            'UPDATE `' . self::TABLE . '` SET `name` = @name WHERE `id` = @id; '
            . 'INSERT INTO `' . self::TABLE . '` (`id`, `name`) VALUES (@id, @name)', // duplicate key
            ['@id' => 1, '@name' => 'partial']
        );

        try {
            $this->database->Execute($command);
            $this->fail('Expected the duplicate key to fail the command');
        } catch (Exception $exception) {
            $this->assertStringContainsStringIgnoringCase('duplicate', $exception->getMessage());
        }

        $row = $this->database->Query(new AdHocCommand('SELECT `name` FROM `' . self::TABLE . '` WHERE `id` = 1'))->GetRow();
        $this->assertSame('one', $row['name'], 'The first statement should have been rolled back');
    }

    public function testQueryRejectsMultiQueryCommands(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->database->Query($this->multiQueryCommand('SELECT 1; SELECT 2', []));
    }

    /**
     * @param array<string, int|string|null> $parameters
     */
    private function multiQueryCommand(string $sql, array $parameters): SqlCommand
    {
        $command = new class ($sql) extends SqlCommand {
            public function IsMultiQuery()
            {
                return true;
            }
        };
        foreach ($parameters as $name => $value) {
            $command->AddParameter(new Parameter($name, $value));
        }

        return $command;
    }

    /**
     * @param array<string, int|string|array<int|string>|null> $parameters
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
