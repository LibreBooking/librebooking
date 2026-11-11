<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/namespace.php');
require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');

class DatabaseTest extends TestBase
{
    public $db = null;

    public function testParameterStoresCorrectParamNameAndValue()
    {
        $name = '@paramName';
        $val = 'value';

        $parameter = new Parameter($name, $val);
        $this->assertEquals($name, $parameter->Name);
        $this->assertEquals($val, $parameter->Value);
    }

    public function testParametersAreAddedToParametersCollection()
    {
        $name1 = '@paramName1';
        $val1 = 'value1';
        $name2 = '@paramName2';
        $val2 = 'value2';

        $p1 = new Parameter($name1, $val1);
        $p2 = new Parameter($name2, $val2);

        $parameters = new Parameters();
        $parameters->Add($p1);
        $parameters->Add($p2);

        $par1 = $parameters->Items(0);
        $par2 = $parameters->Items(1);

        $this->assertEquals(2, $parameters->Count(), 'Parameters count is wrong');
        $this->assertEquals($name1, $par1->Name, 'Parameter1 name is wrong');
        $this->assertEquals($val1, $par1->Value, 'Parameter1 value is wrong');
        $this->assertEquals($name2, $par2->Name, 'Parameter2 name is wrong');
        $this->assertEquals($val2, $par2->Value, 'Parameter2 value is wrong');
    }

    public function testParametersCanBeRemovedFromParametersCollection()
    {
        $name1 = '@paramName1';
        $val1 = 'value1';
        $name2 = '@paramName2';
        $val2 = 'value2';

        $p1 = new Parameter($name1, $val1);
        $p2 = new Parameter($name2, $val2);

        $parameters = new Parameters();
        $parameters->Add($p1);
        $parameters->Add($p2);

        $par1 = $parameters->Items(0);
        $par2 = $parameters->Items(1);

        $this->assertEquals(2, $parameters->Count(), 'Parameters count is wrong before remove');

        $parameters->Remove($par1);
        $this->assertEquals(1, $parameters->Count(), 'Parameters count is wrong after remove');
        $tmp = $parameters->Items(0);
        $this->assertEquals($name2, $tmp->Name);
        $this->assertEquals($val2, $tmp->Value);
    }

    public function testParametersAreAssignedToSqlCommand()
    {
        $SqlCommand = new SqlCommand();

        $parameters = new Parameters();
        $p1 = new Parameter('n1', 'v1');
        $parameters->Add($p1);
        $p2 = new Parameter('n2', 'v2');
        $parameters->Add(parameter: $p2);

        $SqlCommand->SetParameters($parameters);

        $newParam = new Parameter('n3', 'v3');
        $SqlCommand->AddParameter($newParam);

        $this->assertNotNull($SqlCommand->Parameters, 'SqlCommand::parameters is null');
        $this->assertEquals(3, $SqlCommand->Parameters->Count());
        $this->assertEquals($parameters->Items(0), $SqlCommand->Parameters->Items(0));
        $this->assertEquals($parameters->Items(1), $SqlCommand->Parameters->Items(1));
        $this->assertNotNull($SqlCommand->Parameters->Items(2), 'Last parameter object null');
        $this->assertEquals(3, $parameters->Count());
        $this->assertEquals($newParam, $SqlCommand->Parameters->Items(2));
    }

    public function testGetParametersReturnsTheCommandParameters(): void
    {
        $command = new SqlCommand('SELECT * FROM t WHERE a = @a');
        $parameter = new Parameter('@a', 1);
        $command->AddParameter(parameter: $parameter);

        $this->assertSame($command->Parameters, $command->GetParameters());
        $this->assertSame($parameter, $command->GetParameters()->Items(0));
    }

    public function testDecoratedCommandsReturnTheParametersTheyBind(): void
    {
        $base = new SqlCommand('SELECT * FROM t WHERE a = @a');
        $base->AddParameter(parameter: new Parameter('@a', 1));

        $commands = [
            new CountCommand(baseCommand: $base),
            new SortCommand(baseCommand: $base, sortField: 'a', sortDirection: 'asc'),
            new FilterCommand(baseCommand: $base, filter: new SqlFilterEquals('b', 2)),
            new MySqlLimitCommand(baseCommand: $base, limit: 10, offset: 0),
        ];

        foreach ($commands as $command) {
            // MySqlCommandAdapter used to read ->Parameters directly.
            $this->assertSame($command->Parameters, $command->GetParameters(), get_class($command));
        }
    }

    public function testSqlCommandIsUsedInQuery()
    {
        $SqlCommand = new SqlCommand('query');
        $param = new Parameter('n1', 'v1');
        $SqlCommand->AddParameter($param);

        $cn = new FakeDBConnection();

        $db = new Database($cn);
        $db->Query($SqlCommand);

        $this->assertEquals($SqlCommand, $cn->_LastSqlCommand);
        $this->assertEquals($param, $cn->_LastSqlCommand->Parameters->Items(0));
    }

    public function testConnectAndDisconnectAreCalledForEachQuery()
    {
        $SqlCommand = new SqlCommand('query');
        $cn = new FakeDBConnection();

        $db = new Database($cn);
        $db->Query($SqlCommand);

        $this->assertTrue($cn->_ConnectWasCalled, 'Connect should be called for every query');
        $this->assertEquals($SqlCommand, $cn->_LastSqlCommand);
        $this->assertTrue($cn->_DisconnectWasCalled, 'Disonnect should be called for every query');
    }

    public function testConnectAndDisconnectAreCalledForEachExecute()
    {
        $SqlCommand = new SqlCommand('query');
        $cn = new FakeDBConnection();

        $db = new Database($cn);
        $db->Execute($SqlCommand);

        $this->assertTrue($cn->_ConnectWasCalled, 'Connect should be called for every query');
        $this->assertEquals($SqlCommand, $cn->_LastExecuteCommand);
        $this->assertTrue($cn->_DisconnectWasCalled, 'Disonnect should be called for every query');
    }

    public function testGetsIncrementedIdForExecuteInsert()
    {
        $expectedInsertId = 10;

        $SqlCommand = new SqlCommand('query');
        $cn = new FakeDBConnection();
        $cn->_ExpectedInsertId = $expectedInsertId;

        $db = new Database($cn);
        $actualInsertId = $db->ExecuteInsert($SqlCommand);

        $this->assertTrue($cn->_ConnectWasCalled, 'Connect should be called for every query');
        $this->assertEquals($SqlCommand, $cn->_LastExecuteCommand);
        $this->assertTrue($cn->_GetLastInsertIdCalled);
        $this->assertEquals($expectedInsertId, $actualInsertId);
        $this->assertTrue($cn->_DisconnectWasCalled, 'Disonnect should be called for every query');
    }

    public function testExecuteAffectedRowsReturnsConnectionCountAndConnects()
    {
        $sqlCommand = new SqlCommand('query');
        $cn = new FakeDBConnection();
        $cn->_ExpectedAffectedRows = 1;

        $db = new Database($cn);
        $affectedRows = $db->ExecuteAffectedRows($sqlCommand);

        $this->assertSame(1, $affectedRows);
        $this->assertSame($sqlCommand, $cn->_LastExecuteAffectedRowsCommand);
        $this->assertTrue($cn->_ConnectWasCalled, 'Connect should be called for every query');
        $this->assertTrue($cn->_DisconnectWasCalled, 'Disconnect should be called for every query');
    }

    public function testExecuteAffectedRowsReturnsZeroWhenNothingMatched()
    {
        $cn = new FakeDBConnection();
        $cn->_ExpectedAffectedRows = 0;

        $db = new Database($cn);

        $this->assertSame(0, $db->ExecuteAffectedRows(new SqlCommand('query')));
    }

    public function testExecuteAffectedRowsPropagatesErrorsAndDisconnects()
    {
        $exception = new Exception('query failed');
        $cn = new FakeDBConnection();
        $cn->_ExecuteAffectedRowsException = $exception;

        $db = new Database($cn);

        try {
            $db->ExecuteAffectedRows(new SqlCommand('query'));
            $this->fail('Expected the connection exception to propagate');
        } catch (Exception $caught) {
            $this->assertSame($exception, $caught);
        }

        $this->assertTrue($cn->_DisconnectWasCalled, 'Disconnect should be called even when execution fails');
    }

    public function testFakeDatabaseReturnsConfiguredAffectedRowsPerCall()
    {
        $db = new FakeDatabase();
        $db->_ExpectedAffectedRowsList = [1, 0];
        $command = new SqlCommand('query');

        $this->assertSame(1, $db->ExecuteAffectedRows($command));
        $this->assertSame(0, $db->ExecuteAffectedRows($command));
        $this->assertSame($command, $db->_LastCommand);
        $this->assertCount(2, $db->_Commands);
    }
}
