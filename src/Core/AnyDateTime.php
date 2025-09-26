<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DateInterval;
use DateTimeInterface;
use DummyGenerator\Definitions\Enum\DatePeriodEnum;
use DummyGenerator\Definitions\Extension\AnyDateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ClockAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ClockAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

class AnyDateTime implements AnyDateTimeExtensionInterface, RandomizerAwareExtensionInterface, ClockAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;
    use ClockAwareExtensionTrait;

    /**
     * @param \DateTime|\DateTimeImmutable|string $date
     * @throws \DateInvalidOperationException
     * @throws \DateMalformedIntervalStringException
     * @throws \DateMalformedStringException
     */
    public function anyDate(DateTimeInterface|string|null $date = null, DateInterval|string $interval = 'P10Y', DatePeriodEnum $period = DatePeriodEnum::ANY_DATE): \DateTimeInterface
    {
        if ($date === null) {
            $date = $this->clock->now();
        }

        if (is_string($date)) {
            $date = new \DateTimeImmutable($date, $this->clock->timezone());
        }

        if (is_string($interval)) {
            $interval = new DateInterval($interval);
        }

        if ($period === DatePeriodEnum::PAST_DATE) {
            $until = $date;
            $from = $date->sub($interval)->setTime(0, 0, 1);
        } elseif ($period === DatePeriodEnum::FUTURE_DATE) {
            $from = $date;
            $until = $date->add($interval)->setTime(23, 59, 59);
        } else {
            $from = $date->sub($interval)->setTime(0, 0, 1);
            $until = $date->add($interval)->setTime(23, 59, 59);
        }

        return $this->anyDateBetween($from, $until);
    }

    /** @param \DateTime|\DateTimeImmutable|null $from */
    public function anyDateBetween(?\DateTimeInterface $from = null, ?\DateTimeInterface $until = null): \DateTimeInterface
    {
        if ($from === null) {
            $from = $this->clock->now()->sub(new DateInterval('P5Y'));
        }

        if ($until === null) {
            $until = $from->add(new DateInterval('P5Y'));
        }

        // remember to use same timezone for both, unless you explicitly want it different
        $start = $from->getTimestamp();
        $end = $until->getTimestamp();

        if ($start > $end) {
            throw new ExtensionArgumentException('"from" must be anterior to "until".');
        }

        $timestamp = $this->randomizer->getInt($start, $end);

        return $this->getTimestampDateTime($timestamp);
    }

    public function anyTimezone(?string $country = null): string
    {
        if ($country === null) {
            return $this->randomizer->randomElement(\DateTimeZone::listIdentifiers());
        }

        try {
            $identifiers = \DateTimeZone::listIdentifiers(\DateTimeZone::PER_COUNTRY, $country);
        } catch (\Throwable $e) {
            throw new ExtensionArgumentException(sprintf('Invalid country code for timezone list: %s', $country), $e->getCode(), $e);
        }

        return $this->randomizer->randomElement($identifiers);
    }

    /**
     * Get a DateTimeImmutable created based on a POSIX-timestamp.
     *
     * @param int $timestamp the UNIX / POSIX-compatible timestamp
     * @throws \DateMalformedStringException
     */
    protected function getTimestampDateTime(int $timestamp): \DateTimeInterface
    {
        return new \DateTimeImmutable('@' . $timestamp, $this->clock->timezone());
    }
}
