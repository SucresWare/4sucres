<?php

namespace App\Helpers;

use Illuminate\Support\Carbon;

class SucresHelper
{
    const NICEDATE_MINIMAL = 0;
    const NICEDATE_WITH_HOURS = 1;

    public static function niceDate(Carbon $date, $ret_type = self::NICEDATE_WITH_HOURS)
    {
        $diffInDays = $date->copy()->startOfDay()->diffInWeekDays(now()->startOfDay());

        switch ($diffInDays) {
            case 0:
                $markup = 'aujourd\'hui';

                break;
            case 1:
                $markup = 'hier';

                break;
            default:
                $markup = 'le ' . $date->format('d/m/Y');
        }

        if ($ret_type == self::NICEDATE_WITH_HOURS) {
            $markup .= ' à ' . $date->format('H:i:s');
        }

        return $markup;
    }

    /**
     * Matches what we call "unicode whitespace", i.e. normal ASCII whitespace as well as special
     * unicode control and whitespace properties. Use only in regex with /u modifier!
     *
     * By using:
     * - \pZ we match any kind of whitespace or invisible separator
     * - \p{Cc} we match control characters
     * - \x{feff} we match \uFEFF ; in the past known as BOM
     *
     * http://www.regular-expressions.info/unicode.html has a good overview
     */
    const RE_UNICODE_WS = '[\pZ\p{Cc}\x{feff}]';

    /**
     * Like trim() but also handles unicode specific properties.
     *
     * @param string $str
     *
     * @return string
     */
    public static function unicodeTrim($str): string
    {
        $str = preg_replace(
            '/^' . self::RE_UNICODE_WS . '+|' . self::RE_UNICODE_WS . '+$/u',
            ' ',
            $str
        );

        return trim($str, "\t\n\r\0\x0B" . '⠀');
    }
}