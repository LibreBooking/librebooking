<?php

declare(strict_types=1);

class FakePasswordEncryption extends PasswordEncryption
{
    public $_Salt = '123';
    public $_EncryptCalled = false;
    public $_EncryptPasswordCalled = false;
    public $_LastPassword;
    public $_LastSalt;
    public $_Encrypted = 'encryptedpw';

    public function Encrypt($password, $salt)
    {
        $this->_EncryptCalled = true;
        $this->_LastPassword = $password;
        $this->_LastSalt = $salt;

        return $this->_Encrypted;
    }

    public function EncryptPassword($password)
    {
        $this->_EncryptPasswordCalled = true;
        $this->_LastPassword = $password;

        return new EncryptedPassword($this->_Encrypted, $this->_Salt);
    }

    public function Salt()
    {
        return $this->_Salt;
    }
}
