<?php

class ParamsValidatorMethods implements IParamsValidatorMethods
{
    /**
     * Validates that a query parameter is present and its value is numeric.
     */
    public static function numericalValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $pattern = '/[?&]' . preg_quote($param, '/') . '=([0-9]+)/';

        if (preg_match($pattern, $requestURI, $matches)) {
            return is_numeric($matches[1]);
        }

        return false;
    }

    /**
     * Validates that a query parameter is present and has a non-empty value.
     */
    public static function existsInURLValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $pattern = "/(?:\?|&)" . preg_quote($param, '/') . '=([^&]*)/';

        if (preg_match($pattern, $requestURI, $matches)) {
            return $matches[1] !== '';
        }

        return false;
    }


    /**
     * Validates that a query parameter is present and its value is a date in YYYY-MM-DD format.
     */
    public static function dateValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $pattern = '/[?&]' . preg_quote($param, '/') . '=([^&]*)/';

        if (preg_match($pattern, $requestURI, $matches)) {
            $value = htmlspecialchars(urldecode($matches[1]), ENT_QUOTES, 'UTF-8');
            return preg_match('/^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])$/', $value) === 1;
        }

        return false;
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

        $pattern = '/[?&]' . preg_quote($param, '/') . '=([^&]*)/';

        if (preg_match($pattern, $requestURI, $matches)) {
            $value = htmlspecialchars(urldecode($matches[1]), ENT_QUOTES, 'UTF-8');

            $dates = explode(',', $value);

            foreach ($dates as $date) {
                if (!preg_match('/^\d{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[12]?\d|3[01])$/', $date)) {
                    return false;
                }
            }

            return true;
        }

        return false;
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

        $pattern = '/[?&]' . preg_quote($param, '/') . '=([^&]*)/';

        if (preg_match($pattern, $requestURI, $matches)) {
            $value = htmlspecialchars(urldecode($matches[1]), ENT_QUOTES, 'UTF-8');
            // Validates: YYYY-MM-DD HH:MM (no seconds)
            return preg_match('/
                ^\d{4}                   # year (YYYY)
                -(0[1-9]|1[0-2])         # month (01-12)
                -(0[1-9]|[12]\d|3[01])   # day (01-31)
                \ ([01]\d|2[0-3])        # hour (00-23)
                :([0-5]\d)               # minute (00-59)
            $/x', $value) === 1;
        }

        return false;
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

        $pattern = '/[?&]' . preg_quote($param, '/') . '=([^&]*)/';

        if (preg_match($pattern, $requestURI, $matches)) {
            $value = htmlspecialchars(urldecode($matches[1]), ENT_QUOTES, 'UTF-8');
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

        return false;
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

        $pattern = "/(?:\?|&)redirect=([^&]+)/";

        if (preg_match($pattern, $requestURI, $matches)) {
            $redirectURL = urldecode($matches[1]);

            $segments = explode('?', $requestURI);
            if (!isset($segments[1]) || $segments[1] === '') {
                return false;
            }

            $validStart = preg_match('/[?&]start=(\d{4})-(0[1-9]|1[0-2])-(0[1-9]|[12]\d|3[01])/', $redirectURL);

            preg_match('/[?&]ct=([a-zA-Z0-9_]+)/', $redirectURL, $ct);
            $validCt = in_array($ct[1], [
                CalendarTypes::Day,
                CalendarTypes::Week,
                CalendarTypes::Month
            ]);
            return ($validCt && $validStart);
        }

        return false;
    }

    /**
     * Validates that a query parameter is present and its value is "true" or "false" (case-insensitive).
     */
    public static function booleanValidator(string $param, string $requestURI): bool
    {
        if (self::validatePossibleScripts($requestURI)) {
            return false; // Reject URLs containing potential XSS payloads
        }

        $pattern = '/[?&]' . preg_quote($param, '/') . '=(true|false)(?:&|$)/i';

        return preg_match($pattern, $requestURI) === 1;
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

        $paramPresentPattern = '/[?&]' . preg_quote($param, '/') . '=([^&]*)/';

        if (!preg_match($paramPresentPattern, $requestURI)) {
            return true;
        }

        $pattern = '/[?&]' . preg_quote($param, '/') . '=' . preg_quote($expectedValue, '/') . '(?:&|$)/';

        return preg_match($pattern, $requestURI) === 1;
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
