<?php

declare(strict_types = 1);

namespace DummyGenerator\Clock;

use DateInvalidTimeZoneException;
use DateTimeImmutable;
use DateTimeZone;

class SystemClock implements SystemClockInterface
{
    private DateTimeZone $timezone;

    /** @throws DateInvalidTimeZoneException */
    public function __construct(\DateTimeZone|string|null $timezone = null)
    {
        if ($timezone === null) {
            $timezone = date_default_timezone_get();
        }

        if (is_string($timezone)) {
            $timezone = new DateTimeZone($timezone);
        }

        $this->timezone = $timezone;
    }

    public function now(): \DateTimeImmutable
    {
        return new DateTimeImmutable('now', $this->timezone);
    }

    public function timezone(): DateTimeZone
    {
        return $this->timezone;
    }
}
