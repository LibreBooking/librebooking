<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/ResourceDisplayPage.php');

use PHPUnit\Framework\Attributes\DataProvider;

class ResourceDisplayPageTest extends TestBase
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
            'greater-than in middle' => ['secret>123'],
            'single-quote at end' => ["secret123'"],
        ];
    }

    private function createPageWithoutConstructor(): ResourceDisplayPage
    {
        $reflection = new ReflectionClass(ResourceDisplayPage::class);
        /** @var ResourceDisplayPage $page */
        $page = $reflection->newInstanceWithoutConstructor();

        $serverProperty = new ReflectionProperty(Page::class, 'server');
        $serverProperty->setValue($page, new Server());

        return $page;
    }
}
