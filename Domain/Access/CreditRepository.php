<?php

require_once(ROOT_DIR . 'Domain/Values/CreditLogView.php');
require_once(ROOT_DIR . 'Domain/Access/PageableDataStore.php');

interface ICreditRepository
{
    /**
     * @param int|null $pageNumber null for no paging
     * @param int|null $pageSize null for no paging
     * @param int $userId
     * @param string $sortField
     * @param string $sortDirection
     * @param ISqlFilter $filter
     * @return PageableData|CreditLogView[]
     */
    public function GetList($pageNumber, $pageSize, $userId = -1, $sortField = null, $sortDirection = null, $filter = null);
}

class CreditRepository implements ICreditRepository
{
    public function GetList($pageNumber, $pageSize, $userId = -1, $sortField = null, $sortDirection = null, $filter = null)
    {
        $command = new GetAllCreditLogsCommand($userId);

        if ($filter != null) {
            $command = new FilterCommand($command, $filter);
        }

        $builder = ['CreditLogView', 'Populate'];
        return PageableDataStore::GetList($command, $builder, $pageNumber, $pageSize, $sortField, $sortDirection);
    }
}
