<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Pages/IPageable.php');

use PHPUnit\Framework\Attributes\DataProvider;

class PageablePageTest extends TestBase
{
    private const DEFAULT_PAGE_SIZE = 25;

    private PageablePage $pageable;

    public function setUp(): void
    {
        parent::setUp();

        $this->fakeConfig->SetKey(ConfigKeys::DEFAULT_PAGE_SIZE, (string) self::DEFAULT_PAGE_SIZE);
        $this->pageable = new PageablePage(new class () extends Page {
            public function __construct()
            {
                parent::__construct();
            }

            public function PageLoad()
            {
            }
        });
    }

    #[DataProvider('pageNumbers')]
    public function testGetPageNumber(?string $page, int $expected): void
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::PAGE, $page);

        $this->assertSame($expected, $this->pageable->GetPageNumber());
    }

    /**
     * @return array<string, array{0:?string, 1:int}>
     */
    public static function pageNumbers(): array
    {
        return [
            'missing' => [null, 0],
            'valid' => ['3', 3],
            'negative' => ['-3', 0],
            'not a number' => ['abc', 0],
        ];
    }

    #[DataProvider('pageSizes')]
    public function testGetPageSize(?string $pageSize, int $expected): void
    {
        $this->fakeServer->SetQuerystring(QueryStringKeys::PAGE_SIZE, $pageSize);

        $this->assertSame($expected, $this->pageable->GetPageSize());
    }

    /**
     * @return array<string, array{0:?string, 1:int}>
     */
    public static function pageSizes(): array
    {
        return [
            'missing' => [null, self::DEFAULT_PAGE_SIZE],
            'valid' => ['50', 50],
            'view all' => ['-1', PageInfo::All],
            'zero' => ['0', self::DEFAULT_PAGE_SIZE],
            'negative' => ['-5', self::DEFAULT_PAGE_SIZE],
            'not a number' => ['abc', self::DEFAULT_PAGE_SIZE],
        ];
    }
}
