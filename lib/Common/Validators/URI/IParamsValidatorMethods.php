<?php

interface IParamsValidatorMethods
{
    /**
     * Check if param is a numerical value
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function numericalValidator(string $param, string $requestURI): bool;

    /**
     * Check if param exists in URI
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function existsInURLValidator(string $param, string $requestURI): bool;

    /**
     * Check if param is a valid date (YYYY-MM-DD)
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function dateValidator(string $param, string $requestURI): bool;

    /**
     * Check if param is a valid date (YYYY-MM-DD) and (YYYY-M-D)
     * This can be a list a of date
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function simpleDateValidatorList(string $param, string $requestURI): bool;

    /**
     * Check if params is a valid date (YYYY-MM-DD HH:MM), hours and minutes can have one or two digits 
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function simpleDateTimeValidator(string $param, string $requestURI): bool;

    /**
     * Check if params is a valid date (YYYY-MMM-DD HH:MM:SS)
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function complexDateTimedateValidator(string $param, string $requestURI): bool;

    
    /**
     * Check if param is a valid redirect in guest-reservation route
     * 
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function redirectGuestReservationValidator(string $requestURI): bool;

    /**
     * Check if param is a valid boolean value
     * 
     * @param string $param         - Query param in URI
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function booleanValidator(string $param, string $requestURI): bool;


    /**
     * Check if param match with expecter value
     * 
     * @param string $param         - Query param in URI
     * @param string $expectedValue - Expected value to perform the match
     * @param string $requestURI    - Request URI to check the param
     * 
     * @return bool Returns true if is valid
     */
    public static function matchValidator(string $param, string $expectedValue, string $requestURI): bool;
}