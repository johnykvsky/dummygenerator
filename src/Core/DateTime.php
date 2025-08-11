<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use DummyGenerator\Definitions\Extension\Awareness\ClockAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ClockAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

class DateTime implements DateTimeExtensionInterface, RandomizerAwareExtensionInterface, ClockAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;
    use ClockAwareExtensionTrait;

    /**
     * @var string[]
     * TODO move to interface const
     */
    protected array $centuries = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX', 'XXI'];

    public function dateTime(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        return $this->setTimezone(
            $this->getTimestampDateTime($this->unixTime($until)),
            $timezone,
        );
    }

    public function dateTimeAD(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        $min = (PHP_INT_SIZE > 4) ? -62135597361 : -PHP_INT_MAX;

        return $this->setTimezone(
            $this->getTimestampDateTime($this->randomizer->getInt($min, $this->getTimestamp($until))),
            $timezone,
        );
    }

    public function dateTimeBetween(DateTimeInterface|string $from = '-30 years', DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        $start = $this->getTimestamp($from);
        $end = $this->getTimestamp($until);

        if ($start > $end) {
            throw new ExtensionArgumentException('"$from" must be anterior to "$until".');
        }

        $timestamp = $this->randomizer->getInt($start, $end);

        return $this->setTimezone(
            $this->getTimestampDateTime($timestamp),
            $timezone,
        );
    }

    public function dateTimeInInterval(
        DateTimeInterface|string $from = '-30 years',
        DateInterval|string $interval = '+5 days',
        ?string $timezone = null
    ): \DateTimeInterface {
        $intervalObject = $interval instanceof DateInterval ? $interval : DateInterval::createFromDateString($interval);
        $datetime = $from instanceof \DateTimeInterface ? $from : new \DateTimeImmutable((string) $from);

        // @phpstan-ignore-next-line
        $other = $datetime->add($intervalObject);

        $begin = min($datetime, $other);
        $end = $datetime === $begin ? $other : $datetime;

        return $this->dateTimeBetween($begin, $end, $timezone);
    }

    public function dateTimeThisWeek(DateTimeInterface|string $until = 'sunday this week', ?string $timezone = null): \DateTimeInterface
    {
        $from = $this->clock->now();

        if ($timezone !== null) {
            $from = $from->setTimezone(new DateTimeZone($timezone));
        }

        if ($from->format('w') !== '1') {
            $from = $from->modify('last monday');
        }

        $from = $from->setTime(0, 0, 0);

        return $this->dateTimeBetween($from, $until, $timezone);
    }

    public function dateTimeThisMonth(DateTimeInterface|string $until = 'last day of this month', ?string $timezone = null): \DateTimeInterface
    {
        return $this->dateTimeBetween('first day of this month', $until, $timezone);
    }

    public function dateTimeThisYear(DateTimeInterface|string $until = 'last day of december', ?string $timezone = null): \DateTimeInterface
    {
        return $this->dateTimeBetween('first day of january', $until, $timezone);
    }

    public function dateTimeThisDecade(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        $year = floor(date('Y') / 10) * 10;

        return $this->dateTimeBetween("first day of january $year", $until, $timezone);
    }

    public function dateTimeThisCentury(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        $year = floor(date('Y') / 100) * 100;

        return $this->dateTimeBetween("first day of january $year", $until, $timezone);
    }

    public function date(string $format = 'Y-m-d', DateTimeInterface|string $until = 'now'): string
    {
        return $this->dateTime($until)->format($format);
    }

    public function time(string $format = 'H:i:s', DateTimeInterface|string $until = 'now'): string
    {
        return $this->date($format, $until);
    }

    public function unixTime(DateTimeInterface|string $until = 'now'): int
    {
        return $this->randomizer->getInt(0, $this->getTimestamp($until));
    }

    public function iso8601(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date(DateTimeInterface::ATOM, $until);
    }

    public function amPm(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date('a', $until);
    }

    public function dayOfMonth(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date('d', $until);
    }

    public function dayOfWeek(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date('l', $until);
    }

    public function month(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date('m', $until);
    }

    public function monthName(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date('F', $until);
    }

    public function year(DateTimeInterface|string $until = 'now'): string
    {
        return $this->date('Y', $until);
    }

    public function century(): string
    {
        return $this->randomizer->randomElement($this->centuries);
    }

    public function timezone(): string
    {
        return $this->randomizer->randomElement(\DateTimeZone::listIdentifiers());
    }

    /**
     * Get the POSIX-timestamp of a DateTime or string (can be epoch as "1733785884").
     */
    protected function getTimestamp(\DateTimeInterface|string $until = 'now'): int
    {
        if (is_numeric($until)) {
            return (int) $until;
        }

        if ($until instanceof \DateTimeInterface) {
            return $until->getTimestamp();
        }

        return $this->getDateTimeFromString($until)->getTimestamp();
    }

    /**
     * Get a DateTimeImmutable created based on a POSIX-timestamp.
     *
     * @param int $timestamp the UNIX / POSIX-compatible timestamp
     * @throws \DateMalformedStringException
     */
    protected function getTimestampDateTime(int $timestamp): \DateTimeInterface
    {
        return new \DateTimeImmutable('@' . $timestamp);
    }

    protected function resolveTimezone(?string $timezone): string
    {
        if ($timezone === null) {
            return date_default_timezone_get();
        }

        $timezones = \DateTimeZone::listIdentifiers();
        if (in_array($timezone, $timezones, true)) {
            return $timezone;
        }

        throw new ExtensionArgumentException('"$timezone" is not a valid timezone.');
    }

    /**
     * Internal method to set the timezone on a DateTime object.
     */
    protected function setTimezone(\DateTimeInterface $dateTime, ?string $timezone): \DateTimeInterface
    {
        $timezone = $this->resolveTimezone($timezone);

        // @phpstan-ignore-next-line
        return $dateTime->setTimezone(new \DateTimeZone($timezone));
    }

    protected function getDateTimeFromString(string $dateString): DateTimeInterface
    {
        try {
            return new DateTimeImmutable($dateString);
        } catch (\Throwable $e) {
            throw new ExtensionArgumentException('Invalid datetime string given.', $e->getCode(), $e);
        }
    }
}
