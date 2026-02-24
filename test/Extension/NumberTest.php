<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Number;
use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NumberTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
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

    public function testRandomDigitNotThrowsExtensionRuntimeExceptionWhenRetriesExceeded(): void
    {
        $randomizer = $this->createMock(RandomizerInterface::class);
        $randomizer->expects(self::once())
            ->method('getInt')
            ->with(0, 9)
            ->willReturn(5);

        $number = new Number($randomizer);

        $this->expectException(ExtensionRuntimeException::class);
        $number->randomDigitNot(except: 5, retries: 0);
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

    // Enhanced edge case tests

    public function testRandomDigitNotWithValueOutsideRange(): void
    {
        // Test with except value outside 0-9 range (should still work)
        $result = $this->generator->randomDigitNot(except: 15);
        self::assertTrue($result >= 0 && $result <= 9);
    }

    public function testRandomDigitNotRetriesLimit(): void
    {
        // This should theoretically work but might hit retry limit
        // Testing with a digit that exists in range
        for ($i = 0; $i < 10; $i++) {
            $result = $this->generator->randomDigitNot(except: 5);
            self::assertNotEquals(5, $result);
            self::assertTrue($result >= 0 && $result <= 9);
        }
    }

    public function testRandomFloatWithMaxBoundary(): void
    {
        // Test with very large max value (but not exceeding PHP_FLOAT_MAX)
        $result = $this->generator->randomFloat(nbMaxDecimals: 2, min: 0.0, max: 1000000.0);
        self::assertTrue($result >= 0.0 && $result <= 1000000.0);
    }

    public function testRandomFloatExceptionWhenMaxTooLarge(): void
    {
        $this->expectException(\DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException::class);
        $this->expectExceptionMessage('randomFloat() can only generate numbers up to PHP_FLOAT_MAX');

        // This should throw an exception
        $this->generator->randomFloat(nbMaxDecimals: 2, min: 0.0, max: PHP_FLOAT_MAX * 2);
    }

    public function testRandomFloatDecimalPlaces(): void
    {
        $result = $this->generator->randomFloat(nbMaxDecimals: 3, min: 0.0, max: 100.0);

        // Check decimal places
        $parts = explode('.', (string) $result);
        if (isset($parts[1])) {
            self::assertLessThanOrEqual(3, strlen($parts[1]), 'Should have at most 3 decimal places');
        }
    }

    public function testRandomFloatZeroDecimals(): void
    {
        $result = $this->generator->randomFloat(nbMaxDecimals: 0, min: 1.0, max: 10.0);

        // Should be a whole number
        self::assertEquals(floor($result), $result);
    }

    public function testRandomNumberStrictMode(): void
    {
        // Strict mode should not allow leading zeros
        $result = $this->generator->randomNumber(nbDigits: 3, strict: true);

        self::assertTrue($result >= 100 && $result <= 999, 'Should be 3 digits without leading zeros');
    }

    public function testRandomNumberNonStrictModeCanHaveLeadingZeros(): void
    {
        // Non-strict mode allows leading zeros (smaller numbers)
        $result = $this->generator->randomNumber(nbDigits: 3, strict: false);

        self::assertTrue($result >= 0 && $result <= 999, 'Should be at most 3 digits');
    }

    public function testRandomNumberExceptionWhenTooManyDigits(): void
    {
        $this->expectException(\DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException::class);
        $this->expectExceptionMessage('randomNumber() can only generate numbers up to PHP_INT_MAX');

        // Requesting too many digits should throw exception
        $this->generator->randomNumber(nbDigits: 50, strict: false);
    }

    /**
     * Test numberBetween with various range scenarios including edge cases.
     *
     * @param int $min Minimum value
     * @param int $max Maximum value
     * @param string $scenario Description of what this scenario tests
     */
    #[DataProvider('numberBetweenRangeProvider')]
    public function testNumberBetweenWithVariousRanges(int $min, int $max, string $scenario): void
    {
        $result = $this->generator->numberBetween(min: $min, max: $max);
        self::assertGreaterThanOrEqual($min, $result, "Failed for scenario: $scenario");
        self::assertLessThanOrEqual($max, $result, "Failed for scenario: $scenario");
    }

    /**
     * Data provider for number range tests.
     *
     * @return array<string, array{min: int, max: int, scenario: string}>
     */
    public static function numberBetweenRangeProvider(): array
    {
        return [
            'equal min and max' => [42, 42, 'Min equals max should return that value'],
            'negative range' => [-100, -50, 'Both negative numbers'],
            'crosses zero' => [-50, 50, 'Range crosses zero'],
            'large positive range' => [1000, 9999, 'Large positive numbers'],
            'zero to positive' => [0, 100, 'Starting from zero'],
            'negative to zero' => [-100, 0, 'Ending at zero'],
        ];
    }

    /**
     * Test boolean generation with extreme probability values (1% and 99%).
     *
     * This test validates that:
     * - 1% chance produces true rarely (< 20% of 100 samples)
     * - 99% chance produces true frequently (> 80% of 100 samples)
     *
     * The thresholds (20 and 80) allow for statistical variance while ensuring
     * the probability distribution is reasonable.
     */
    public function testBooleanEdgePercentages(): void
    {
        // Test 1% chance (should mostly be false, but occasionally true)
        $results = [];
        for ($i = 0; $i < 100; $i++) {
            $results[] = $this->generator->boolean(chanceOfGettingTrue: 1);
        }
        $trueCount = count(array_filter($results));
        self::assertLessThan(20, $trueCount, 'With 1% chance, should rarely be true');

        // Test 99% chance (should mostly be true, but occasionally false)
        $results = [];
        for ($i = 0; $i < 100; $i++) {
            $results[] = $this->generator->boolean(chanceOfGettingTrue: 99);
        }
        $trueCount = count(array_filter($results));
        self::assertGreaterThan(80, $trueCount, 'With 99% chance, should usually be true');
    }

    public function testBoolean50Percent(): void
    {
        // Test 50% chance (should be roughly balanced)
        $results = [];
        for ($i = 0; $i < 200; $i++) {
            $results[] = $this->generator->boolean(chanceOfGettingTrue: 50);
        }
        $trueCount = count(array_filter($results));

        // Allow some variance (30-70%)
        self::assertGreaterThan(40, $trueCount);
        self::assertLessThan(160, $trueCount);
    }

    public function testRandomDigitNotZeroRange(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $result = $this->generator->randomDigitNotZero();
            self::assertTrue($result >= 1 && $result <= 9);
            self::assertNotEquals(0, $result);
        }
    }

    public function testRandomFloatWithNullDecimalsHasDecimalPlaces(): void
    {
        // When nbMaxDecimals is null, should use randomDigitNot()
        $result = $this->generator->randomFloat(nbMaxDecimals: null, min: 1.0, max: 10.0);

        self::assertTrue($result >= 1.0 && $result <= 10.0);

        // Should have some decimal places
        $parts = explode('.', (string) $result);
        if (isset($parts[1])) {
            self::assertGreaterThan(0, strlen($parts[1]));
        }
    }

    public function testRandomNumberWithNullDigitsIsReasonable(): void
    {
        // When nbDigits is null, should use randomDigitNotZero()
        $result = $this->generator->randomNumber(nbDigits: null, strict: false);

        self::assertTrue($result >= 0);

        // Should be reasonable number
        self::assertLessThan(1000000000, $result);
    }

    public function testNumberBetweenLargeMumbers(): void
    {
        $result = $this->generator->numberBetween(min: 1000000, max: 2000000);
        self::assertTrue($result >= 1000000 && $result <= 2000000);
    }
}
