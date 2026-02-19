<?php

declare(strict_types=1);

require_once(ROOT_DIR. 'lib/Application/Reservation/namespace.php');

class FakeReservationHandler implements IReservationHandler
{
    /**
     * @var bool
     */
    public $_Success = false;

    /**
     * @var ReservationSeries
     */
    public $_LastSeries;

    /**
     * @var string[]
     */
    public $_Errors = [];

    public function Handle($reservationSeries, IReservationSaveResultsView $view)
    {
        $this->_LastSeries = $reservationSeries;
        $view->SetErrors($this->_Errors);
        return $this->_Success;
    }
}
