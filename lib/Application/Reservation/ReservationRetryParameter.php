<?php

class ReservationRetryParameter
{
    public static $SKIP_CONFLICTS = 'skipconflicts';
    private $name;
    private $value;

    /**
     * @param string $name
     * @param string|bool $value a string from a submitted form, or a bool
     *        from a validation rule or an API request
     */
    public function __construct($name, $value)
    {
        $this->name = $name;
        $this->value = $value;
    }

    /**
     * @static
     * @param string|array<string, string>|null $params The result of $this->GetForm(FormKeys::RESERVATION_RETRY_PREFIX)
     * @return list<ReservationRetryParameter>
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
     * @param null|IConvert $converter defaults to LowerCaseConverter
     * @return mixed the converter's result, for example a bool from
     *         BooleanConverter; null when the parameter is not present
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
     * @return string|bool
     */
    public function Value()
    {
        return $this->value;
    }
}
