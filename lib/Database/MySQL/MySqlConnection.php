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
        $mysqlCommand = new MySqlCommandAdapter($sqlCommand, $this->_db);

        if (Log::DebugEnabled()) {
            Log::Sql('MySql Query: ' . str_replace('%', '%%', $mysqlCommand->GetQuery()));
        }

        if ($sqlCommand->ContainsGroupConcat()) {
            mysqli_query($this->_db, 'SET SESSION group_concat_max_len = 1000000;');
        }

        mysqli_query($this->_db, "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        $result = mysqli_query($this->_db, $mysqlCommand->GetQuery());

        $this->_handleError($result);

        return new MySqlReader($result);
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
        $mysqlCommand = new MySqlCommandAdapter($sqlCommand, $this->_db);

        if (Log::DebugEnabled()) {
            Log::Sql('MySql Execute: ' . str_replace('%', '%%', $mysqlCommand->GetQuery()));
        }

        mysqli_query($this->_db, "SET SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'");

        if ($sqlCommand->IsMultiQuery()) {
            $result = mysqli_multi_query($this->_db, $mysqlCommand->GetQuery());
            do {
                if ($r = mysqli_store_result($this->_db)) {
                    mysqli_free_result($r);
                }
            } while (mysqli_next_result($this->_db));
        } else {
            $result = mysqli_query($this->_db, $mysqlCommand->GetQuery());
        }
        $this->_handleError($result);
    }

    public function GetLastInsertId()
    {
        return mysqli_insert_id($this->_db);
    }

    private function _handleError($result)
    {
        if (!$result) {
            Log::Error('Error executing MySQL query %s', mysqli_error($this->_db));

            throw new Exception('There was an error executing your query\n' .  mysqli_error($this->_db));
        }
        return false;
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
