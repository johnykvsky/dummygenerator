<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Randomizer;

use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    public function testRandomElement(): void
    {
        $randomizer = new Randomizer();
        $elements = ['23', 'e', 32, '#'];
        self::assertContains($randomizer->randomElement($elements), $elements);
    }

    public function testRandomKey(): void
    {
        $randomizer = new Randomizer();
        $elements = ['key' => '23', 'flower' => 'e', 'mars' => 32, 'hand' => '#'];
        self::assertContains($randomizer->randomKey($elements), array_keys($elements));
    }

    public function testRandomLetter(): void
    {
        $randomizer = new Randomizer();
        $letter = $randomizer->randomLetter();

        self::assertTrue(ord($letter) >= 97 && ord($letter) <= 122);
    }

    public function testGetInt(): void
    {
        $min = 5;
        $max = 50;
        $randomizer = new Randomizer();
        $number = $randomizer->getInt($min, $max);

        self::assertTrue($number >= $min && $number <= $max);
    }

    public function testGetFloat(): void
    {
        $min = 5.50;
        $max = 150.45;
        $randomizer = new Randomizer();
        $number = $randomizer->getFloat($min, $max);

        self::assertTrue($number >= $min && $number <= $max);
    }

    public function testShuffleElements(): void
    {
        $array = ['23', 'e', 32, '#'];
        $randomizer = new Randomizer();
        $shuffled = $randomizer->shuffleElements($array);

        self::assertCount(count($array), $shuffled);
    }

    public function testRandomElements(): void
    {
        $array = ['23', 'e', 32, '#'];
        $randomizer = new Randomizer();
        $shuffled = $randomizer->randomElements($array, 2);

        self::assertCount(2, $shuffled);
        self::assertContains($shuffled[0], $array);
        self::assertContains($shuffled[1], $array);
    }

    public function testRandomElementsUnique(): void
    {
        $array = ['23', '#', '23', '#'];
        $randomizer = new Randomizer();
        $shuffled = $randomizer->randomElements($array, 2, true);

        self::assertCount(2, $shuffled);
        self::assertNotEquals($shuffled[0], $shuffled[1]);
        self::assertContains($shuffled[0], $array);
        self::assertContains($shuffled[1], $array);
    }

    public function testRandomElementsUniqueException(): void
    {
        $array = ['23', '#', '23', '#'];
        $randomizer = new Randomizer();
        $this->expectException(ExtensionArgumentException::class);
        $randomizer->randomElements($array, 5, true);
    }

    // Enhanced edge case tests

    public function testGetBoolWithZeroPercentage(): void
    {
        $randomizer = new Randomizer();

        // 0% chance should always be false
        for ($i = 0; $i < 20; $i++) {
            self::assertFalse($randomizer->getBool(0));
        }
    }

    public function testGetBoolWithOneHundredPercentage(): void
    {
        $randomizer = new Randomizer();

        // 100% chance should always be true
        for ($i = 0; $i < 20; $i++) {
            self::assertTrue($randomizer->getBool(100));
        }
    }

    public function testGetBoolWithFiftyPercentage(): void
    {
        $randomizer = new Randomizer();
        $results = [];

        // Test 50% chance - should get mix of true/false
        for ($i = 0; $i < 100; $i++) {
            $results[] = $randomizer->getBool(50);
        }

        $trueCount = count(array_filter($results));

        // With 100 samples and 50%, expect between 30-70 (allowing variance)
        self::assertGreaterThan(25, $trueCount);
        self::assertLessThan(75, $trueCount);
    }

    public function testGetBoolWithOnePercentage(): void
    {
        $randomizer = new Randomizer();
        $results = [];

        // Test 1% chance
        for ($i = 0; $i < 100; $i++) {
            $results[] = $randomizer->getBool(1);
        }

        $trueCount = count(array_filter($results));

        // With 1% chance, should mostly be false
        self::assertLessThan(20, $trueCount);
    }

    public function testGetBoolWithNinetyNinePercentage(): void
    {
        $randomizer = new Randomizer();
        $results = [];

        // Test 99% chance
        for ($i = 0; $i < 100; $i++) {
            $results[] = $randomizer->getBool(99);
        }

        $trueCount = count(array_filter($results));

        // With 99% chance, should mostly be true
        self::assertGreaterThan(80, $trueCount);
    }

    public function testGetBytesLength(): void
    {
        $randomizer = new Randomizer();

        self::assertEquals(16, strlen($randomizer->getBytes(16)));
        self::assertEquals(32, strlen($randomizer->getBytes(32)));
        self::assertEquals(1, strlen($randomizer->getBytes(1)));
        self::assertEquals(100, strlen($randomizer->getBytes(100)));
    }

    public function testGetBytesGeneratesDifferentBytes(): void
    {
        $randomizer = new Randomizer();

        $bytes1 = $randomizer->getBytes(16);
        $bytes2 = $randomizer->getBytes(16);

        // Should be very unlikely to generate same 16 random bytes
        self::assertNotEquals($bytes1, $bytes2);
    }

    public function testGetBytesFromStringWithSingleCharacterSource(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getBytesFromString('A', 10);

        self::assertEquals(10, strlen($result));
        self::assertEquals('AAAAAAAAAA', $result);
    }

    public function testGetBytesFromStringLengthValidation(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getBytesFromString('abcdef', 3);
        self::assertEquals(3, strlen($result));

        $result = $randomizer->getBytesFromString('abcdef', 10);
        self::assertEquals(10, strlen($result));
    }

    public function testGetBytesFromStringWithLengthGreaterThanSource(): void
    {
        $randomizer = new Randomizer();

        // Source has 3 characters, request 20
        $result = $randomizer->getBytesFromString('abc', 20);

        self::assertEquals(20, strlen($result));

        // All characters should be from the source
        for ($i = 0; $i < 20; $i++) {
            self::assertContains($result[$i], ['a', 'b', 'c']);
        }
    }

    public function testRandomElementsWithCountZero(): void
    {
        $randomizer = new Randomizer();
        $array = ['a', 'b', 'c'];

        $result = $randomizer->randomElements($array, 0);

        self::assertIsArray($result);
        self::assertEmpty($result);
    }

    public function testRandomElementsWithCountGreaterThanArrayNonUnique(): void
    {
        $randomizer = new Randomizer();
        $array = ['a', 'b'];

        // Request 5 elements from 2-element array (non-unique)
        $result = $randomizer->randomElements($array, 5, unique: false);

        self::assertCount(5, $result);

        // All elements should be from original array
        foreach ($result as $element) {
            self::assertContains($element, $array);
        }
    }

    public function testRandomKeyWithSingleElement(): void
    {
        $randomizer = new Randomizer();
        $array = ['only_key' => 'value'];

        $result = $randomizer->randomKey($array);

        self::assertEquals('only_key', $result);
    }

    public function testRandomKeyWithNumericKeys(): void
    {
        $randomizer = new Randomizer();
        $array = [10 => 'a', 20 => 'b', 30 => 'c'];

        $result = $randomizer->randomKey($array);

        self::assertContains($result, [10, 20, 30]);
    }

    public function testGetIntWithNegativeRange(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getInt(-100, -50);

        self::assertTrue($result >= -100 && $result <= -50);
    }

    public function testGetIntWithNegativeToPositiveRange(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getInt(-50, 50);

        self::assertTrue($result >= -50 && $result <= 50);
    }

    public function testGetIntWithEqualMinMax(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getInt(42, 42);

        self::assertEquals(42, $result);
    }

    public function testGetFloatWithNegativeRange(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getFloat(-100.5, -50.5);

        self::assertTrue($result >= -100.5 && $result <= -50.5);
    }

    public function testGetFloatWithNegativeToPositiveRange(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getFloat(-50.5, 50.5);

        self::assertTrue($result >= -50.5 && $result <= 50.5);
    }

    public function testGetFloatWithEqualMinMax(): void
    {
        $randomizer = new Randomizer();

        $result = $randomizer->getFloat(42.5, 42.5);

        self::assertEquals(42.5, $result);
    }

    public function testShuffleElementsPreservesAllElements(): void
    {
        $randomizer = new Randomizer();
        $array = ['a', 'b', 'c', 'd', 'e'];

        $shuffled = $randomizer->shuffleElements($array);

        // Should have same count
        self::assertCount(5, $shuffled);

        // All elements should be present
        sort($array);
        sort($shuffled);
        self::assertEquals($array, $shuffled);
    }

    public function testShuffleElementsWithSingleElement(): void
    {
        $randomizer = new Randomizer();
        $array = ['only'];

        $shuffled = $randomizer->shuffleElements($array);

        self::assertEquals(['only'], $shuffled);
    }

    public function testShuffleElementsWithEmptyArray(): void
    {
        $randomizer = new Randomizer();
        $array = [];

        $shuffled = $randomizer->shuffleElements($array);

        self::assertEmpty($shuffled);
    }

    public function testRandomElementWithSingleElement(): void
    {
        $randomizer = new Randomizer();
        $array = ['only'];

        $result = $randomizer->randomElement($array);

        self::assertEquals('only', $result);
    }

    public function testRandomLetterReturnsLowercase(): void
    {
        $randomizer = new Randomizer();

        for ($i = 0; $i < 20; $i++) {
            $letter = $randomizer->randomLetter();
            self::assertMatchesRegularExpression('/^[a-z]$/', $letter);
        }
    }

    public function testRandomLetterVariety(): void
    {
        $randomizer = new Randomizer();
        $letters = [];

        for ($i = 0; $i < 50; $i++) {
            $letters[] = $randomizer->randomLetter();
        }

        $uniqueLetters = array_unique($letters);

        // Should have variety (not all the same letter)
        self::assertGreaterThan(1, count($uniqueLetters));
    }

    public function testRandomElementsNonUniqueCanHaveDuplicates(): void
    {
        $randomizer = new Randomizer();
        $array = ['a', 'b'];

        // Request more elements than available, non-unique should allow duplicates
        $result = $randomizer->randomElements($array, 10, unique: false);

        self::assertCount(10, $result);
    }

    public function testGetBytesFromStringGeneratesRandomSelection(): void
    {
        $randomizer = new Randomizer();

        $bytes1 = $randomizer->getBytesFromString('abcdefghij', 20);
        $bytes2 = $randomizer->getBytesFromString('abcdefghij', 20);

        // Should be unlikely to generate same sequence
        self::assertNotEquals($bytes1, $bytes2);
    }
}
