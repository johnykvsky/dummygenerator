<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DateInterval;
use DateTimeInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

class DateTime implements DateTimeExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    /**
     * @var string[]
     * TODO move to interface const
     */
    private array $centuries = ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X', 'XI', 'XII', 'XIII', 'XIV', 'XV', 'XVI', 'XVII', 'XVIII', 'XIX', 'XX', 'XXI'];

    private ?string $defaultTimezone = null;

    public function dateTime(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        return $this->setTimezone(
            $this->getTimestampDateTime($this->unixTime($until)),
            $timezone
        );
    }

    public function dateTimeAD(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface
    {
        $min = (PHP_INT_SIZE > 4) ? -62135597361 : -PHP_INT_MAX;

        return $this->setTimezone(
            $this->getTimestampDateTime($this->randomizer->getInt($min, $this->getTimestamp($until))),
            $timezone
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
            $timezone
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
        return $this->dateTimeBetween('monday this week', $until, $timezone);
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
     *
     * @param \DateTimeInterface|string $until
     *
     * @return int
     */
    protected function getTimestamp(\DateTimeInterface|string $until = 'now'): int
    {
        if (is_numeric($until)) {
            return (int) $until;
        }

        if ($until instanceof \DateTimeInterface) {
            return $until->getTimestamp();
        }

        return (int) strtotime(empty($until) ? 'now' : $until);
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

    protected function setDefaultTimezone(string $timezone = null): void
    {
        $this->defaultTimezone = $timezone;
    }

    protected function getDefaultTimezone(): ?string
    {
        return $this->defaultTimezone;
    }

    protected function resolveTimezone(?string $timezone): string
    {
        return $timezone ?? $this->defaultTimezone ?? date_default_timezone_get();
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
}
