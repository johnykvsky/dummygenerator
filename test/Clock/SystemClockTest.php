<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Clock;

use DummyGenerator\Clock\SystemClock;
use PHPUnit\Framework\TestCase;

class SystemClockTest extends TestCase
{
    public function testSystemClock(): void
    {
        date_default_timezone_set('UTC');
        $clock = new SystemClock();

        self::assertEquals('UTC', $clock->now()->getTimezone()->getName());
        self::assertEquals('UTC', $clock->timezone()->getName());
    }

    public function testSystemClockStringTimezone(): void
    {
        date_default_timezone_set('UTC');
        $clock = new SystemClock(timezone: 'Europe/Berlin');

        self::assertEquals('Europe/Berlin', $clock->now()->getTimezone()->getName());
        self::assertEquals('Europe/Berlin', $clock->timezone()->getName());
    }

    public function testSystemClockObjectTimezone(): void
    {
        date_default_timezone_set('UTC');
        $clock = new SystemClock(timezone: new \DateTimeZone('Europe/London'));

        self::assertEquals('Europe/London', $clock->now()->getTimezone()->getName());
        self::assertEquals('Europe/London', $clock->timezone()->getName());
    }
}
