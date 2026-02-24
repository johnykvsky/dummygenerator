<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Randomizer;

use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use PHPUnit\Framework\TestCase;

class XoshiroRandomizerTest extends TestCase
{
    public function testSeedReproducibility(): void
    {
        $randomizer1 = new XoshiroRandomizer(seed: 42);
        $randomizer2 = new XoshiroRandomizer(seed: 42);

        $results1 = [];
        $results2 = [];

        for ($i = 0; $i < 10; $i++) {
            $results1[] = $randomizer1->getInt(1, 100);
            $results2[] = $randomizer2->getInt(1, 100);
        }

        self::assertEquals($results1, $results2, 'Same seed should produce same sequence');
    }

    public function testDifferentSeedsProduceDifferentSequences(): void
    {
        $randomizer1 = new XoshiroRandomizer(seed: 42);
        $randomizer2 = new XoshiroRandomizer(seed: 123);

        $results1 = [];
        $results2 = [];

        for ($i = 0; $i < 10; $i++) {
            $results1[] = $randomizer1->getInt(1, 1000);
            $results2[] = $randomizer2->getInt(1, 1000);
        }

        self::assertNotEquals($results1, $results2, 'Different seeds should produce different sequences');
    }

    public function testGetInt(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $min = 5;
        $max = 50;

        for ($i = 0; $i < 100; $i++) {
            $number = $randomizer->getInt($min, $max);
            self::assertTrue($number >= $min && $number <= $max);
        }
    }

    public function testGetFloat(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $min = 5.5;
        $max = 150.75;

        for ($i = 0; $i < 100; $i++) {
            $number = $randomizer->getFloat($min, $max);
            self::assertTrue($number >= $min && $number <= $max);
        }
    }

    public function testGetBool(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);

        $results = [];
        for ($i = 0; $i < 100; $i++) {
            $results[] = $randomizer->getBool(50);
        }

        $trueCount = count(array_filter($results, fn($v) => $v === true));
        $falseCount = count(array_filter($results, fn($v) => $v === false));

        self::assertGreaterThan(0, $trueCount);
        self::assertGreaterThan(0, $falseCount);
        self::assertEquals(100, $trueCount + $falseCount);
    }

    public function testGetBoolAlwaysTrue(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);

        for ($i = 0; $i < 10; $i++) {
            self::assertTrue($randomizer->getBool(100));
        }
    }

    public function testGetBoolAlwaysFalse(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);

        for ($i = 0; $i < 10; $i++) {
            self::assertFalse($randomizer->getBool(0));
        }
    }

    public function testGetBytes(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);

        $bytes = $randomizer->getBytes(16);

        self::assertEquals(16, strlen($bytes));
    }

    public function testGetBytesFromString(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $source = 'abcdefghijklmnopqrstuvwxyz';

        $result = $randomizer->getBytesFromString($source, 8);

        self::assertEquals(8, strlen($result));

        // Verify all characters are from source string
        for ($i = 0; $i < strlen($result); $i++) {
            self::assertStringContainsString($result[$i], $source);
        }
    }

    public function testRandomLetter(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);

        for ($i = 0; $i < 50; $i++) {
            $letter = $randomizer->randomLetter();
            $ord = ord($letter);
            self::assertTrue($ord >= 97 && $ord <= 122, "Letter '$letter' should be lowercase a-z");
        }
    }

    public function testRandomElement(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $elements = ['apple', 'banana', 'cherry', 'date'];

        for ($i = 0; $i < 20; $i++) {
            $element = $randomizer->randomElement($elements);
            self::assertContains($element, $elements);
        }
    }

    public function testRandomKey(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $array = ['first' => 1, 'second' => 2, 'third' => 3];

        for ($i = 0; $i < 20; $i++) {
            $key = $randomizer->randomKey($array);
            self::assertContains($key, array_keys($array));
        }
    }

    public function testShuffleElements(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $array = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];

        $shuffled = $randomizer->shuffleElements($array);

        self::assertCount(count($array), $shuffled);

        // All elements should still be present
        sort($array);
        sort($shuffled);
        self::assertEquals($array, $shuffled);
    }

    public function testRandomElements(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $array = ['a', 'b', 'c', 'd', 'e'];

        $result = $randomizer->randomElements($array, 3);

        self::assertCount(3, $result);
        foreach ($result as $element) {
            self::assertContains($element, $array);
        }
    }

    public function testRandomElementsUnique(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $array = ['a', 'b', 'c', 'd', 'e'];

        $result = $randomizer->randomElements($array, 3, true);

        self::assertCount(3, $result);
        self::assertCount(3, array_unique($result), 'All elements should be unique');
    }

    public function testRandomElementsUniqueException(): void
    {
        $randomizer = new XoshiroRandomizer(seed: 1);
        $array = ['a', 'b', 'a', 'b'];

        $this->expectException(ExtensionArgumentException::class);
        $randomizer->randomElements($array, 5, true);
    }

    public function testZeroSeed(): void
    {
        $randomizer1 = new XoshiroRandomizer(seed: 0);
        $randomizer2 = new XoshiroRandomizer(seed: 0);

        $value1 = $randomizer1->getInt(1, 100);
        $value2 = $randomizer2->getInt(1, 100);

        self::assertEquals($value1, $value2, 'Zero seed should be reproducible');
    }

    public function testNegativeSeed(): void
    {
        $randomizer = new XoshiroRandomizer(seed: -42);

        $value = $randomizer->getInt(1, 100);

        self::assertTrue($value >= 1 && $value <= 100);
    }

    public function testLargeSeed(): void
    {
        $randomizer = new XoshiroRandomizer(seed: PHP_INT_MAX);

        $value = $randomizer->getInt(1, 100);

        self::assertTrue($value >= 1 && $value <= 100);
    }

    public function testConsecutiveSeedsProduceDifferentResults(): void
    {
        $randomizer1 = new XoshiroRandomizer(seed: 100);
        $randomizer2 = new XoshiroRandomizer(seed: 101);

        $value1 = $randomizer1->getInt(1, 1000000);
        $value2 = $randomizer2->getInt(1, 1000000);

        // While technically they COULD be the same, it's extremely unlikely
        // This tests that consecutive seeds produce different random states
        self::assertNotEquals($value1, $value2);
    }

    public function testReproducibilityWithComplexOperations(): void
    {
        $randomizer1 = new XoshiroRandomizer(seed: 999);
        $randomizer2 = new XoshiroRandomizer(seed: 999);

        // Complex sequence of operations
        $results1 = [
            $randomizer1->getInt(1, 100),
            $randomizer1->getFloat(0.0, 1.0),
            $randomizer1->getBool(50),
            $randomizer1->randomLetter(),
            $randomizer1->randomElement(['a', 'b', 'c']),
        ];

        $results2 = [
            $randomizer2->getInt(1, 100),
            $randomizer2->getFloat(0.0, 1.0),
            $randomizer2->getBool(50),
            $randomizer2->randomLetter(),
            $randomizer2->randomElement(['a', 'b', 'c']),
        ];

        self::assertEquals($results1, $results2);
    }
}
