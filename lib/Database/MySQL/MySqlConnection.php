<?php

class MySqlConnection implements IDbConnection
{
    private $_dbUser = '';
    private $_dbPassword = '';
    private $_hostSpec = '';
    private $_dbName = '';
    private $_port = null;

    private $_db = null;
    private $_connected = false;

    /**
     * @param string $dbUser
     * @param string $dbPassword
     * @param string $hostSpec
     * @param string $dbName
     */
    public function __construct($dbUser, $dbPassword, $hostSpec, $dbName)
    {
        $this->_dbUser = $dbUser;
        $this->_dbPassword = $dbPassword;
        $this->_hostSpec = $hostSpec;
        $this->_dbName = $dbName;
    }

    public function Connect()
    {
        if ($this->_connected && !is_null($this->_db)) {
            return;
        }

        $port = null;
        if (BookedStringHelper::Contains($this->_hostSpec, ':')) {
            $parts = explode(':', $this->_hostSpec);
            $this->_hostSpec = $parts[0];
            $this->_port = intval($parts[1]);
        }

        $this->_db = mysqli_connect($this->_hostSpec, $this->_dbUser, $this->_dbPassword, $this->_dbName, $this->_port);

        if (!$this->_db) {
            $connectError = mysqli_connect_error();
            Log::Error("Error connecting to database\nCheck your database settings in the config file\n%s", $connectError);
            throw new DatabaseConnectionException("Error connecting to database\nError: " . $connectError);
        }

        $selected = mysqli_select_db($this->_db, $this->_dbName);

        if (!$selected) {
            Log::Error("Error selecting database '%s'\nCheck your database settings in the config file\n%s", $this->_dbName, mysqli_error($this->_db));
            throw new DatabaseNotFoundException("Error selecting database\nError: " . mysqli_error($this->_db));
        }
        mysqli_set_charset($this->_db, 'utf8mb4');

        $this->_connected = true;
    }

    public function Disconnect()
    {
        mysqli_close($this->_db);
        $this->_db = null;
        $this->_connected = false;
    }

    public function Query(ISqlCommand $sqlCommand)
    {
        if ($sqlCommand->IsMultiQuery()) {
            throw new InvalidArgumentException(
                sprintf('MySqlConnection::Query does not support multi-query commands, got %s', get_debug_type($sqlCommand))
            );
        }

        $mysqlCommand = $this->_prepareExecute(sqlCommand: $sqlCommand, logPrefix: 'MySql Query: ');

        if ($sqlCommand->ContainsGroupConcat()) {
            mysqli_query($this->_db, 'SET SESSION group_concat_max_len = 1000000;');
        }

        $statement = $mysqlCommand->GetStatements()[0];
        $result = $this->executeStatement(query: $statement['query'], values: $statement['values']);

        return new MySqlReader($result === true ? null : $result);
    }

    public function LimitQuery(ISqlCommand $command, $limit, $offset = 0)
    {
        if (!$command instanceof SqlCommand) {
            throw new InvalidArgumentException(
                sprintf('MySqlConnection::LimitQuery requires %s, got %s', SqlCommand::class, get_debug_type($command))
            );
        }

        return $this->Query(new MySqlLimitCommand($command, $limit, $offset));
    }

    public function Execute(ISqlCommand $sqlCommand)
    {
        $mysqlCommand = $this->_prepareExecute(sqlCommand: $sqlCommand, logPrefix: 'MySql Execute: ');

        $statements = $mysqlCommand->GetStatements();
        if (count($statements) === 1) {
            $this->executeAndFree($statements[0]);
            return;
        }

        // A multi-query command is one unit of work: all statements apply or none do.
        if (!mysqli_begin_transaction($this->_db)) {
            $this->fail(mysqli_error($this->_db));
        }
        try {
            foreach ($statements as $statement) {
                $this->executeAndFree($statement);
            }
            if (!mysqli_commit($this->_db)) {
                $this->fail(mysqli_error($this->_db));
            }
        } catch (Throwable $e) {
            mysqli_rollback($this->_db);
            throw $e;
        }
    }

    public function ExecuteAffectedRows(ISqlCommand $sqlCommand): int
    {
        if ($sqlCommand->IsMultiQuery()) {
            throw new InvalidArgumentException(
                sprintf('MySqlConnection::ExecuteAffectedRows does not support multi-query commands, got %s', get_debug_type($sqlCommand))
            );
        }

        $mysqlCommand = $this->_prepareExecute(sqlCommand: $sqlCommand, logPrefix: 'MySql ExecuteAffectedRows: ');

        return $this->executeAndFree($mysqlCommand->GetStatements()[0]);
    }

    private function _prepareExecute(ISqlCommand $sqlCommand, string $logPrefix): MySqlCommandAdapter
    {
        $mysqlCommand = new MySqlCommandAdapter($sqlCommand);

        if (Log::DebugEnabled()) {
            Log::Sql(str_replace('%', '%%', $logPrefix . $mysqlCommand->GetQuery() . ' -- ' . json_encode($mysqlCommand->GetValues())));
        }

        mysqli_query($this->_db, "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        return $mysqlCommand;
    }

    /**
     * Runs one statement, discards any result set and returns the affected row count.
     *
     * @param array{query: string, values: list<string|null>} $statement
     */
    private function executeAndFree(array $statement): int
    {
        $affectedRows = 0;
        $result = $this->executeStatement(query: $statement['query'], values: $statement['values'], affectedRows: $affectedRows);
        if ($result instanceof mysqli_result) {
            mysqli_free_result($result);
        }

        return $affectedRows;
    }

    /**
     * Prepares and executes one statement with its values bound server-side.
     *
     * @param list<string|null> $values
     * @param int $affectedRows receives the statement's affected row count
     * @return mysqli_result|true a buffered result set, or true when the statement returns none
     */
    private function executeStatement(string $query, array $values, int &$affectedRows = 0): mysqli_result|true
    {
        $statement = mysqli_prepare($this->_db, $query);
        if ($statement === false) {
            $this->fail(mysqli_error($this->_db));
        }

        try {
            if (!mysqli_stmt_execute($statement, $values === [] ? null : $values)) {
                $this->fail(mysqli_stmt_error($statement));
            }

            $affectedRows = (int) mysqli_stmt_affected_rows($statement);

            $result = mysqli_stmt_get_result($statement);
            if ($result === false && mysqli_stmt_errno($statement) !== 0) {
                $this->fail(mysqli_stmt_error($statement));
            }

            return $result === false ? true : $result;
        } finally {
            mysqli_stmt_close($statement);
        }
    }

    public function GetLastInsertId()
    {
        return mysqli_insert_id($this->_db);
    }

    private function fail(string $error): never
    {
        Log::Error('Error executing MySQL query %s', $error);

        throw new Exception('There was an error executing your query\n' . $error);
    }
}

class MySqlLimitCommand extends SqlCommand
{
    /**
     * @var SqlCommand
     */
    private $baseCommand;

    private $limit;
    private $offset;

    public function __construct(SqlCommand $baseCommand, $limit, $offset)
    {
        parent::__construct();

        $this->baseCommand = $baseCommand;
        $this->limit = $limit;
        $this->offset = $offset;
        $this->Parameters = $baseCommand->Parameters;
    }

    public function GetQuery()
    {
        return $this->baseCommand->GetQuery() . sprintf(' LIMIT %s OFFSET %s', $this->limit, $this->offset);
    }

    public function ContainsGroupConcat()
    {
        return $this->baseCommand->ContainsGroupConcat();
    }
}
