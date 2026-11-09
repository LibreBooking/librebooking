<?php

declare(strict_types=1);

require_once(ROOT_DIR . 'Domain/Access/BlackoutRepository.php');

class FakeBlackoutRepository implements IBlackoutRepository
{
    /**
     * @var BlackoutSeries
     */
    public $_Added;
    /**
     * @var BlackoutSeries
     */
    public $_Updated;
    /**
     * @var int
     */
    public $_DeletedId;
    /**
     * @var int
     */
    public $_DeletedSeriesId;
    public ?BlackoutSeries $_Series = null;
    /**
     * @var int
     */
    public $_LoadedBlackoutId;

    /**
     * @inheritDoc
     */
    public function Add(BlackoutSeries $blackoutSeries)
    {
        $this->_Added = $blackoutSeries;
        return 1;
    }

    /**
     * @inheritDoc
     */
    public function Update(BlackoutSeries $blackoutSeries)
    {
        $this->_Updated = $blackoutSeries;
    }

    /**
     * @inheritDoc
     */
    public function Delete($blackoutId)
    {
        $this->_DeletedId = $blackoutId;
    }

    /**
     * @inheritDoc
     */
    public function DeleteSeries($blackoutId)
    {
        $this->_DeletedSeriesId = $blackoutId;
    }

    /**
     * @inheritDoc
     */
    public function LoadByBlackoutId($blackoutId): ?BlackoutSeries
    {
        $this->_LoadedBlackoutId = $blackoutId;
        return $this->_Series;
    }
}
