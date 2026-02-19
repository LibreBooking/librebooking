<?php

declare(strict_types=1);

class AuthorizationCommandResults
{
    private $rows = [];

    public $UserId = 1;
    public $Password = 'password';
    public $Salt = 'salt';
    public $OldPassword = 'oldpassword';

    public static function ValidUser()
    {
        $results = new AuthorizationCommandResults();
        $results->AddDefaultUser();

        return $results;
    }

    public static function InvalidUser()
    {
        return new AuthorizationCommandResults();
    }

    private function AddDefaultUser()
    {
        $this->AddRow($this->UserId, $this->Password, $this->Salt, $this->OldPassword);
    }

    public function AddRow($userid, $password, $salt, $oldpassword)
    {
        $this->rows[] = [
            ColumnNames::USER_ID => $userid,
            ColumnNames::PASSWORD => $password,
            ColumnNames::SALT => $salt,
            ColumnNames::OLD_PASSWORD => $oldpassword];
    }

    public function Rows()
    {
        return $this->rows;
    }
}
