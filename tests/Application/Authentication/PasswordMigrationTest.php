<?php

require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'lib/Common/namespace.php');

class PasswordMigrationTest extends TestBase
{
    /**
     * @var FakeDatabase
     */
    private $_db;
    private $plaintext = 'password';
    private $newEncryption;
    private $oldEncryption;

    public function setup(): void
    {
        $this->_db = new FakeDatabase();
        ServiceLocator::SetDatabase($this->_db);

        $this->newEncryption = new PasswordEncryption();
        $this->oldEncryption = new RetiredPasswordEncryption();
    }

    public function teardown(): void
    {
        parent::teardown();
    }

    public function testPasswordValidatesWithNewValidationAndDoesNotMigrate()
    {
        $userid = 1;
        $oldpassword = '';
        $salt = $this->newEncryption->Salt();
        $newpassword = $this->newEncryption->Encrypt($this->plaintext, $salt);

        $migration = new PasswordMigration();
        $password = $migration->Create($this->plaintext, $oldpassword, $newpassword);

        $isValid = $password->Validate($salt);

        $this->assertTrue($isValid, 'should have validated against the new password');

        $password->Migrate($userid);
        $this->assertEquals(0, count($this->_db->_Commands));
    }

    public function testOldPasswordValidatesWithOldValidatorAndMigrates()
    {
        $userid = 1;
        $fakeEncryption = new FakePasswordEncryption();
        $salt = $fakeEncryption->Salt();

        $oldpassword = $this->oldEncryption->Encrypt($this->plaintext);
        $newpassword = '';

        $migration = new PasswordMigration();
        $password = $migration->Create($this->plaintext, $oldpassword, $newpassword);
        $this->assertInstanceOf(Password::class, $password);
        $password->Encryption = $fakeEncryption;

        $isValid = $password->Validate('');

        $this->assertTrue($isValid, 'should have validated against the old password');

        $password->Migrate($userid);
        $encrypted = $fakeEncryption->Encrypt($this->plaintext, $salt);
        $this->assertTrue($this->_db->ContainsCommand(new MigratePasswordCommand($userid, $encrypted, $salt)), 'did not migrate the password');
    }
}
