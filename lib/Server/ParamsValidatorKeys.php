<?php

class ParamsValidatorKeys
{
    private function __construct()
    {
    }

    public const NUMERICAL = 'n';
    public const EXISTS = 'e';
    public const DATE = 'd';
    public const SIMPLE_DATE = 'sd';
    public const SIMPLE_DATETIME = 'sdt';
    public const COMPLEX_DATETIME = 'cdt';
    public const REDIRECT_GUEST_RESERVATION = 'rgr';
    public const OPTIONAL = 'o';
    public const BOOLEAN = 'b';
    public const MATCH = 'm';
    public const OPTIONAL_NUMERIC = 'optional_numeric';
    public const OPTIONAL_SIMPLEDATE = 'optional_simpledate';
}
