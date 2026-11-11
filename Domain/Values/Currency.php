<?php

namespace Booked {
    require_once(ROOT_DIR . 'lib/Common/Resources.php');
    require_once(ROOT_DIR . 'lib/Common/Resources.php');

    use NumberFormatter;
    use Resources;

    class Currency
    {
        private $currencies = [];

        /**
         * @var string
         */
        private $currencyCode;

        /**
         * @var CurrencyDefinition
         */
        private $currency;

        /**
         * @param string $currencyCode
         */
        public function __construct($currencyCode)
        {
            $this->currencyCode = $currencyCode;
            $this->currencies = self::Currencies();

            $this->currency = $this->currencies[0];
            foreach ($this->currencies as $currency) {
                if ($currency->IsoCode() == $currencyCode) {
                    $this->currency = $currency;
                    break;
                }
            }
        }

        /**
         * @return CurrencyDefinition[]
         */
        public static function Currencies()
        {
            return [
                new CurrencyDefinition('USD'),
                new CurrencyDefinition('GBP'),
                new CurrencyDefinition('AUD'),
                new CurrencyDefinition('BRL'),
                new CurrencyDefinition('CAD'),
                new CurrencyDefinition('CZK'),
                new CurrencyDefinition('DKK'),
                new CurrencyDefinition('EUR'),
                new CurrencyDefinition('HKD'),
                new CurrencyDefinition('HUF'),
                new CurrencyDefinition('ILS'),
                new CurrencyDefinition('JPY', true),
                new CurrencyDefinition('MYR'),
                new CurrencyDefinition('MXN'),
                new CurrencyDefinition('NOK'),
                new CurrencyDefinition('NZD'),
                new CurrencyDefinition('PHP'),
                new CurrencyDefinition('PLN'),
                new CurrencyDefinition('SGD'),
                new CurrencyDefinition('SEK'),
                new CurrencyDefinition('CHF'),
                new CurrencyDefinition('TWD'),
                new CurrencyDefinition('THB'),
            ];
        }

        /**
         * @param string $currencyCode
         * @return Currency
         */
        public static function Create($currencyCode)
        {
            return new Currency($currencyCode);
        }

        /**
         * @param float $amount
         * @return string
         */
        public function Format($amount)
        {
            if (!class_exists('NumberFormatter')) {
                if ($this->currencyCode == 'USD') {
                    return '$' . floatval($amount) . 'USD';
                } else {
                    return 'We cannot format this currency. <a href="http://php.net/manual/en/book.intl.php">You must enable internationalization</a>.';
                }
            } else {
                $fmt = new NumberFormatter(Resources::GetInstance()->CurrentLanguage, NumberFormatter::CURRENCY);
                return $fmt->formatCurrency($amount, $this->currencyCode);
            }
        }

        /**
         * Converts an amount to the integer number of minor units (e.g.
         * cents) that Stripe requires. Stripe rejects an amount that is not
         * a whole number, and floating point arithmetic makes 19.99 * 100
         * slightly less than 1999.
         *
         * Rounds to cents before multiplying: 1.005 * 100 is
         * 100.49999999999999, which round() turns into 100 on PHP 8.4 and
         * later but 101 on earlier versions. round(1.005, 2) is 1.01 on all
         * of them.
         *
         * @param float|int|string $amount
         * @return int
         */
        public function ToStripe($amount): int
        {
            if ($this->IsZeroDecimal()) {
                return (int) round((float) $amount);
            }

            return (int) round(round((float) $amount, 2) * 100);
        }

        /**
         * @param float $amount
         * @return float
         */
        public function FromStripe($amount)
        {
            if ($this->IsZeroDecimal()) {
                return $amount;
            }

            return $amount / 100;
        }

        public function IsZeroDecimal()
        {
            return $this->currency->IsZeroDecimal();
        }
    }

    class CurrencyDefinition
    {
        /**
         * @var string
         */
        private $isoCode;

        /**
         * @var bool
         */
        private $isZeroDecimal;

        /**
         * @param string $isoCode
         * @param bool $isZeroDecimal
         */
        public function __construct($isoCode, $isZeroDecimal = false)
        {
            $this->isoCode = $isoCode;
            $this->isZeroDecimal = $isZeroDecimal;
        }

        public function __toString()
        {
            return $this->isoCode;
        }

        /**
         * @return string
         */
        public function IsoCode()
        {
            return $this->isoCode;
        }

        /**
         * @return bool
         */
        public function IsZeroDecimal()
        {
            return $this->isZeroDecimal;
        }
    }
}
