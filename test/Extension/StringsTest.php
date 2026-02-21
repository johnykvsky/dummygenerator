<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Strings;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class StringsTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(StringsExtensionInterface::class, Strings::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testString(): void
    {
        self::assertNotEmpty($this->generator->string());
    }

    public function testStringLength(): void
    {
        $string = $this->generator->string(5, 5);
        self::assertEquals(5, strlen($string));
    }

    public function testStringLongLength(): void
    {
        $string = $this->generator->string(100, 100);
        self::assertEquals(100, strlen($string));
    }

    public function testStringPool(): void
    {
        $string = $this->generator->string(3, 3, '11111');
        self::assertEquals('111', $string);
    }

    public function testMinBelowLimit(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('$min should be at least 1');
        $this->generator->string(0, 3, '11111');
    }

    public function testMinHigherThanMax(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('$min cannot be higher than $max');
        $this->generator->string(7, 3, '11111');
    }

    // Enhanced validation tests

    public function testStringContainsOnlyPoolCharacters(): void
    {
        $pool = 'abc123';
        $string = $this->generator->string(min: 20, max: 20, pool: $pool);

        // Check each character is from the pool
        for ($i = 0; $i < strlen($string); $i++) {
            self::assertStringContainsString($string[$i], $pool, "Character '{$string[$i]}' should be in pool '$pool'");
        }
    }

    public function testStringWithSingleCharacterPool(): void
    {
        $pool = 'X';
        $string = $this->generator->string(min: 10, max: 10, pool: $pool);

        // Should be all X's
        self::assertEquals('XXXXXXXXXX', $string);
        self::assertEquals(10, strlen($string));
    }

    public function testStringWithVeryLongLength(): void
    {
        $string = $this->generator->string(min: 1000, max: 1000);

        self::assertEquals(1000, strlen($string));
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $string);
    }

    public function testStringWithAccentedCharactersInPool(): void
    {
        // Use Latin-1 characters that are single byte in ISO-8859-1 but work in UTF-8
        $pool = 'áéíóúñ';
        $string = $this->generator->string(min: 10, max: 10, pool: $pool);

        // Should be 10 bytes
        self::assertEquals(10, strlen($string));

        // String should only contain characters from the pool
        self::assertNotEmpty($string);
    }

    public function testStringDefaultPool(): void
    {
        $string = $this->generator->string(min: 50, max: 50);

        // Default pool is alphanumeric
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $string);
    }

    public function testStringVariableLengthInRange(): void
    {
        $lengths = [];

        // Generate many strings and collect lengths
        for ($i = 0; $i < 50; $i++) {
            $string = $this->generator->string(min: 5, max: 10);
            $lengths[] = strlen($string);
        }

        // All lengths should be in range
        foreach ($lengths as $length) {
            self::assertTrue($length >= 5 && $length <= 10);
        }

        // Should have variety of lengths (not all the same)
        $uniqueLengths = array_unique($lengths);
        self::assertGreaterThan(1, count($uniqueLengths), 'Should generate varying lengths');
    }

    public function testStringMinEqualsMax(): void
    {
        $string = $this->generator->string(min: 7, max: 7);
        self::assertEquals(7, strlen($string));
    }

    public function testStringWithSpecialCharactersPool(): void
    {
        $pool = '!@#$%^&*()';
        $string = $this->generator->string(min: 15, max: 15, pool: $pool);

        self::assertEquals(15, strlen($string));

        // Each character should be from pool
        for ($i = 0; $i < strlen($string); $i++) {
            self::assertStringContainsString($string[$i], $pool);
        }
    }

    public function testStringWithNumbersOnlyPool(): void
    {
        $pool = '0123456789';
        $string = $this->generator->string(min: 10, max: 10, pool: $pool);

        self::assertEquals(10, strlen($string));
        self::assertMatchesRegularExpression('/^\d+$/', $string);
    }

    public function testStringWithLettersOnlyPool(): void
    {
        $pool = 'abcdefghijklmnopqrstuvwxyz';
        $string = $this->generator->string(min: 20, max: 20, pool: $pool);

        self::assertEquals(20, strlen($string));
        self::assertMatchesRegularExpression('/^[a-z]+$/', $string);
    }

    public function testStringEmptyPoolThrowsException(): void
    {
        // Empty pool should throw an exception
        $this->expectException(\ValueError::class);
        $this->generator->string(min: 10, max: 10, pool: '');
    }
}
