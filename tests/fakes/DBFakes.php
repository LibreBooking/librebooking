<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/namespace.php');
//require_once(ROOT_DIR . 'lib/pear/MDB2.php');

class FakeDatabase extends Database
{
    public $reader = [];
    public $_LastCommand;
    public $_Commands = [];
    public $_ExpectedInsertId = 0;
    public $_ExpectedInsertIds = [];
    public $_ExpectedAffectedRows = 0;
    public $_ExpectedAffectedRowsList = [];
    public $_Limit = 0;
    public $_Offset = 0;
    private $readcount;
    private $executeInsertCount;
    private $executeAffectedRowsCount;

    public function __construct()
    {
        $this->readcount = 0;
        $this->executeInsertCount = 0;
        $this->executeAffectedRowsCount = 0;

        $this->reader[0] = new FakeReader([]);
    }

    public function SetReader($readerCount, IReader $reader)
    {
        $this->reader[$readerCount] = $reader;
    }

    public function Query(ISqlCommand $command)
    {
        $this->_LastCommand = $command;
        $this->_AddCommand($command);

        if (!isset($this->reader[$this->readcount])) {
            $reader = new FakeReader([]);
        } else {
            $reader = $this->reader[$this->readcount];
        }

        $this->readcount++;
        return $reader;
    }

    public function LimitQuery(ISqlCommand $command, $limit, $offset = null)
    {
        $this->_Limit = $limit;
        $this->_Offset = $offset;

        return $this->Query($command);
    }

    public function Execute(ISqlCommand $command)
    {
        $this->_LastCommand = $command;
        $this->_AddCommand($command);
    }

    public function ExecuteInsert(ISqlCommand $command)
    {
        $this->_LastCommand = $command;
        $this->_AddCommand($command);

        $expectedId =  $this->_ExpectedInsertId;
        if (isset($this->_ExpectedInsertIds[$this->executeInsertCount])) {
            $expectedId = $this->_ExpectedInsertIds[$this->executeInsertCount];
        }

        $this->executeInsertCount++;
        return $expectedId;
    }

    public function ExecuteAffectedRows(ISqlCommand $command): int
    {
        $this->_LastCommand = $command;
        $this->_AddCommand($command);

        $affectedRows = $this->_ExpectedAffectedRows;
        if (isset($this->_ExpectedAffectedRowsList[$this->executeAffectedRowsCount])) {
            $affectedRows = $this->_ExpectedAffectedRowsList[$this->executeAffectedRowsCount];
        }

        $this->executeAffectedRowsCount++;
        return $affectedRows;
    }

    private function _AddCommand($command)
    {
        array_push($this->_Commands, $command);
    }

    /**
     * Set this to array(array())
     * @param $rows
     */
    public function SetRows($rows)
    {
        $this->SetRow(0, $rows);
    }

    public function SetRow($readerCount, $rows)
    {
        $reader = new FakeReader($rows);
        $this->SetReader($readerCount, $reader);
    }

    public function GetReader($readerCount)
    {
        return $this->reader[$readerCount];
    }

    public function GetCommandsOfType($type)
    {
        $commands = [];

        foreach ($this->_Commands as $command) {
            if (get_class($command) == $type) {
                $commands[] = $command;
            }
        }

        return $commands;
    }

    /**
     * @param ISqlCommand $command
     * @return bool
     */
    public function ContainsCommand($command)
    {
        return in_array($command, $this->_Commands);
    }
}

class FakeReader implements IReader
{
    public $rows = [];
    public $idx = 0;
    public $_FreeCalled = false;

    public function __construct($rows)
    {
        $this->rows = $rows;
    }

    public function GetRow()
    {
        if (sizeof($this->rows) > $this->idx) {
            return $this->rows[$this->idx++];
        }
        return [];
    }

    public function NumRows()
    {
        return sizeof($this->rows);
    }

    public function Free()
    {
        $this->_FreeCalled = true;
    }
}

class FakeDBConnection implements IDbConnection
{
    public $_LastQueryCommand = null;
    public $_LastExecuteCommand = null;
    public $_LastSqlCommand = null;
    public $_ConnectWasCalled = false;
    public $_DisconnectWasCalled = false;
    public $_GetLastInsertIdCalled = false;
    public $_ExpectedInsertId = 0;
    public $_LimitQueryCalled = false;
    public $_LastExecuteAffectedRowsCommand = null;
    public $_ExpectedAffectedRows = 0;
    public ?Throwable $_ExecuteAffectedRowsException = null;

    public function __construct()
    {
    }

    public function Connect()
    {
        $this->_ConnectWasCalled = true;
    }

    public function Disconnect()
    {
        $this->_DisconnectWasCalled = true;
    }

    public function Query(ISqlCommand $command)
    {
        $this->_LastSqlCommand = $command;
        return new FakeReader([]);
    }

    public function Execute(ISqlCommand $command)
    {
        $this->_LastExecuteCommand = $command;
    }

    public function ExecuteAffectedRows(ISqlCommand $command): int
    {
        $this->_LastExecuteAffectedRowsCommand = $command;

        if ($this->_ExecuteAffectedRowsException !== null) {
            throw $this->_ExecuteAffectedRowsException;
        }

        return $this->_ExpectedAffectedRows;
    }

    public function GetLastInsertId()
    {
        $this->_GetLastInsertIdCalled = true;
        return $this->_ExpectedInsertId;
    }

    /**
     * @param ISqlCommand $command
     * @param int $limit
     * @param int $offset
     * @return IReader to iterate over
     */
    public function LimitQuery(ISqlCommand $command, $limit, $offset = null)
    {
        $this->_LimitQueryCalled = true;
        return new FakeReader([]);
    }
}

/*
class FakePearDB extends MDB2
{
    public $dsn = '';
    public $permcn = false;
    public $result = null;

    public $PrepareHandle = null;

    public $_PrepareWasCalled = false;
    public $_LastPreparedQuery = '';
    public $_PrepareAutoDetect = false;
    public $_PrepareType = -1;

    public $_LastInsertId = 10;
    public $_LastInsertIDCalled = false;

    public function __construct(&$results) {
        $this->result = $results;
    }

    public function connect($dsn, $permcn) {
        $this->dsn = $dsn;
        $this->permcn = $permcn;
    }

    public function &prepare($query, $autodetect, $prepareType) {
        $this->_LastPreparedQuery = $query;
        $this->_PrepareWasCalled = true;
        $this->_PrepareAutoDetect = $autodetect;
        $this->_PrepareType = $prepareType;
        return $this->PrepareHandle;
    }

    public function lastInsertID()
    {
        $this->_LastInsertIDCalled = true;
        return $this->_LastInsertId;
    }
}

class FakeDBResult extends MDB2_Result_Common
{
    public $rows = array();
    public $idx = 0;
    public $_FreeWasCalled = false;

    public function __construct(&$rows)
    {
        $this->rows = $rows;
    }

    public function fetchRow()
    {
        if (sizeof($this->rows) > $this->idx)
        {
            return $this->rows[$this->idx++];
        }
        return false;
    }

    public function NumRows() {
        return sizeof($this->rows);
    }

    public function Free() {
        $this->_FreeWasCalled = true;
    }
}

class FakePrepareHandle extends MDB2_Statement_Common
{
    public $result = null;
    public $_ExecuteWasCalled = false;
    public $_LastExecutedValues = array();

    public function __construct(&$result) {
        $this->result = $result;
    }

    public function &execute($values) {
        $this->_ExecuteWasCalled = true;
        $this->_LastExecutedValues = $values;
        return $this->result;
    }
}
*/
