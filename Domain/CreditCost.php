<?php

require_once(ROOT_DIR . 'Domain/Values/Currency.php');
use Booked\Currency;

class CreditCost
{
    /**
     * Number of Credits for the given cost
     * @var int
     */
    private $count;
    /**
     * @var float
     */
    private $cost;
    /**
     * @var string
     */
    private $currency;

    /**
     * @param int $count number of credits for the given cost
     * @param float $cost
     * @param string $currency
     */
    public function __construct($count = 1, $cost = 0.0, $currency = 'USD')
    {
        $this->count = $count;
        $this->cost = $cost;
        $this->currency = $currency;
    }

    /**
     * @return int
     */
    public function Count()
    {
        return $this->count;
    }

    /**
     * @return float
     */
    public function Cost()
    {
        return $this->cost;
    }

    /**
     * @return string
     */
    public function Currency()
    {
        return $this->currency;
    }

    /**
     * @param float|null $amount
     * @return string
     */
    public function FormatCurrency($amount = null)
    {
        $toFormat = is_null($amount) ? $this->Cost() : $amount;
        $currency = new Currency($this->Currency());
        return $currency->Format($toFormat);
    }

    /**
     * @param float $quantity
     * @return float
     */
    public function GetTotal($quantity)
    {
        return $this->Cost() * $quantity;
    }

    /**
     * @param float $quantity
     * @return string
     */
    public function GetFormattedTotal($quantity)
    {
        $total = $this->GetTotal($quantity);
        return $this->FormatCurrency($total);
    }
}
