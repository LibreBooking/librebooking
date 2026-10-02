<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'lib/Database/MySQL/namespace.php');

/**
 * Base class for tests that run against a real MariaDB/MySQL database.
 *
 * Connection settings come from the same LB_TEST_DB_* environment variables as
 * tests/Integration/setup-test-database.sh. If LB_TEST_DB_USER is not set, the
 * tests are skipped, so they never touch a database by accident. The database
 * name must end in "_test".
 *
 * Tests connect directly and do not read or modify config/config.php.
 */
abstract class DatabaseTestCase extends TestBase
{
    private const DEFAULT_HOST = '127.0.0.1';
    private const DEFAULT_PORT = '3306';
    private const DEFAULT_NAME = 'librebooking_test';
    private const REQUIRED_NAME_SUFFIX = '_test';

    protected string $dbHost;
    protected string $dbPort;
    protected string $dbName;
    protected string $dbUser;
    protected string $dbPassword;

    public function setUp(): void
    {
        parent::setUp();

        $user = getenv('LB_TEST_DB_USER');
        if ($user === false || $user === '') {
            $this->markTestSkipped('Set LB_TEST_DB_USER (and LB_TEST_DB_PASSWORD) to run database integration tests.');
        }

        $this->dbHost = getenv('LB_TEST_DB_HOST') ?: self::DEFAULT_HOST;
        $this->dbPort = getenv('LB_TEST_DB_PORT') ?: self::DEFAULT_PORT;
        $this->dbName = getenv('LB_TEST_DB_NAME') ?: self::DEFAULT_NAME;
        $this->dbUser = $user;
        $password = getenv('LB_TEST_DB_PASSWORD');
        $this->dbPassword = $password === false ? '' : $password;

        if (!str_ends_with($this->dbName, self::REQUIRED_NAME_SUFFIX)) {
            $this->fail(sprintf('Refusing to use database "%s": the name must end in "%s".', $this->dbName, self::REQUIRED_NAME_SUFFIX));
        }
    }

    /**
     * A Database wired to the real test database (not the FakeDatabase from TestBase).
     */
    protected function realDatabase(): Database
    {
        $hostSpec = $this->dbPort === self::DEFAULT_PORT ? $this->dbHost : $this->dbHost . ':' . $this->dbPort;

        return new Database(new MySqlConnection(
            dbUser: $this->dbUser,
            dbPassword: $this->dbPassword,
            hostSpec: $hostSpec,
            dbName: $this->dbName
        ));
    }

    /**
     * Skips the test unless tests/Integration/setup-test-database.sh has loaded the schema.
     */
    protected function requireLoadedSchema(): void
    {
        $reader = $this->realDatabase()->Query(new AdHocCommand("SHOW TABLES LIKE 'dbversion'"));
        if ($reader->NumRows() === 0) {
            $this->markTestSkipped('Run tests/Integration/setup-test-database.sh first to load the schema.');
        }
    }
}
