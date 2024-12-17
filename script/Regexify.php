<?php

declare(strict_types=1);

namespace Script;

/*
 * This class is only for compatibility, not used and not recommended to use: VERY slow
 */
class Regexify
{
    /**
     * Returns a random letter from a to z
     */
    private static function randomDigit(): int
    {
        return random_int(0, 9);
    }

    /**
     * Returns a random letter from a to z
     */
    private static function randomDigitNotZero(): int
    {
        return random_int(1, 9);
    }

    /**
     * Returns a random letter from a to z
     */
    public static function randomLetter(): string
    {
        return chr(random_int(97, 122));
    }

    /**
     * Returns a random ASCII character (excluding accents and special chars)
     */
    public static function randomAscii(): string
    {
        return chr(random_int(33, 126));
    }

    /**
     * Returns a random element from a passed array.
     */
    public static function randomElement(array $array): mixed
    {
        if ($array === []) {
            return null;
        }

        return $array[array_rand($array, 1)];
    }


    /**
     * Transforms a basic regular expression into a random string satisfying the expression.
     *
     * @example Regexify::regexify('[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}'); // sm0@y8k96a.ej
     *
     * Regex delimiters '/.../' and begin/end markers '^...$' are ignored.
     *
     * Only supports a small subset of the regex syntax. For instance,
     * unicode, negated classes, unbounded ranges, subpatterns, back references,
     * assertions, recursive patterns, and comments are not supported. Escaping
     * support is extremely fragile.
     *
     * This method is also VERY slow. Use it only when no other extension
     * can generate the fake data you want. For instance, prefer calling
     * `$generator->email()` rather than `regexify` with the previous regular
     * expression.
     *
     * Also note than `bothify` can probably do most of what this method does,
     * but much faster. For instance, for a dummy email generation, try
     * `Helper::bothify('?????????@???.???')`.
     *
     * @see https://github.com/icomefromthenet/ReverseRegex for a more robust implementation
     *
     * @param string $regex A regular expression (delimiters are optional)
     * @return string
     */
    public static function regexify(string $regex = ''): string
    {
        // ditch the anchors
        $regex = preg_replace('/^\/?\^?/', '', $regex);
        $regex = preg_replace('/\$?\/?$/', '', $regex);
        // All {2} become {2,2}
        $regex = preg_replace('/\{(\d+)\}/', '{\1,\1}', $regex);
        // Single-letter quantifiers (?, *, +) become bracket quantifiers ({0,1}, {0,rand}, {1, rand})
        $regex = preg_replace('/(?<!\\\)\?/', '{0,1}', $regex);
        $regex = preg_replace('/(?<!\\\)\*/', '{0,' . self::randomDigitNotZero() . '}', $regex);
        $regex = preg_replace('/(?<!\\\)\+/', '{1,' . self::randomDigitNotZero() . '}', $regex);
        // [12]{1,2} becomes [12] or [12][12]
        $regex = preg_replace_callback('/(\[[^\]]+\])\{(\d+),(\d+)\}/', static function ($matches) {
            return str_repeat($matches[1], (int) self::randomElement(range($matches[2], $matches[3])));
        }, $regex);
        // (12|34){1,2} becomes (12|34) or (12|34)(12|34)
        $regex = preg_replace_callback('/(\([^\)]+\))\{(\d+),(\d+)\}/', static function ($matches) {
            return str_repeat($matches[1], (int) self::randomElement(range($matches[2], $matches[3])));
        }, $regex);
        // A{1,2} becomes A or AA or \d{3} becomes \d\d\d
        $regex = preg_replace_callback('/(\\\?.)\{(\d+),(\d+)\}/', static function ($matches) {
            return str_repeat($matches[1], (int) self::randomElement(range($matches[2], $matches[3])));
        }, $regex);
        // (this|that) becomes 'this' or 'that'
        $regex = preg_replace_callback('/\((.*?)\)/', static function ($matches) {
            return self::randomElement(explode('|', str_replace(array('(', ')'), '', $matches[1])));
        }, $regex);
        // All A-F inside of [] become ABCDEF
        $regex = preg_replace_callback('/\[([^\]]+)\]/', static function ($matches) {
            return '[' . preg_replace_callback('/(\w|\d)\-(\w|\d)/', static function ($range) {
                    return implode('', range($range[1], $range[2]));
            }, $matches[1]) . ']';
        }, $regex);
        // All [ABC] become B (or A or C)
        $regex = preg_replace_callback('/\[([^\]]+)\]/', static function ($matches) {
            return self::randomElement(str_split($matches[1]));
        }, $regex);
        // replace \d with number and \w with letter and . with ascii
        $regex = preg_replace_callback('/\\\w/', self::randomLetter(...), $regex);
        $regex = preg_replace_callback('/\\\d/', self::randomDigit(...), $regex);
        $regex = preg_replace_callback('/(?<!\\\)\./', self::randomAscii(...), $regex);
        // remove remaining backslashes
        // phew
        return str_replace('\\', '', $regex);
    }
}
