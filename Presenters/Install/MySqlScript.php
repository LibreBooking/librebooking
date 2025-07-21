<?php

/**
 * Producing mySql query string
 */
class MySqlScript
{
    /**
     * @var string
     */
    private $path;

    /**
     * @var array|string[]
     */
    private $tokens = [];

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Return the mySql script files directory path
     * $param null
     * @return string path to directory where mySql script are located
     */
    public function Name()
    {
        return $this->path;
    }

    /**
     * Replace the default string values
     * @param string $search databasename, username, etc ...
     * @param string $replace configured databasename, configured username, etc ...
     */
    public function Replace($search, $replace)
    {
        $this->tokens[$search] = $replace;
    }

    /**
     * Contruct and return mySql statement string
     * @return string $sql query string
     */
    public function GetFullSql()
    {
        $f = fopen($this->path, "r");
        $sql = fread($f, filesize($this->path));
        fclose($f);

        foreach ($this->tokens as $search => $replace) {
            $sql = str_replace($search, $replace, $sql);
        }

        return $sql;
    }
}
