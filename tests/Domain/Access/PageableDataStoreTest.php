<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/namespace.php');

use PHPUnit\Framework\Attributes\DataProvider;

class PageableDataStoreTest extends TestBase
{
    #[DataProvider('pagingValues')]
    public function testLimitAndOffsetAreAlwaysValid(int $pageNumber, int $pageSize, int $expectedLimit, int $expectedOffset): void
    {
        $this->db->SetRow(0, [[ColumnNames::TOTAL => 50]]);
        $this->db->SetRow(1, []);

        PageableDataStore::GetList(new AdHocCommand('SELECT 1'), fn ($row) => $row, $pageNumber, $pageSize);

        $this->assertSame($expectedLimit, $this->db->_Limit);
        $this->assertSame($expectedOffset, $this->db->_Offset);
    }

    /**
     * @return array<string, array{0:int, 1:int, 2:int, 3:int}>
     */
    public static function pagingValues(): array
    {
        return [
            'valid page and size' => [3, 20, 20, 40],
            'missing page' => [0, 20, 20, 0],
            'negative page' => [-3, 20, 20, 0],
            'negative size' => [2, -5, 1, 1],
            'negative page and size' => [-2, -5, 1, 0],
            'huge page' => [PHP_INT_MAX, 20, 20, (intdiv(PHP_INT_MAX, 20) - 1) * 20],
            'huge page and size' => [3, PHP_INT_MAX, PHP_INT_MAX, 0],
        ];
    }
}
