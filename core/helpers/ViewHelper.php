<?php

namespace core\helpers;

class ViewHelper
{
    /**
     * Convert text from Underscore to text with spaces and first uppercase.
     *
     * from 'this_is_example' to 'This is example.
     * @param string $text
     * @return string
     */
    public static function underscoreToUCFirst(string $text): string
    {
        return ucfirst(str_replace('_', ' ', $text));
    }

    public static function underscoreToUCWords(string $text): string
    {
        return ucwords(str_replace('_', ' ', $text));
    }
}
