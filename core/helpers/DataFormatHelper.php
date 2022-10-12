<?php

namespace core\helpers;

class DataFormatHelper
{
    public static function hideLetters($string)
    {
        for ($i=0; $i < strlen($string); $i++) {
            if ($i < 3 || $i > strlen($string)-4) continue;
            if ($string[$i] === '-') continue;
            $string[$i] = '*';
        }
        return $string;
    }

    public static function returnDateWithMilliseconds(float $timestamp): string
    {
        date_default_timezone_set('Asia/Krasnoyarsk');
        $date = explode(".", $timestamp);
        while (strlen($date[1]) < 3) {
            $date[1] = $date[1].'0';
        }
        return date('d.m.Y H:i:s', $date[0]).'.'.$date[1];
    }

    public static function returnDate(int $timestamp): string
    {
        date_default_timezone_set('Asia/Krasnoyarsk');
        return date('d.m.Y H:i:s', $timestamp);
    }

    public static function cutFloat(float $float, int $precision = 8): float
    {
        return floor($float*10**$precision)/10**$precision;
    }

    /**
     * Analog of JS method to fixed
     *
     * @param float $float
     * @param int $precision
     * @return string
     */
    public static function toFixed(float $float, int $precision = 8): string
    {
        /*
        $float = explode(".", $float);
        $float[1] = substr($float[1], 0, $precision);
        while (strlen($float[1]) <= $precision) {
            $float[1] = $float[1].'0';
        }
        return $float[0].'.'.$float[1];
        */
        return sprintf('%.'.$precision.'f', $float);
    }

    public static function returnCheckBox(bool $value, bool $disable = true): string
    {
        $string = '<input type="checkbox"';
        if ($disable) $string .= ' disabled="disabled"';
        if ($value) $string .= ' checked';
        $string .= '>';
        return $string;
    }
}
