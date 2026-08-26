<?php

class HtmlNormalizer
{
    public static function normalize(string $html): string
    {
        // Canonicalize numeric entities to their named/short equivalents
        $html = preg_replace_callback('/&#0*(\d+);/', static function ($m) {
            return '&#' . (int) $m[1] . ';';
        }, $html);
        $html = str_replace(['&#39;', '&#34;'], ['&apos;', '&quot;'], $html);

        // Canonicalize single-quoted attribute values to double quotes. Smarty keeps the quote
        // style of the source, while the Twig templates use double quotes for HTML attributes.
        $html = preg_replace_callback('/<[a-zA-Z][^<>]*>/', static function ($m) {
            return preg_replace('/(\s[\w:@.\-]+=)\'([^\'"]*)\'/', '$1"$2"', $m[0]);
        }, $html);

        // Collapse whitespace between tags and trim runs of whitespace
        $html = preg_replace('/>\s+</', '><', $html);
        $html = preg_replace('/\s+/', ' ', $html);

        return trim($html);
    }
}
