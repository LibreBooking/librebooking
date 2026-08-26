<?php

declare(strict_types=1);

namespace LibreBooking\Common\Text;

/**
 * Converts plain-text URLs and email addresses in a string into clickable HTML
 * anchor elements.
 *
 * This is the single implementation shared by the Smarty url2link modifier
 * (SmartyPage::CreateUrl) and the Twig url2link filter.  Both engines must
 * delegate here so that output is byte-identical and tests only need to cover
 * one place.
 */
final class LinkifyText
{
    /**
     * Linkifies http/https URLs and valid email addresses found in $text.
     *
     * Behaviour is preserved 1-to-1 from the original SmartyPage::CreateUrl
     * implementation (WordPress-inspired three-pass regex approach).
     *
     * @param string $text The plain-text (or lightly HTML-marked) input.
     * @return string The input with safe URLs and emails wrapped in <a> tags.
     */
    public static function linkify(string $text): string
    {
        // credit to WordPress wp-includes/formatting.php
        $makeUrlClickable = static function (array $matches): string {
            $ret = '';
            $url = $matches[2];

            if (empty($url)) {
                return $matches[0];
            }
            // removed trailing [.,;:] from URL
            if (in_array(substr((string) $url, -1), ['.', ',', ';', ':'], true)) {
                $ret = substr((string) $url, -1);
                $url = substr((string) $url, 0, strlen((string) $url) - 1);
            }

            // Only linkify safe http(s) URLs. Without this, url2link would wrap
            // text such as javascript://%0Aalert%281%29 in a live <a href>, which
            // bypasses any later sanitizer allowlist.
            if (!self::isSafeLinkifyUrl((string) $url)) {
                return $matches[0];
            }

            $text = $url;
            if (strlen($text) > 30) {
                $text = substr($text, 0, 30) . '...';
            }

            return $matches[1] . "<a href=\"$url\" target=\"_blank\" rel=\"noopener noreferrer nofollow\">$text</a>" . $ret;
        };

        $makeWebFtpClickableCb = static function (array $matches): string {
            $ret = '';
            $dest = $matches[2];
            $dest = 'http://' . $dest;

            // removed trailing [,;:] from URL
            if (in_array(substr($dest, -1), ['.', ',', ';', ':'], true)) {
                $ret = substr($dest, -1);
                $dest = substr($dest, 0, strlen($dest) - 1);
            }

            $text = $dest;
            if (strlen($text) > 30) {
                $text = substr($text, 0, 30) . '...';
            }

            return $matches[1] . "<a href=\"$dest\" rel=\"noopener noreferrer nofollow\">$text</a>" . $ret;
        };

        $makeEmailClickableCb = static function (array $matches): string {
            $email = $matches[2] . '@' . $matches[3];
            if (!self::isValidEmailAddress($email)) {
                return $matches[0];
            }
            return $matches[1] . "<a href=\"mailto:$email\">$email</a>";
        };

        $text = ' ' . $text;
        $text = preg_replace_callback(
            '#([\s>])([\w]+?://[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
            $makeUrlClickable,
            $text
        );
        $text = preg_replace_callback(
            '#([\s>])((www|ftp)\.[\w\\x80-\\xff\#$%&~/.\-;:=,?@\[\]+]*)#is',
            $makeWebFtpClickableCb,
            (string) $text
        );
        $text = preg_replace_callback(
            '#([\s>])([.0-9a-z_+-]+)@(([0-9a-z-]+\.)+[0-9a-z]{2,})#i',
            $makeEmailClickableCb,
            (string) $text
        );
        $text = preg_replace('#(<a( [^>]+?>|>))<a [^>]+?>([^>]+?)</a></a>#i', '$1$3</a>', (string) $text);
        $text = trim((string) $text);
        return $text;
    }

    private static function isSafeLinkifyUrl(string $url): bool
    {
        try {
            $scheme = \League\Uri\Uri::new($url)->getScheme();
        } catch (\Throwable $e) {
            return false;
        }

        return in_array(strtolower((string) $scheme), ['http', 'https'], true);
    }

    private static function isValidEmailAddress(string $email): bool
    {
        static $validator = null;
        static $rule = null;
        $validator ??= new \Egulias\EmailValidator\EmailValidator();
        $rule ??= new \Egulias\EmailValidator\Validation\RFCValidation();

        return $validator->isValid($email, $rule);
    }
}
