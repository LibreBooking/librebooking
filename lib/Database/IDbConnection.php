<?php

interface IDbConnection
{
    public function Connect();
    public function Disconnect();

    /**
     * Queries the database and returns an IReader
     *
     * @param ISqlCommand $command
     * @return IReader to iterate over
     */
    public function Query(ISqlCommand $command);

    /**
     * @param ISqlCommand $command
     * @param int $limit
     * @param int $offset
     * @return IReader to iterate over
     */
    public function LimitQuery(ISqlCommand $command, $limit, $offset = null);

    /**
     * Executes an alter query against the database
     *
     * @param ISqlCommand $command
     * @return void
     */
    public function Execute(ISqlCommand $command);

    /**
     * Executes a single-statement alter query and returns the number of rows it affected.
     * For UPDATE, MySQL counts only rows whose values actually changed.
     *
     * @param ISqlCommand $command must not be a multi-query command
     * @return int number of rows affected by the statement
     * @throws InvalidArgumentException if the command is a multi-query command
     */
    public function ExecuteAffectedRows(ISqlCommand $command): int;

    /**
     * @return int last auto-increment id inserted for this connection
     */
    public function GetLastInsertId();
}
