<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');

class PasswordEncryptionTest extends TestBase
{
    public function testGeneratesSaltAndHashesPassword()
    {
        $password = 'mypassword';

        $encryption = new PasswordEncryption();
        $salt = $encryption->Salt();
        $actualEncryptedPassword = $encryption->Encrypt($password, $salt);

        $expectedEncryptedPassword = sha1($password . $salt);

        $this->assertEquals($expectedEncryptedPassword, $actualEncryptedPassword, 'Password was not encrypted correctly');
    }
}
