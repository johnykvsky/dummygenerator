<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Clock;

use DummyGenerator\Clock\SystemClock;
use PHPUnit\Framework\Attributes\DataProvider;
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

    /**
     * Test that SystemClock throws exception for invalid timezone string.
     *
     * @group clock
     * @group edge-case
     */
    public function testSystemClockThrowsExceptionForInvalidTimezone(): void
    {
        $this->expectException(\DateInvalidTimeZoneException::class);
        new SystemClock(timezone: 'Invalid/Timezone');
    }

    /**
     * Test that SystemClock uses default timezone when null is provided.
     *
     * @group clock
     */
    public function testSystemClockUsesDefaultTimezoneWhenNull(): void
    {
        date_default_timezone_set('America/New_York');
        $clock = new SystemClock(timezone: null);

        self::assertEquals('America/New_York', $clock->timezone()->getName());

        // Reset to UTC
        date_default_timezone_set('UTC');
    }

    /**
     * Test that now() returns current time as DateTimeImmutable.
     *
     * @group clock
     */
    public function testNowReturnsDateTimeImmutable(): void
    {
        $clock = new SystemClock();
        $now = $clock->now();

        self::assertInstanceOf(\DateTimeImmutable::class, $now);
    }

    /**
     * Test that consecutive calls to now() return different times.
     *
     * @group clock
     */
    public function testConsecutiveCallsToNowReturnDifferentTimes(): void
    {
        $clock = new SystemClock();
        $time1 = $clock->now();
        usleep(10000); // 10ms delay
        $time2 = $clock->now();

        self::assertGreaterThanOrEqual($time1->getTimestamp(), $time2->getTimestamp());
    }

    /**
     * Test that timezone() returns DateTimeZone object.
     *
     * @group clock
     */
    public function testTimezoneReturnsDateTimeZone(): void
    {
        $clock = new SystemClock(timezone: 'Europe/Paris');

        self::assertInstanceOf(\DateTimeZone::class, $clock->timezone());
    }

    /**
     * Test SystemClock with various timezone strings.
     *
     * @group clock
     *
     * @param string $timezone Timezone identifier
     */
    #[DataProvider('timezoneProvider')]
    public function testSystemClockWithVariousTimezones(string $timezone): void
    {
        $clock = new SystemClock(timezone: $timezone);

        self::assertEquals($timezone, $clock->timezone()->getName());
        self::assertEquals($timezone, $clock->now()->getTimezone()->getName());
    }

    /**
     * Data provider for timezone tests.
     *
     * @return array<string, array{0: string}>
     */
    public static function timezoneProvider(): array
    {
        return [
            'UTC' => ['UTC'],
            'New York' => ['America/New_York'],
            'Tokyo' => ['Asia/Tokyo'],
            'London' => ['Europe/London'],
            'Sydney' => ['Australia/Sydney'],
            'Los Angeles' => ['America/Los_Angeles'],
        ];
    }
}
