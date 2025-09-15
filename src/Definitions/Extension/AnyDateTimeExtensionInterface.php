<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

use DummyGenerator\Definitions\Enum\DatePeriodEnum;

interface AnyDateTimeExtensionInterface extends ExtensionInterface
{
    /**
     * Get a DateTimeInterface object a random date between `$date` and given `$interval` in given `$period`.
     *
     * @param \DateTimeInterface|null     $date      Date used for range generation (default today)
     * @param \DateInterval               $interval  Interval to be applied for date
     * @param DatePeriodEnum              $period    If interval should be applied in the past, in the future or for both
     *
     * @example DateTimeImmutable('2005-08-16 20:39:21')
     */
    public function anyDate(?\DateTimeInterface $date = null, \DateInterval $interval = new \DateInterval('P10Y'), DatePeriodEnum $period = DatePeriodEnum::ANY_DATE): \DateTimeInterface;

    /**
     * Get a DateTimeInterface object a random date between `$from` and `$until`.
     *
     * @param \DateTimeInterface     $from     Start date for date generation
     * @param \DateTimeInterface     $until    End date for date generation
     *
     * @example DateTimeImmutable('2005-08-16 20:39:21')
     */
    public function anyDateBetween(\DateTimeInterface $from, \DateTimeInterface $until): \DateTimeInterface;

    /**
     * Get a random timezone, uses `\DateTimeZone::listIdentifiers()` internally.
     *
     * @example 'Europe/Rome'
     */
    public function anyTimezone(): string;
}
