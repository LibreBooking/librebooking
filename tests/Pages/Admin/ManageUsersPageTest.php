<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Admin/ManageUsersPage.php');

use PHPUnit\Framework\Attributes\DataProvider;

class ManageUsersPageTest extends TestBase
{
    private array $originalPost = [];

    public function setUp(): void
    {
        parent::setUp();
        $this->originalPost = $_POST;
    }

    public function tearDown(): void
    {
        $_POST = $this->originalPost;
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
