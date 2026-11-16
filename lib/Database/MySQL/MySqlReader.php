<?php

class MySqlReader implements IReader
{
    private $_result = null;

    public function __construct($result)
    {
        $this->_result = $result;
    }

    public function GetRow()
    {
        $row = mysqli_fetch_assoc($this->_result);

        // Prepared statements return native PHP types for numeric columns.
        // Callers expect every non-null column as a string, as mysqli_query() returned them.
        if (is_array($row)) {
            foreach ($row as $column => $value) {
                if ($value !== null && !is_string($value)) {
                    $row[$column] = (string) $value;
                }
            }
        }

        return $row;
    }

    public function NumRows()
    {
        return mysqli_num_rows($this->_result);
    }

    public function Free()
    {
        mysqli_free_result($this->_result);
    }
}
