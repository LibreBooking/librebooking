<?php

class ParamsValidatorMethods implements IParamsValidatorMethods
{
    /**
     * Extracts a string query parameter value from a URI using parse_url/parse_str.
     *
     * Only returns simple string values. Array-style parameters (e.g. ?uid[]=1&uid[]=2)
     * are treated as absent and return null.
     *
     * @param string $param      The query parameter name to extract
     * @param string $requestURI The full request URI
     * @return string|null       The parameter value, or null if absent or non-string
     */
    private static function getQueryParam(string $param, string $requestURI): ?string
    {
        $query = parse_url($requestURI, PHP_URL_QUERY);
        if ($query === null || $query === false) {
            return null;
        }

        parse_str($query, $params);

        if (!array_key_exists($param, $params) || !is_string($params[$param])) {
            return null;
        }

        return $params[$param];
    }

    /**
     * Validates that a query parameter is present and its value is numeric.
     */
    public static function numericalValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }

        return ctype_digit($value);
    }

    /**
     * Validates that a query parameter is either empty or numeric.
     *
     * Used for optional filter fields (e.g., schedule filter) where the param
     * may be present with an empty value when the user has not set a filter.
     */
    public static function optionalNumericValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }
        if ($value === '') {
            return true; // empty is valid for optional filter fields
        }

        return ctype_digit($value);
    }

    /**
     * Validates that a query parameter is present and has a non-empty value.
     */
    public static function existsInURLValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);

        return $value !== null && $value !== '';
    }

    /**
     * Validates that a query parameter is present and its value is a date in YYYY-MM-DD format.
     */
    public static function dateValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }

        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');

        return preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $value) === 1;
    }

    /**
     * Validates that a query parameter is present and its value is a comma-separated
     * list of dates in YYYY-M-D format (leading zeros optional for month and day).
     */
    public static function simpleDateValidatorList(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }

        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        $dates = explode(',', $value);

        foreach ($dates as $date) {
            if (!preg_match('/^\d{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[12]?\d|3[01])$/', $date)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Validates that a query parameter is present and either empty or a comma-separated
     * list of dates in YYYY-M-D format (leading zeros optional for month and day).
     */
    public static function optionalSimpleDateValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }
        if ($value === '') {
            return true;
        }

        return self::simpleDateValidatorList($param, $requestURI);
    }


    /**
     * Validates that a query parameter is present and its value is a datetime
     * in YYYY-MM-DD HH:MM format (no seconds).
     */
    public static function simpleDateTimeValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }

        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        // Validates: YYYY-MM-DD HH:MM (no seconds)
        return preg_match('/
            ^\d{4}                   # year (YYYY)
            -(0[1-9]|1[0-2])         # month (01-12)
            -(0[1-9]|[12]\d|3[01])   # day (01-31)
            \ ([01]\d|2[0-3])        # hour (00-23)
            :([0-5]\d)               # minute (00-59)
        $/x', $value) === 1;
    }

    /**
     * Validates that a query parameter is present and its value is a datetime
     * in YYYY-MM-DD HH:MM:SS format (with seconds).
     */
    public static function complexDateTimedateValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }

        $value = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
        // Validates: YYYY-MM-DD HH:MM:SS (with seconds, unlike simpleDateTimeValidator)
        return preg_match('/
            ^\d{4}                   # year (YYYY)
            -(0[1-9]|1[0-2])         # month (01-12)
            -(0[1-9]|[12]\d|3[01])   # day (01-31)
            \ ([01]\d|2[0-3])        # hour (00-23)
            :([0-5]\d)               # minute (00-59)
            :([0-5]\d)               # second (00-59)
        $/x', $value) === 1;
    }

    /**
     * Validates a guest reservation redirect URL contains a valid 'start' date
     * and a valid 'ct' (calendar type: day, week, or month).
     */
    public static function redirectGuestReservationValidator(string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $redirectURL = self::getQueryParam('redirect', $requestURI);
        if ($redirectURL === null) {
            return false;
        }

        // Ensure the original URI has a query string
        $query = parse_url($requestURI, PHP_URL_QUERY);
        if ($query === null || $query === '' || $query === false) {
            return false;
        }

        // Parse sub-parameters from the redirect URL
        $redirectQuery = parse_url($redirectURL, PHP_URL_QUERY);
        if ($redirectQuery === null || $redirectQuery === false) {
            return false;
        }

        parse_str($redirectQuery, $redirectParams);

        // Validate 'start' date parameter
        $start = $redirectParams['start'] ?? null;
        $validStart = is_string($start)
            && preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $start) === 1;

        // Validate 'ct' calendar type parameter
        $ct = $redirectParams['ct'] ?? null;
        $validCt = is_string($ct) && in_array($ct, [
            CalendarTypes::Day,
            CalendarTypes::Week,
            CalendarTypes::Month,
        ], strict: true);

        return $validStart && $validCt;
    }

    /**
     * Validates that a query parameter is present and its value is "true" or "false" (case-insensitive).
     */
    public static function booleanValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return false;
        }

        return in_array(strtolower($value), ['true', 'false'], strict: true);
    }

    /**
     * Validates that a query parameter, if present, matches the expected value.
     *
     * Returns false if the URL contains potential XSS payloads.
     * Returns true if the parameter is absent (no constraint to enforce).
     * Returns true if the parameter is present and its value matches $expectedValue.
     * Returns false if the parameter is present with a non-matching value.
     */
    public static function matchValidator(string $param, string $expectedValue, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $value = self::getQueryParam($param, $requestURI);
        if ($value === null) {
            return true; // Parameter absent — no constraint to enforce
        }

        return $value === $expectedValue;
    }



    /**
     * Checks if the URL contains patterns indicating potential XSS script injection,
     * such as <script> tags, quoted strings, or their URL-encoded equivalents.
     */
    private static function validatePossibleScripts(string $requestURI): bool
    {
        return preg_match('/%22.*%22/', $requestURI) ||
            preg_match('/".*"/', urldecode($requestURI)) ||
            preg_match('/%27.*%27/', $requestURI) ||
            preg_match("/'.*'/", urldecode($requestURI)) ||
            preg_match('/%3Cscript%3E/', $requestURI) ||
            preg_match('/<script>/', urldecode($requestURI));
    }
}
