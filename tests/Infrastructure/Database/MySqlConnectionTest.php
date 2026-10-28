<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');

class MySqlConnectionTest extends TestBase
{
    public function testExecuteAffectedRowsRejectsMultiQueryCommands()
    {
        $multiQueryCommand = new class ('query one; query two') extends SqlCommand {
            public function IsMultiQuery()
            {
                return true;
            }
        };

        // Never connects: the command is rejected before any database access.
        $connection = new MySqlConnection(dbUser: 'user', dbPassword: 'password', hostSpec: 'localhost', dbName: 'db');

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('does not support multi-query commands');

        $connection->ExecuteAffectedRows($multiQueryCommand);
    }
}
