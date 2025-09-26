<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DateInterval;
use DateTimeImmutable;
use DateTimeZone;
use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\AnyDateTime;
use DummyGenerator\Definitions\Enum\DatePeriodEnum;
use DummyGenerator\Definitions\Extension\AnyDateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Test\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;
use Psr\Clock\ClockInterface;

class AnyDateTimeTest extends TestCase
{
    private DummyGenerator $generator;
    private ClockInterface $clock;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(AnyDateTimeExtensionInterface::class, AnyDateTime::class);
        date_default_timezone_set('UTC');
        $this->clock = new FrozenClock(new DateTimeImmutable('2025-08-12', new DateTimeZone('UTC')));
        $this->generator = new DummyGenerator(container: $container, clock: $this->clock);
    }

    public function testAnyDate(): void
    {
        $date = $this->generator->anyDate(interval: new DateInterval('P1D'), period: DatePeriodEnum::ANY_DATE);
        $dateFrom = (new DateTimeImmutable('2025-08-11', new DateTimeZone('UTC')))->setTime(0,0,1);
        $dateTo = (new DateTimeImmutable('2025-08-13', new DateTimeZone('UTC')))->setTime(23,59,59);

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyStringDate(): void
    {
        $date = $this->generator->anyDate(date: '2025-08-12', interval: 'P1D', period: DatePeriodEnum::ANY_DATE);
        $dateFrom = (new DateTimeImmutable('2025-08-11', new DateTimeZone('UTC')))->setTime(0,0,1);
        $dateTo = (new DateTimeImmutable('2025-08-13', new DateTimeZone('UTC')))->setTime(23,59,59);

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyPastDate(): void
    {
        $date = $this->generator->anyDate(interval: new DateInterval('P1D'), period: DatePeriodEnum::PAST_DATE);
        $dateFrom = (new DateTimeImmutable('2025-08-11', new DateTimeZone('UTC')))->setTime(0, 0, 1);
        $dateTo = (new DateTimeImmutable('2025-08-12', new DateTimeZone('UTC')))->setTime(23, 59, 59);

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyFutureDate(): void
    {
        $date = $this->generator->anyDate(interval: new DateInterval('P1D'), period: DatePeriodEnum::FUTURE_DATE);
        $dateFrom = (new DateTimeImmutable('2025-08-12', new DateTimeZone('UTC')))->setTime(0, 0, 1);
        $dateTo = (new DateTimeImmutable('2025-08-13', new DateTimeZone('UTC')))->setTime(23, 59, 59);

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyDateBetween(): void
    {
        $dateFrom = (new DateTimeImmutable('2025-08-14', new DateTimeZone('UTC')))->setTime(0, 0, 1);
        $dateTo = (new DateTimeImmutable('2025-08-16', new DateTimeZone('UTC')))->setTime(23, 59, 59);
        $date = $this->generator->anyDateBetween($dateFrom, $dateTo);

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyDateBetweenWithNoFrom(): void
    {
        $dateTo = (new DateTimeImmutable('2025-08-16', new DateTimeZone('UTC')))->setTime(23, 59, 59);
        $date = $this->generator->anyDateBetween(until: $dateTo);
        $dateFrom = $dateTo->sub(new DateInterval('P5Y'));

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyDateBetweenWithNoUntil(): void
    {
        $dateFrom = (new DateTimeImmutable('2025-08-14', new DateTimeZone('UTC')))->setTime(0, 0, 1);
        $date = $this->generator->anyDateBetween(from: $dateFrom);
        $dateTo = $dateFrom->add(new DateInterval('P5Y'));

        self::assertTrue($date > $dateFrom && $date < $dateTo);
    }

    public function testAnyDateBetweenException(): void
    {
        $dateFrom = (new DateTimeImmutable('2025-08-14', new DateTimeZone('UTC')))->setTime(0, 0, 1);
        $dateTo = (new DateTimeImmutable('2025-08-16', new DateTimeZone('UTC')))->setTime(23, 59, 59);
        self::expectException(ExtensionArgumentException::class);
        $this->generator->anyDateBetween($dateTo, $dateFrom);
    }

    public function testAnyTimezone(): void
    {
        self::assertNotEmpty($this->generator->anyTimezone());
    }

    public function testAnyTimezoneForCountry(): void
    {
        self::assertEquals('Europe/Warsaw',$this->generator->anyTimezone('PL'));
    }

    public function testAnyTimezoneForInvalidCountry(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid country code for timezone list: PLA');
        $this->generator->anyTimezone('PLA');
    }
}
