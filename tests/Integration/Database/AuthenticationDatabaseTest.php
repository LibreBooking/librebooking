<?php

declare(strict_types=1);

require_once(__DIR__ . '/DatabaseTestCase.php');
require_once(ROOT_DIR . 'lib/Application/Authentication/namespace.php');
require_once(ROOT_DIR . 'Domain/Access/namespace.php');

/**
 * Runs Authentication::Login() against a real database. Each test creates its
 * own user and changes that user's row between the moment the login loads the
 * user and the moment it saves, the way a second request would.
 */
class AuthenticationDatabaseTest extends DatabaseTestCase
{
    private const ORIGINAL_PASSWORD = 'original-password-hash';
    private const ORIGINAL_SALT = 'original-salt';
    private const ORIGINAL_LANGUAGE = 'en_us';
    private const LOGIN_LANGUAGE = 'de_de';
    private const CHANGED_LANGUAGE = 'fr_fr';
    private const ORIGINAL_LAST_MODIFIED = '2020-01-01 00:00:00';

    private Database $database;
    private ?int $userId = null;
    private string $username;

    public function setUp(): void
    {
        parent::setUp();
        $this->requireLoadedSchema();

        $this->database = $this->realDatabase();
        ServiceLocator::SetDatabase($this->database);

        $this->username = 'login-test-' . bin2hex(random_bytes(8));
        $this->userId = (int)$this->database->ExecuteInsert($this->command(
            'INSERT INTO `users`
                (`fname`, `lname`, `username`, `email`, `password`, `salt`, `timezone`, `language`, `homepageid`, `date_created`, `last_modified`, `status_id`)
            VALUES
                (@fname, @lname, @username, @email, @password, @salt, @timezone, @language, @homepageid, @dateCreated, @lastModified, @statusid)',
            [
                '@fname' => 'Login',
                '@lname' => 'Test',
                '@username' => $this->username,
                '@email' => $this->username . '@example.com',
                '@password' => self::ORIGINAL_PASSWORD,
                '@salt' => self::ORIGINAL_SALT,
                '@timezone' => 'UTC',
                '@language' => self::ORIGINAL_LANGUAGE,
                '@homepageid' => Pages::DEFAULT_HOMEPAGE_ID,
                '@dateCreated' => Date::Now()->ToDatabase(),
                '@lastModified' => self::ORIGINAL_LAST_MODIFIED,
                '@statusid' => AccountStatus::ACTIVE,
            ]
        ));
    }

    public function tearDown(): void
    {
        if ($this->userId !== null) {
            $this->database->Execute($this->command('DELETE FROM `users` WHERE `user_id` = @userid', ['@userid' => $this->userId]));
        }
        ServiceLocator::SetDatabase(new FakeDatabase());

        parent::tearDown();
    }

    public function testLoginStoresLoginTimeLanguageAndPublicId(): void
    {
        $session = $this->login();

        $row = $this->userRow();
        $this->assertTrue($session->IsLoggedIn());
        $this->assertSame($session->LoginTime, $row['lastlogin']);
        $this->assertSame(self::LOGIN_LANGUAGE, $row['language']);
        $this->assertNotEmpty($row['public_id']);
        $this->assertSame($session->PublicId, $row['public_id']);
    }

    public function testLoginUpdatesLastModified(): void
    {
        $this->assertSame(self::ORIGINAL_LAST_MODIFIED, $this->userRow()['last_modified']);

        $this->login();

        // TestBase freezes Date::Now(), so the stored value is known exactly.
        $this->assertSame(Date::Now()->ToDatabase(), $this->userRow()['last_modified']);
    }

    public function testLoginReplacesAnEmptyPublicId(): void
    {
        $this->changeUser('`public_id` = @value', '');

        $session = $this->login();

        $row = $this->userRow();
        $this->assertNotEmpty($row['public_id']);
        $this->assertSame($session->PublicId, $row['public_id']);
    }

    public function testLoginKeepsAnExistingPublicId(): void
    {
        $this->changeUser('`public_id` = @value', 'existing-public-id');

        $this->login();

        $this->assertSame('existing-public-id', $this->userRow()['public_id']);
    }

    public function testLoginDoesNotReplaceAPublicIdStoredDuringTheLogin(): void
    {
        $this->login(function (): void {
            $this->changeUser('`public_id` = @value', 'concurrent-public-id');
        });

        $this->assertSame('concurrent-public-id', $this->userRow()['public_id']);
    }

    public function testLoginDoesNotRevertAPasswordChangedDuringTheLogin(): void
    {
        $this->login(function (): void {
            $this->changeUser('`password` = @value, `salt` = \'new-salt\'', 'new-password-hash');
        });

        $row = $this->userRow();
        $this->assertSame('new-password-hash', $row['password']);
        $this->assertSame('new-salt', $row['salt']);
    }

    public function testLoginDoesNotReactivateAUserDeactivatedDuringTheLogin(): void
    {
        $this->login(function (): void {
            $this->changeUser('`status_id` = @value', AccountStatus::INACTIVE);
        });

        $this->assertSame(AccountStatus::INACTIVE, (int)$this->userRow()['status_id']);
    }

    public function testLoginDoesNotRevertCreditsChangedDuringTheLogin(): void
    {
        $this->login(function (): void {
            $this->changeUser('`credit_count` = @value', '12.50');
        });

        $this->assertSame('12.50', $this->userRow()['credit_count']);
    }

    public function testLoginWithoutALanguageKeepsTheStoredLanguage(): void
    {
        $session = $this->login(language: null);

        $this->assertSame(self::ORIGINAL_LANGUAGE, $session->LanguageCode);
        $this->assertSame(self::ORIGINAL_LANGUAGE, $this->userRow()['language']);
    }

    public function testLoginWithoutALanguageDoesNotRevertALanguageChangedDuringTheLogin(): void
    {
        $this->login(
            duringLogin: function (): void {
                $this->changeUser('`language` = @value', self::CHANGED_LANGUAGE);
            },
            language: null
        );

        $this->assertSame(self::CHANGED_LANGUAGE, $this->userRow()['language']);
    }

    public function testLoginDoesNotRemoveAPreferenceAddedDuringTheLogin(): void
    {
        $this->login(function (): void {
            $this->database->Execute($this->command(
                'INSERT INTO `user_preferences` (`user_id`, `name`, `value`) VALUES (@userid, @name, @value)',
                ['@userid' => $this->userId, '@name' => 'added-during-login', '@value' => 'kept']
            ));
        });

        $reader = $this->database->Query($this->command(
            'SELECT `value` FROM `user_preferences` WHERE `user_id` = @userid AND `name` = @name',
            ['@userid' => $this->userId, '@name' => 'added-during-login']
        ));
        $row = $reader->GetRow();
        $this->assertIsArray($row, 'The preference added during the login should still exist');
        $this->assertSame('kept', $row['value']);
    }

    /**
     * Logs the test user in. $duringLogin runs after the login has loaded the
     * user and before it saves, simulating a change made by another request.
     * A null $language is a login that does not choose a language.
     */
    private function login(?Closure $duringLogin = null, ?string $language = self::LOGIN_LANGUAGE): UserSession
    {
        $userRepository = new class ($duringLogin) extends UserRepository {
            public function __construct(private readonly ?Closure $afterLoad)
            {
                parent::__construct();
            }

            public function LoadByUsername($userName)
            {
                $user = parent::LoadByUsername($userName);
                if ($this->afterLoad !== null) {
                    ($this->afterLoad)();
                }

                return $user;
            }
        };

        $authentication = new Authentication(
            roleService: new FakeAuthorizationService(),
            userRepository: $userRepository,
            groupRepository: new GroupRepository()
        );
        // The default strategy rewrites config/config.php when there is only one user.
        $authentication->SetFirstRegistrationStrategy(new class () implements IFirstRegistrationStrategy {
            public function HandleLogin(User $user, IUserRepository $userRepository, IGroupRepository $groupRepository)
            {
                return $user;
            }
        });

        return $authentication->Login(
            $this->username,
            new WebLoginContext(new LoginData(false, $language))
        );
    }

    private function changeUser(string $assignment, int|string $value): void
    {
        $this->database->Execute($this->command(
            'UPDATE `users` SET ' . $assignment . ' WHERE `user_id` = @userid',
            ['@value' => $value, '@userid' => $this->userId]
        ));
    }

    /**
     * @return array<string, mixed>
     */
    private function userRow(): array
    {
        $row = $this->database->Query($this->command('SELECT * FROM `users` WHERE `user_id` = @userid', ['@userid' => $this->userId]))->GetRow();
        $this->assertIsArray($row, 'The test user should exist');

        return $row;
    }

    /**
     * @param array<string, int|string|null> $parameters
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
