<?php

class PageableDataStore
{
    /**
     * Returns a limited query based on page number and size
     * If nulls are passed for both $pageNumber, $pageSize then all results are returned
     *
     * @param SqlCommand $command
     * @param callable $listBuilder callback to for each row of results
     * @param int|null $pageNumber
     * @param int|null $pageSize
     * @param string|null $sortField
     * @param string|null $sortDirection
     * @return PageableData
     */
    public static function GetList($command, $listBuilder, $pageNumber = null, $pageSize = null, $sortField = null, $sortDirection = null)
    {
        $total = null;
        $totalCounter = 0;
        $results = [];
        $db = ServiceLocator::GetDatabase();
        $pageNumber = intval($pageNumber);
        $pageSize = intval($pageSize);

        if (!empty($sortField)) {
            $command = new SortCommand($command, $sortField, $sortDirection);
        }

        if ((empty($pageNumber) && empty($pageSize)) || $pageSize == PageInfo::All) {
            $resultReader = $db->Query($command);
        } else {
            $totalReader = $db->Query(new CountCommand($command));
            if ($row = $totalReader->GetRow()) {
                $total = $row[ColumnNames::TOTAL];
            }

            $pageNumber = empty($pageNumber) ? 1 : $pageNumber;
            $pageSize = empty($pageSize) ? 1 : $pageSize;

            $resultReader = $db->LimitQuery($command, $pageSize, ($pageNumber - 1) * $pageSize);
        }

        while ($row = $resultReader->GetRow()) {
            $results[] = call_user_func($listBuilder, $row);
            $totalCounter++;
        }
        $resultReader->Free();

        return new PageableData($results, is_null($total) ? $totalCounter : $total, $pageNumber, $pageSize);
    }
}

class PageableData
{
    private $results = [];

    /**
     * @var PageInfo
     */
    private $pageInfo = [];

    public function __construct($results = [], $total = 0, $pageNumber = 1, $pageSize = 1)
    {
        $this->results = $results;

        $pageNumber = empty($pageNumber) ? 1 : intval($pageNumber);
        $pageSize = empty($pageSize) ? 1 : intval($pageSize);

        $this->pageInfo = PageInfo::Create($total, $pageNumber, $pageSize);
    }

    /**
     * @return array|mixed
     */
    public function Results()
    {
        return $this->results;
    }

    /**
     * @return PageInfo
     */
    public function PageInfo()
    {
        return $this->pageInfo;
    }
}

class PageInfo
{
    public const All = -1;

    public $Total = 0;
    public $TotalPages = 0;
    public $PageSize = 0;
    public $CurrentPage = 0;
    public $ResultsStart = 0;
    public $ResultsEnd = 0;

    public function __construct($totalResults, $pageNumber, $pageSize)
    {
        $this->Total = $totalResults;
        $this->CurrentPage = $pageNumber;
        $this->PageSize = $pageSize;
        $this->TotalPages = ceil($totalResults/max($pageSize, 1));
        $this->ResultsStart = ($pageNumber-1) * $pageSize + 1;
        $this->ResultsEnd = min(($pageNumber * $pageSize), $totalResults);
    }

    public static function Create($total, $pageNumber, $pageSize)
    {
        if ($pageSize == self::All) {
            return new PageInfoAll($total);
        }

        return new PageInfo($total, $pageNumber, $pageSize);
    }
}

class PageInfoAll extends PageInfo
{
    public function __construct($totalResults)
    {
        $defaultPageSize = Configuration::Instance()->GetKey(ConfigKeys::DEFAULT_PAGE_SIZE);
        parent::__construct($totalResults, 1, $defaultPageSize);
        $this->TotalPages = 1;
        $this->ResultsStart = 1;
        $this->ResultsEnd = $totalResults;
    }
}
