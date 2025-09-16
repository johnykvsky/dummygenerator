<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Number;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(NumberExtensionInterface::class, Number::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testNumberBetween(): void
    {
        $number = $this->generator->numberBetween(min: 25, max: 82);

        self::assertTrue($number >= 25 && $number <= 82);
    }

    public function testRandomDigit(): void
    {
        $number = $this->generator->randomDigit();

        self::assertTrue($number >= 0 && $number <= 9);
    }

    public function testRandomDigitNot(): void
    {
        $number = $this->generator->randomDigitNot(except: 5);
        self::assertNotSame($number, 5);
    }

    public function testRandomDigitNotZero(): void
    {
        $number = $this->generator->randomDigitNotZero();

        self::assertNotSame($number, 0);
    }

    public function testRandomFloat(): void
    {
        $number = $this->generator->randomFloat(nbMaxDecimals: 3, min: 12.83, max: 26.45);

        self::assertTrue($number >= 12.83 && $number <= 26.45);
        $parts = explode('.', (string) $number);
        self::assertTrue(strlen($parts[1]) <= 3);
    }

    public function testRandomFloatRandomDecimals(): void
    {
        $number = $this->generator->randomFloat(nbMaxDecimals: null, min: 12.83, max: 26.45);

        self::assertTrue($number >= 12.83 && $number <= 26.45);
        $parts = explode('.', (string) $number);
        self::assertTrue(strlen($parts[1]) !== 0);
    }

    public function testRandomNumber(): void
    {
        $number = $this->generator->randomNumber(nbDigits: 3, strict: true);

        self::assertTrue($number >= 100 && $number <= 999);
    }

    public function testRandomNumberRandomDigits(): void
    {
        $number = $this->generator->randomNumber(nbDigits: null, strict: false);

        self::assertTrue($number >= 0);
    }

    public function testBoolean(): void
    {
        self::assertTrue($this->generator->boolean(chanceOfGettingTrue: 100));
        self::assertFalse($this->generator->boolean(chanceOfGettingTrue: 0));
    }

}
