<?php

class ReservationRetryParameter
{
    public static $SKIP_CONFLICTS = 'skipconflicts';
    private $name;
    private $value;

    /**
     * @param string $name
     * @param string $value
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @static
     * @param $params string|string[]|null The result of $this->GetForm(FormKeys::RESERVATION_RETRY_PREFIX)
     * @return array|AttributeFormElement[]
     */
    public static function GetParamsFromForm($params)
    {
        if (is_array($params)) {
            $af = [];

            foreach ($params as $name => $value) {
                $af[] = new ReservationRetryParameter($name, $value);
            }

            return $af;
        }

        return [];
    }

    /**
     * @param string $parameterName
     * @param ReservationRetryParameter[] $retryParameters
     * @param null|IConvert $converter
     * @return null|string
     */
    public static function GetValue($parameterName, $retryParameters, $converter = null)
    {
        if (!is_array($retryParameters)) {
            return null;
        }

        if ($converter == null) {
            $converter = new LowerCaseConverter();
        }

        /** @var ReservationRetryParameter $retryParameter */
        foreach ($retryParameters as $retryParameter) {
            if ($retryParameter->Name() == $parameterName) {
                return $converter->Convert($retryParameter->Value());
            }
        }

        return null;
    }

    /**
     * @return string
     */
    public function Name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function Value()
    {
        return $this->value;
    }
}
