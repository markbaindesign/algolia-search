<?php

namespace BD324\Search\Helpers;

class StringHelpers
{
    public static function slugify($text)
    {
        // Replace non-letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Trim
        $text = trim($text, '-');

        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT//IGNORE', $text);

        // Lowercase
        $text = mb_strtolower($text, 'UTF-8');

        if (empty($text)) {
            return 'n-a';
        }

        return $text;
    }


    public static function starts_with($haystack, $needle)
    {
        return strncmp($haystack, $needle, strlen($needle)) === 0;
    }

    public static function ends_with($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return substr($haystack, -$length) === $needle;
    }

    public static function contains($haystack, $needle)
    {
        return strpos($haystack, $needle) !== false;
    }

    public static function truncate($text, $max_length, $suffix = '...')
    {
        if (strlen($text) <= $max_length) {
            return $text;
        }
        return substr($text, 0, $max_length - strlen($suffix)) . $suffix;
    }

    public static function limit_words($text, $word_limit, $suffix = '...')
    {
        $words = explode(' ', $text);
        if (count($words) <= $word_limit) {
            return $text;
        }
        return implode(' ', array_slice($words, 0, $word_limit)) . $suffix;
    }

    public static function normalize_whitespace($text)
    {
        // Replace multiple whitespace characters with a single space
        $text = preg_replace('/\s+/', ' ', $text);
        // Trim leading and trailing whitespace
        $text = trim($text);
        return $text;
    }

    public static function get_script_handles($index_name, $template_name = '', $is_config = false)
    {
        $output = '';
        $handle = 'algolia-search-' . $index_name;
        if ($template_name) {
            $handle .= '-' . $template_name;
        }
        if ($is_config) {
            $handle .= '-config';
        }
        $output = apply_filters(
            'bd324_filter_handle_script_' . $handle,
            $handle
        );
        return $output;
    }


}
