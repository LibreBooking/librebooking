<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageUsersPage.php');

use PHPUnit\Framework\Attributes\DataProvider;

class ManageUsersPageTest extends TestBase
{
    private array $originalPost = [];
    private array $originalGet = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->originalPost = $_POST;
        $this->originalGet = $_GET;
    }

    public function tearDown(): void
    {
        $_POST = $this->originalPost;
        $_GET = $this->originalGet;
        parent::tearDown();
    }

    #[DataProvider('specialCharacterPasswords')]
    public function testPreservesSpecialCharactersInPassword(string $password): void
    {
        $_POST[FormKeys::PASSWORD] = $password;

        $page = $this->createPageWithoutConstructor();

        $this->assertSame($password, $page->GetPassword());
    }

    public function testReturnsEmptyStringWhenPasswordIsMissing(): void
    {
        unset($_POST[FormKeys::PASSWORD]);

        $page = $this->createPageWithoutConstructor();

        $this->assertSame('', $page->GetPassword());
    }

    public function testReturnsEmptyStringWhenPasswordIsArray(): void
    {
        $_POST[FormKeys::PASSWORD] = ['not-a-string'];

        $page = $this->createPageWithoutConstructor();

        $this->assertSame('', $page->GetPassword());
    }

    public function testUserIdUsesFormWhenQuerystringIsMissing(): void
    {
        unset($_GET[QueryStringKeys::USER_ID]);
        $_POST[FormKeys::PK] = '42';

        $this->assertSame(42, $this->createPageWithoutConstructor()->GetUserId());
    }

    public function testUserIdPrefersQuerystringOverForm(): void
    {
        $_GET[QueryStringKeys::USER_ID] = '7';
        $_POST[FormKeys::PK] = '42';

        $this->assertSame(7, $this->createPageWithoutConstructor()->GetUserId());
    }

    public function testUserIdIsNullWhenNotSubmitted(): void
    {
        unset($_GET[QueryStringKeys::USER_ID], $_POST[FormKeys::PK]);

        $this->assertNull($this->createPageWithoutConstructor()->GetUserId());
    }

    public function testUserGroupIsParsedAsInteger(): void
    {
        $_POST[FormKeys::GROUP_ID] = '3';

        $this->assertSame(3, $this->createPageWithoutConstructor()->GetUserGroup());
    }

    public function testDeletedUserIdsWrapsASingleId(): void
    {
        $_POST[FormKeys::USER_ID] = '5';

        $this->assertSame([5], $this->createPageWithoutConstructor()->GetDeletedUserIds());
    }

    public function testDeletedUserIdsParsesSubmittedList(): void
    {
        $_POST[FormKeys::USER_ID] = ['5', 'abc', '6'];

        $this->assertSame([5, 6], $this->createPageWithoutConstructor()->GetDeletedUserIds());
    }

    public function testDeletedUserIdsIsEmptyWhenNothingIsSubmitted(): void
    {
        unset($_POST[FormKeys::USER_ID]);

        $this->assertSame([], $this->createPageWithoutConstructor()->GetDeletedUserIds());
    }

    /**
     * @return array<string, array{0:string}>
     */
    public static function specialCharacterPasswords(): array
    {
        return [
            'ampersand at beginning' => ['&secret123'],
            'less-than in middle' => ['secret<123'],
            'double-quote at end' => ['secret123"'],
        ];
    }

    private function createPageWithoutConstructor(): ManageUsersPage
    {
        $reflection = new ReflectionClass(ManageUsersPage::class);
        /** @var ManageUsersPage $page */
        $page = $reflection->newInstanceWithoutConstructor();

        $serverProperty = new ReflectionProperty(Page::class, 'server');
        $serverProperty->setValue($page, new Server());

        return $page;
    }
}
