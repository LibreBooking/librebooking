<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/Install/InstallPage.php');

use PHPUnit\Framework\Attributes\DataProvider;

class InstallPageTest extends TestBase
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
    public function testPreservesSpecialCharactersInInstallPassword(string $password): void
    {
        $_POST[FormKeys::INSTALL_PASSWORD] = $password;

        $page = $this->createPageWithoutConstructor();

        $this->assertSame($password, $page->GetInstallPassword());
    }

    public function testReturnsEmptyStringWhenInstallPasswordIsArray(): void
    {
        $_POST[FormKeys::INSTALL_PASSWORD] = ['not-a-string'];

        $page = $this->createPageWithoutConstructor();

        $this->assertSame('', $page->GetInstallPassword());
    }

    /**
     * @return array<string, array{0:string}>
     */
    public static function specialCharacterPasswords(): array
    {
        return [
            'ampersand at beginning' => ['&secret123'],
            'greater-than in middle' => ['secret>123'],
            'double-quote in middle' => ['sec"ret123'],
            'single-quote at end' => ["secret123'"],
        ];
    }

    private function createPageWithoutConstructor(): InstallPage
    {
        $reflection = new ReflectionClass(InstallPage::class);
        /** @var InstallPage $page */
        $page = $reflection->newInstanceWithoutConstructor();

        $serverProperty = new ReflectionProperty(Page::class, 'server');
        $serverProperty->setValue($page, new Server());

        return $page;
    }
}
