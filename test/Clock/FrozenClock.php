<?php

namespace DummyGenerator\Test\Clock;

use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeZone;
use DummyGenerator\Clock\SystemClockInterface;

class FrozenClock implements SystemClockInterface
{
    private DateTimeImmutable $now;
    private DateTimeZone $timezone;

    /**
     * @throws DateInvalidTimeZoneException
     */
    public function __construct(DateTimeImmutable $now, DateTimeZone|string|null $timezone = null)
    {
        if ($timezone === null) {
            $timezone = date_default_timezone_get();
        }

        if (is_string($timezone)) {
            $timezone = new DateTimeZone($timezone);
        }

        $this->timezone = $timezone;
        $this->now = $now;
    }

    public function now(): \DateTimeImmutable
    {
        return $this->now;
    }

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }
}