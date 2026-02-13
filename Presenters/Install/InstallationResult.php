<?php

/**
 * Display successful/failure message after attempting the auto installation
 */
class InstallationResult
{
    public $connectionError = false;
    public $authError = false;
    public $taskName;
    public $sqlErrorCode;
    public $sqlErrorText;
    public $sqlText;

    public function __construct($taskName)
    {
        $this->taskName = $taskName;
    }

    public function SetConnectionError()
    {
        $this->connectionError = true;
        $this->sqlErrorText = 'Error connecting to mysql database.  Check your configured host and entered username and password.';
    }

    public function SetAuthenticationError()
    {
        $this->authError = true;
        $this->sqlErrorText = 'Error selecting to mysql database.  Check entered username and password.';
    }

    public function SetResult($sqlErrorCode, $sqlErrorText, $sqlStmt)
    {
        $this->sqlErrorCode = $sqlErrorCode;
        $this->sqlErrorText = $sqlErrorText;
        $this->sqlText = $sqlStmt;
    }

    public function WasSuccessful()
    {
        return !$this->connectionError && !$this->authError && $this->sqlErrorCode == 0;
    }
}

class InstallationResultSkipped extends InstallationResult
{
    public function __construct($versionNumber)
    {
        $this->taskName = "Skipping $versionNumber";
    }

    public function WasSuccessful()
    {
        true;
    }
}
