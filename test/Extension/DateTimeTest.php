<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DateTimeImmutable;
use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\DateTime;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    private DummyGenerator $generator;
    private \DateTimeImmutable $now;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(DateTimeExtensionInterface::class, DateTime::class);
        $this->generator = new DummyGenerator($container);
        date_default_timezone_set('UTC');
        $this->now = new \DateTimeImmutable(timezone: new \DateTimeZone('UTC'));
    }

    public function testDateTime(): void
    {
        $date = $this->generator->dateTime(until: 'yesterday', timezone: 'UTC');

        self::assertTrue($date <= $this->now->sub(new \DateInterval('P1D')));
    }

    public function testDateTimeAD(): void
    {
        $adDate = new \DateTimeImmutable('0000-01-01 00:00:01', new \DateTimeZone('UTC'));
        $date = $this->generator->dateTimeAD(until: 'yesterday', timezone: 'UTC');

        self::assertTrue($date >= $adDate && $date <= $this->now->sub(new \DateInterval('P1D')));
    }

    public function testDateTimeBetween(): void
    {
        $date1 = new \DateTimeImmutable('2021-04-12 10:34:23', new \DateTimeZone('UTC'));
        $date2 = new \DateTimeImmutable('2022-05-11 14:08:46', new \DateTimeZone('UTC'));

        $date = $this->generator->dateTimeBetween(from: $date1, until: $date2, timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testInvalidDateTimeBetween(): void
    {
        $date1 = new \DateTimeImmutable('2021-04-12 10:34:23', new \DateTimeZone('UTC'));
        $date2 = new \DateTimeImmutable('2022-05-11 14:08:46', new \DateTimeZone('UTC'));

        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('"$from" must be anterior to "$until".');
        $this->generator->dateTimeBetween(from: $date2, until: $date1, timezone: 'UTC');

    }

    public function testDateTimeInterval(): void
    {
        $date1 = new \DateTimeImmutable('2021-04-12 10:34:23', new \DateTimeZone('UTC'));
        $date2 = $date1->add(new \DateInterval('P10D'));

        $date = $this->generator->dateTimeInInterval(from: $date1, interval: new \DateInterval('P10D'), timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testInvalidTimezone(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('"$timezone" is not a valid timezone.');

        $this->generator->dateTimeInInterval(from: new DateTimeImmutable(), interval: new \DateInterval('P10D'), timezone: 'ABC');
    }

    public function testDateTimeThisWeek(): void
    {
        $date1 = $this->getMondayThisWeek();
        $date2 = $this->getSundayThisWeek();

        $date = $this->generator->dateTimeThisWeek(until: $date2, timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testDateTimeThisMonth(): void
    {
        $date1 = new \DateTimeImmutable($this->now->format('Y-m') . '-01', new \DateTimeZone('UTC'));
        $date2 = new \DateTimeImmutable($this->now->format('Y-m-t'), new \DateTimeZone('UTC'));

        $date = $this->generator->dateTimeThisMonth(until: $date2, timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testDateTimeThisYear(): void
    {
        $date1 = new \DateTimeImmutable($this->now->format('Y') . '-01-01', new \DateTimeZone('UTC'));
        $date2 = new \DateTimeImmutable($this->now->format('Y' . '-12-31'), new \DateTimeZone('UTC'));

        $date = $this->generator->dateTimeThisYear(until: $date2, timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testDateTimeThisDecade(): void
    {
        $year = floor(date('Y') / 10) * 10;
        $date1 = new \DateTimeImmutable($year . '-01-01', new \DateTimeZone('UTC'));
        $date2 = new \DateTimeImmutable($year + 10 . '-12-31', new \DateTimeZone('UTC'));

        $date = $this->generator->dateTimeThisDecade(until: $date2, timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testDateTimeThisCentury(): void
    {
        $year = floor(date('Y') / 100) * 100;
        $date1 = new \DateTimeImmutable($year . '-01-01', new \DateTimeZone('UTC'));
        $date2 = new \DateTimeImmutable($year + 100 . '-12-31', new \DateTimeZone('UTC'));

        $date = $this->generator->dateTimeThisCentury(until: $date2, timezone: 'UTC');

        self::assertTrue($date >= $date1 && $date <= $date2);
    }

    public function testDate(): void
    {
        $date = $this->generator->date(format: 'Y-m-d H:i:s', until: $this->now);
        $date2 = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));

        self::assertTrue($date2 <= $this->now);
    }

    public function testTime(): void
    {
        $date = $this->generator->time(format: 'Y-m-d H:i:s', until: $this->now);
        $date2 = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));

        self::assertTrue($date2 <= $this->now);
    }

    public function testUnixTime(): void
    {
        $time = $this->generator->unixTime(until: $this->now);

        self::assertTrue($time <= $this->now->getTimestamp());
    }

    public function testTimestamp()
    {
        $time = $this->now->getTimestamp() - 3600;
        self::assertTrue($time <= $this->generator->dateTimeBetween(from: (string) $time, timezone: 'UTC')->format('U'));
    }

    public function testIso8601(): void
    {
        $date = $this->generator->iso8601(until: $this->now);
        $date2 = new \DateTimeImmutable($date, new \DateTimeZone('UTC'));

        self::assertTrue($date2 <= $this->now);
    }

    public function testAmPm(): void
    {
        self::assertContains($this->generator->amPm($this->now), ['am', 'pm']);
    }

    public function testDayOfMonth(): void
    {
        $day = $this->generator->dayOfMonth($this->now);

        self::assertIsNumeric($day);
        self::assertTrue((int) $day >= 1 && (int) $day <= 31);
    }

    public function testDayOfWeek(): void
    {
        $day = $this->generator->dayOfWeek($this->now);

        self::assertContains($day, ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday']);
    }

    public function testMonth(): void
    {
        $month = $this->generator->month($this->now);

        self::assertIsNumeric($month);
        self::assertTrue((int) $month >= 1 && (int) $month <= 12);
    }

    public function testMonthName(): void
    {
        $month = $this->generator->monthName($this->now);

        self::assertContains($month, [
            'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December',
        ]);
    }

    public function testYear(): void
    {
        $year = $this->generator->year($this->now);

        self::assertIsNumeric($year);
        self::assertTrue((int) $year <= (int) $this->now->format('Y'));
    }

    public function testCentury(): void
    {
        self::assertNotEmpty($this->generator->century());
    }

    public function testTimezone(): void
    {
        self::assertNotEmpty($this->generator->timezone());
    }

    private function getMondayThisWeek(): \DateTimeImmutable
    {
        $date = clone $this->now;

        if ($date->format('w') === '1') {
            return $date->setTime(0, 0, 0);
        }

        return $date->modify('last monday')->setTime(0, 0, 0);
    }

    private function getSundayThisWeek(): \DateTimeImmutable
    {
        $date = clone $this->now;

        if ($date->format('w') === '0') {
            return $date->setTime(23, 59, 59);
        }

        return $date->modify('next sunday')->setTime(23, 59, 59);
    }
}
