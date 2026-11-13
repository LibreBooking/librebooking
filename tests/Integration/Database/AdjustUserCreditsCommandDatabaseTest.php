<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

/**
 * Runs AdjustUserCreditsCommand, the only multi-statement command, against the
 * real schema: both its statements must apply as one unit of work.
 */
class AdjustUserCreditsCommandDatabaseTest extends DatabaseTestCase
{
    private const ORIGINAL_CREDITS = '10.00';

    private Database $database;
    private ?int $userId = null;

    public function setUp(): void
    {
        parent::setUp();
        $this->requireLoadedSchema();

        $this->database = $this->realDatabase();

        $username = 'credits-test-' . bin2hex(random_bytes(8));
        $this->userId = (int)$this->database->ExecuteInsert($this->command(
            'INSERT INTO `users`
                (`fname`, `lname`, `username`, `email`, `password`, `salt`, `timezone`, `language`, `homepageid`, `date_created`, `status_id`, `credit_count`)
            VALUES
                (@fname, @lname, @username, @email, @password, @salt, @timezone, @language, @homepageid, @dateCreated, @statusid, @credits)',
            [
                '@fname' => 'Credits',
                '@lname' => 'Test',
                '@username' => $username,
                '@email' => $username . '@example.com',
                '@password' => 'password-hash',
                '@salt' => 'salt',
                '@timezone' => 'UTC',
                '@language' => 'en_us',
                '@homepageid' => Pages::DEFAULT_HOMEPAGE_ID,
                '@dateCreated' => Date::Now()->ToDatabase(),
                '@statusid' => AccountStatus::ACTIVE,
                '@credits' => self::ORIGINAL_CREDITS,
            ]
        ));
    }

    public function tearDown(): void
    {
        if ($this->userId !== null) {
            $this->database->Execute($this->command('DELETE FROM `credit_log` WHERE `user_id` = @userid', ['@userid' => $this->userId]));
            $this->database->Execute($this->command('DELETE FROM `users` WHERE `user_id` = @userid', ['@userid' => $this->userId]));
        }

        parent::tearDown();
    }

    public function testDeductsCreditsAndLogsTheChange(): void
    {
        $this->database->Execute(new AdjustUserCreditsCommand(userId: $this->userId, creditsToDeduct: 2.5, note: "it's; a note"));

        $user = $this->database->Query($this->command('SELECT `credit_count` FROM `users` WHERE `user_id` = @userid', ['@userid' => $this->userId]))->GetRow();
        $this->assertSame('7.50', $user['credit_count']);

        $log = $this->database->Query($this->command(
            'SELECT `original_credit_count`, `credit_count`, `credit_note` FROM `credit_log` WHERE `user_id` = @userid',
            ['@userid' => $this->userId]
        ))->GetRow();
        $this->assertSame(
            ['original_credit_count' => self::ORIGINAL_CREDITS, 'credit_count' => '7.50', 'credit_note' => "it's; a note"],
            $log
        );
    }

    /**
     * @param array<string, int|string> $parameters
     */
    private function command(string $sql, array $parameters): SqlCommand
    {
        $command = new SqlCommand($sql);
        foreach ($parameters as $name => $value) {
            $command->AddParameter(new Parameter($name, $value));
        }

        return $command;
    }
}
