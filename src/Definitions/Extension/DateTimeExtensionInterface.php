<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

use DateInterval;
use DateTimeInterface;

/**
 * Functions accepting a date string use the `strtotime()` function internally.
 */
interface DateTimeExtensionInterface extends ExtensionInterface
{
    /**
     * Get a DateTime object between January 1, 1970, and `$until` (defaults to "now").
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null   $timezone zone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     *
     * @example DateTimeImmutable('2005-08-16 20:39:21')
     */
    public function dateTime(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a DateTimeInterface object for a date between January 1, 0001, and now.
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone zone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @example DateTime('1265-03-22 21:15:52')
     *
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeAD(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a DateTimeInterface object a random date between `$from` and `$until`.
     * Accepts date strings that can be recognized by `strtotime()`.
     *
     * @param \DateTimeInterface|string     $from     defaults to 30 years ago
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone zone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeBetween(DateTimeInterface|string $from = '-30 years', DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a DateTimeInterface object based on a random date between `$from` and an interval.
     * Accepts date string that can be recognized by `strtotime()`.
     *
     * @param \DateTimeInterface|string $from     defaults to 30 years ago
     * @param DateInterval|string               $interval defaults to 5 days after
     * @param string|null          $timezone zone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeInInterval(
        DateTimeInterface|string $from = '-30 years',
        DateInterval|string $interval = '+5 days',
        ?string $timezone = null
    ): \DateTimeInterface;

    /**
     * Get a date time object somewhere inside the current week.
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone zone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeThisWeek(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a date time object somewhere inside the current month.
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeThisMonth(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a date time object somewhere inside the current year.
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeThisYear(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a date time object somewhere inside the current decade.
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeThisDecade(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a date time object somewhere inside the current century.
     *
     * @param \DateTimeInterface|string $until    maximum timestamp, defaults to "now"
     * @param string|null          $timezone timezone for generated date, fallback to `DateTime::$defaultTimezone` and `date_default_timezone_get()`.
     *
     * @see \DateTimeZone
     * @see http://php.net/manual/en/timezones.php
     * @see http://php.net/manual/en/function.date-default-timezone-get.php
     */
    public function dateTimeThisCentury(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface;

    /**
     * Get a date string between January 1, 1970, and `$until`.
     *
     * @param string               $format DateTime format
     * @param \DateTimeInterface|string $until  maximum timestamp, defaults to "now"
     *
     * @see https://www.php.net/manual/en/datetime.format.php
     */
    public function date(string $format = 'Y-m-d', DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a time string (24h format by default).
     *
     * @param string               $format DateTime format
     * @param \DateTimeInterface|string $until  maximum timestamp, defaults to "now"
     *
     * @see https://www.php.net/manual/en/datetime.format.php
     */
    public function time(string $format = 'H:i:s', DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a UNIX (POSIX-compatible) timestamp between January 1, 1970, and `$until`.
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     */
    public function unixTime(DateTimeInterface|string $until = 'now'): int;

    /**
     * Get a date string according to the ISO-8601 standard.
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     */
    public function iso8601(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a string containing either "am" or "pm".
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     *
     * @example 'am'
     */
    public function amPm(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a localized random day of the month.
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     *
     * @example '16'
     */
    public function dayOfMonth(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a localized random day of the week.
     *
     * Uses internal DateTimeInterface formatting, hence PHP's internal locale will be used (change using `setlocale()`).
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     *
     * @example 'Tuesday'
     *
     * @see setlocale
     * @see https://www.php.net/manual/en/function.setlocale.php Set a different output language
     */
    public function dayOfWeek(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a random month (numbered).
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     *
     * @example '7'
     */
    public function month(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a random month.
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     *
     * @see setlocale
     * @see https://www.php.net/manual/en/function.setlocale.php Set a different output language
     *
     * @example 'September'
     */
    public function monthName(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a random year between 1970 and `$until`.
     *
     * @param \DateTimeInterface|string $until maximum timestamp, defaults to "now"
     *
     * @example '1987'
     */
    public function year(DateTimeInterface|string $until = 'now'): string;

    /**
     * Get a random century, formatted as Roman numerals.
     *
     * @example 'XVII'
     */
    public function century(): string;

    /**
     * Get a random timezone, uses `\DateTimeZone::listIdentifiers()` internally.
     *
     * @example 'Europe/Rome'
     */
    public function timezone(): string;
}
