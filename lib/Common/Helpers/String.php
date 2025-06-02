<?php

class BookedStringHelper
{
    /**
     * @static
     * @param $haystack string
     * @param $needle string
     * @return bool
     */
    public static function StartsWith($haystack, $needle)
    {
        $length = strlen($needle ?? '');
        return (substr($haystack ?? '', 0, $length) === $needle);
    }

    /**
     * @static
     * @param $haystack string
     * @param $needle string
     * @return bool
     */
    public static function EndsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        $start  = $length * -1;
        return (substr($haystack, $start) === $needle);
    }

    /**
     * @static
     * @param $haystack string
     * @param $needle string
     * @return bool
     */
    public static function Contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    /**
     * @return string
     */
    public static function Random($length = 50)
    {
        try {
            $string = random_bytes(intval($length / 2));
            $string = bin2hex($string);
        } catch (Exception $e) {
            $string = uniqid(rand(), true);
            Log::Error('Could not generate web service session token. %s', $e);
        }
        return $string;
    }
}
