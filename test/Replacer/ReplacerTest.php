<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Replacer;

use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\SimpleTransliterator;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertNotSame;

class ReplacerTest extends TestCase
{
    public function testNumerify(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        $string = 'this#is%test#%';

        $result = $replacer->numerify($string);

        self::assertIsNotNumeric($result[1]);
        self::assertIsNumeric($result[4]);
        self::assertTrue(is_numeric($result[7]) && ((int) $result[7] !== 0));
        self::assertIsNumeric($result[12]);
        self::assertTrue(is_numeric($result[13]) && ((int) $result[13] !== 0));
    }

    public function testLexify(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        $string = '123?56?89?';

        $result = $replacer->lexify($string);

        self::assertIsNumeric($result[1]);
        self::assertIsNotNumeric($result[3]);
        self::assertIsNotNumeric($result[6]);
        self::assertIsNotNumeric($result[9]);
    }

    public function testBothify(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        $string = 'this#is1?3te*t';

        $result = $replacer->bothify($string);

        self::assertIsNotNumeric($result[1]);
        self::assertIsNumeric($result[4]);
        self::assertIsNotNumeric($result[8]);
        self::assertNotSame('*', $result[12]);
    }

    public function testShuffleString(): void
    {
        $string = 'this#is%test#%123';

        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        $shuffled = $replacer->shuffleString($string);

        assertNotSame($string, $shuffled);
    }

    // Enhanced edge case tests

    public function testNumerifyWithNoPlaceholders(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = 'no placeholders here';

        $result = $replacer->numerify($string);

        // Should pass through unchanged
        self::assertEquals($string, $result);
    }

    public function testNumerifyWithOnlyPercentPlaceholders(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = 'test%%%test';

        $result = $replacer->numerify($string);

        // % should be replaced with non-zero digits
        self::assertNotEquals($string, $result);
        self::assertIsNumeric($result[4]);
        self::assertIsNumeric($result[5]);
        self::assertIsNumeric($result[6]);

        // All % should be non-zero
        self::assertTrue((int)$result[4] >= 1 && (int)$result[4] <= 9);
        self::assertTrue((int)$result[5] >= 1 && (int)$result[5] <= 9);
        self::assertTrue((int)$result[6] >= 1 && (int)$result[6] <= 9);
    }

    public function testNumerifyWithOnlyHashPlaceholders(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = '###-###';

        $result = $replacer->numerify($string);

        self::assertMatchesRegularExpression('/^\d{3}-\d{3}$/', $result);
    }

    public function testLexifyWithNoPlaceholders(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = 'no placeholders 123';

        $result = $replacer->lexify($string);

        // Should pass through unchanged
        self::assertEquals($string, $result);
    }

    public function testLexifyWithAsciiMode(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = '???';

        $result = $replacer->lexify($string, ascii: true);

        // Should have ASCII characters (33-126)
        self::assertEquals(3, strlen($result));
        for ($i = 0; $i < 3; $i++) {
            $ord = ord($result[$i]);
            self::assertTrue($ord >= 33 && $ord <= 126);
        }
    }

    public function testBothifyWithNoAsterisks(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = 'test#?#?';

        $result = $replacer->bothify($string);

        // Should process # and ? but no * to expand
        self::assertNotEquals($string, $result);
        self::assertEquals(8, strlen($result));
    }

    public function testBothifyWithOnlyAsterisks(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = '***-***';

        $result = $replacer->bothify($string);

        // Asterisks should be replaced with either # or ?, then processed
        self::assertNotEquals($string, $result);
        self::assertEquals(7, strlen($result));
        self::assertEquals('-', $result[3]);
    }

    public function testToLowerWithUnicode(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        if (extension_loaded('mbstring')) {
            $result = $replacer->toLower('ÀÉÎÖÜ');
            self::assertEquals('àéîöü', $result);
        } else {
            // Without mbstring, only ASCII lowercasing
            $result = $replacer->toLower('ABC');
            self::assertEquals('abc', $result);
        }
    }

    public function testToUpperWithUnicode(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        if (extension_loaded('mbstring')) {
            $result = $replacer->toUpper('àéîöü');
            self::assertEquals('ÀÉÎÖÜ', $result);
        } else {
            // Without mbstring, only ASCII uppercasing
            $result = $replacer->toUpper('abc');
            self::assertEquals('ABC', $result);
        }
    }

    public function testToLowerWithEmptyString(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        self::assertEquals('', $replacer->toLower(''));
    }

    public function testToUpperWithEmptyString(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        self::assertEquals('', $replacer->toUpper(''));
    }

    public function testStrlenWithMultibyteCharacters(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        if (extension_loaded('mbstring')) {
            // Greek letters are 2 bytes each in UTF-8
            $string = 'αβγδε'; // 5 characters, 10 bytes
            self::assertEquals(5, $replacer->strlen($string));
        } else {
            // Without mbstring, counts bytes
            $string = 'abc'; // 3 characters, 3 bytes
            self::assertEquals(3, $replacer->strlen($string));
        }
    }

    public function testStrlenWithEmptyString(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        self::assertEquals(0, $replacer->strlen(''));
    }

    public function testShuffleStringWithEmptyString(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        self::assertEquals('', $replacer->shuffleString(''));
    }

    public function testShuffleStringWithSingleCharacter(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        self::assertEquals('A', $replacer->shuffleString('A'));
    }

    public function testShuffleStringWithVeryLongString(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        // Create a long string
        $string = str_repeat('abcdefghij', 1000); // 10,000 characters

        $shuffled = $replacer->shuffleString($string);

        // Should have same length and same character counts
        self::assertEquals(10000, strlen($shuffled));
        self::assertEquals(substr_count($string, 'a'), substr_count($shuffled, 'a'));
    }

    public function testShuffleStringPreservesAllCharacters(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = 'abc123XYZ!@#';

        $shuffled = $replacer->shuffleString($string);

        // Should have same length
        self::assertEquals(strlen($string), strlen($shuffled));

        // All original characters should be present
        $originalChars = str_split($string);
        $shuffledChars = str_split($shuffled);

        sort($originalChars);
        sort($shuffledChars);

        self::assertEquals($originalChars, $shuffledChars);
    }

    public function testNumerifyHandlesMultipleSegments(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = '##-##-####';

        $result = $replacer->numerify($string);

        self::assertMatchesRegularExpression('/^\d{2}-\d{2}-\d{4}$/', $result);
    }

    public function testLexifyHandlesMultipleSegments(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = '??-??-??';

        $result = $replacer->lexify($string);

        self::assertMatchesRegularExpression('/^[a-z]{2}-[a-z]{2}-[a-z]{2}$/', $result);
    }

    public function testBothifyComplexPattern(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $string = '***-###-???';

        $result = $replacer->bothify($string);

        // Should have proper length
        self::assertEquals(11, strlen($result));
        self::assertEquals('-', $result[3]);
        self::assertEquals('-', $result[7]);
    }

    public function testNumerifyLargeNumberOfPlaceholders(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());

        // Test with many placeholders (more than PHP_INT_MAX digits)
        $string = str_repeat('#', 50);

        $result = $replacer->numerify($string);

        self::assertEquals(50, strlen($result));
        self::assertMatchesRegularExpression('/^\d{50}$/', $result);
    }

    public function testToLowerPreservesNonAlphabeticCharacters(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $result = $replacer->toLower('ABC-123!@#');
        self::assertEquals('abc-123!@#', $result);
    }

    public function testToUpperPreservesNonAlphabeticCharacters(): void
    {
        $replacer = new Replacer(new Randomizer(), new SimpleTransliterator());
        $result = $replacer->toUpper('abc-123!@#');
        self::assertEquals('ABC-123!@#', $result);
    }
}
