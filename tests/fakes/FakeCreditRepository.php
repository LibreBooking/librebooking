<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/CreditRepository.php');

class FakeCreditRepository implements ICreditRepository
{
    /**
     * @var PageableData
     */
    public $_UserCredits;
    public $_LastPage;
    public $_LastPageSize;
    public $_LastUserId;

    public function GetList($pageNumber, $pageSize, $userId = -1, $sortField = null, $sortDirection = null, $filter = null)
    {
        $this->_LastPage = $pageNumber;
        $this->_LastPageSize = $pageSize;
        $this->_LastUserId = $userId;

        return $this->_UserCredits;
    }
}
