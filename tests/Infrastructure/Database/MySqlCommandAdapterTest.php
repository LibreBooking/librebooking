<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');
require_once(ROOT_DIR . 'lib/Database/Commands/namespace.php');

class MySqlCommandAdapterTest extends TestBase
{
    public function testReplacesNamedPlaceholdersWithPositionalOnesInQueryOrder(): void
    {
        // Parameters are added in the opposite order to their placeholders.
        $command = $this->command('SELECT * FROM `t` WHERE `a` = @a AND `b` = @b', ['@b' => 'second', '@a' => 'first']);

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame('SELECT * FROM `t` WHERE `a` = ? AND `b` = ?', $adapter->GetQuery());
        $this->assertSame(['first', 'second'], $adapter->GetValues());
    }

    public function testARepeatedPlaceholderBindsItsValueEachTime(): void
    {
        $command = $this->command('SELECT 1 WHERE `name` = @user OR `mail` = @user', ['@user' => 'jo']);

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame('SELECT 1 WHERE `name` = ? OR `mail` = ?', $adapter->GetQuery());
        $this->assertSame(['jo', 'jo'], $adapter->GetValues());
    }

    public function testALongerNameIsNotConsumedByAShorterPrefix(): void
    {
        $command = $this->command(
            'SELECT @userid, @user, @role10, @role1',
            ['@user' => 'u', '@userid' => '7', '@role1' => 'one', '@role10' => 'ten']
        );

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame('SELECT ?, ?, ?, ?', $adapter->GetQuery());
        $this->assertSame(['7', 'u', 'ten', 'one'], $adapter->GetValues());
    }

    public function testValuesAreNeverRescannedForPlaceholders(): void
    {
        $command = $this->command('SELECT @a, @b', ['@a' => '@b', '@b' => 'x?y']);

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame('SELECT ?, ?', $adapter->GetQuery());
        $this->assertSame(['@b', 'x?y'], $adapter->GetValues());
    }

    public function testAnArrayExpandsToOnePlaceholderPerElement(): void
    {
        $command = $this->command('SELECT 1 WHERE `id` IN (@ids)', ['@ids' => [3, 'four', null]]);

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame('SELECT 1 WHERE `id` IN (?,?,?)', $adapter->GetQuery());
        $this->assertSame(['3', 'four', ''], $adapter->GetValues());
    }

    public function testAnEmptyArrayBindsOneEmptyStringSoTheSqlStaysValid(): void
    {
        $command = $this->command('SELECT 1 WHERE `id` IN (@ids)', ['@ids' => []]);

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame('SELECT 1 WHERE `id` IN (?)', $adapter->GetQuery());
        $this->assertSame([''], $adapter->GetValues());
    }

    public function testScalarsBindAsStringsAndNullAsNull(): void
    {
        $command = $this->command('SELECT @i, @f, @t, @n', ['@i' => 5, '@f' => 1.5, '@t' => true, '@n' => null]);

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame(['5', '1.5', '1', null], $adapter->GetValues());
    }

    public function testARawParameterIsWrittenIntoTheSqlText(): void
    {
        $command = new SqlCommand('SELECT * FROM `t` WHERE `a` = @a ORDER BY `a`');
        $command->AddParameter(new Parameter('@a', 'v'));
        $sorted = new SortCommand($command, 'name', 'desc');

        $adapter = new MySqlCommandAdapter($sorted);

        $this->assertSame('SELECT * FROM `t` WHERE `a` = ? ORDER BY name desc', $adapter->GetQuery());
        $this->assertSame(['v'], $adapter->GetValues());
    }

    public function testTheLastParameterWinsWhenANameIsAddedTwice(): void
    {
        $command = $this->command('SELECT @a', ['@a' => 'first']);
        $command->AddParameter(new Parameter('@a', 'second'));

        $adapter = new MySqlCommandAdapter($command);

        $this->assertSame(['second'], $adapter->GetValues());
    }

    public function testACommandWithoutParametersIsLeftAlone(): void
    {
        $adapter = new MySqlCommandAdapter(new AdHocCommand("SELECT 'a;b', '@x' FROM `t`"));

        $this->assertSame([['query' => "SELECT 'a;b', '@x' FROM `t`", 'values' => []]], $adapter->GetStatements());
    }

    public function testAMultiQueryCommandIsSplitIntoOneStatementPerQuery(): void
    {
        $command = new AdjustUserCreditsCommand(userId: 9, creditsToDeduct: 2, note: 'why; not');

        $statements = (new MySqlCommandAdapter($command))->GetStatements();

        $this->assertCount(2, $statements);
        $this->assertStringStartsWith('INSERT INTO `credit_log`', $statements[0]['query']);
        $this->assertStringStartsWith('UPDATE `users`', $statements[1]['query']);
        $this->assertStringNotContainsString('@', $statements[0]['query']);
        $this->assertStringNotContainsString('@', $statements[1]['query']);
        // credit_count, credit_note, date_created, user_id
        $this->assertCount(4, $statements[0]['values']);
        $this->assertSame('why; not', $statements[0]['values'][1]);
        $this->assertSame(['2', '9'], $statements[1]['values']);
    }

    public function testSplittingIgnoresSemicolonsInsideQuotes(): void
    {
        $command = new class ("SELECT 'a;b', \"c;d\", `e;f` FROM `t`; SELECT 'it\\'s; fine'") extends SqlCommand {
            public function IsMultiQuery()
            {
                return true;
            }
        };

        $statements = (new MySqlCommandAdapter($command))->GetStatements();

        $this->assertSame(
            ["SELECT 'a;b', \"c;d\", `e;f` FROM `t`", "SELECT 'it\\'s; fine'"],
            array_column($statements, 'query')
        );
    }

    /**
     * @param array<string, mixed> $parameters
     */
    private function command(string $sql, array $parameters): SqlCommand
    {
        $command = new SqlCommand($sql);
        foreach ($parameters as $name => $value) {
            $command->AddParameter(new Parameter($name, $value));
        }

        return $command;
    }
}
