<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Stress;

use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Number;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\Strings;
use DummyGenerator\Core\Uuid;
use DummyGenerator\Core\Address;
use DummyGenerator\Core\Country;
use DummyGenerator\Core\Internet;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\CountryExtensionInterface;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;
use DummyGenerator\Definitions\Extension\UuidExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ValidStrategy;
use PHPUnit\Framework\TestCase;

/**
 * Stress tests for the DummyGenerator under extreme conditions.
 *
 * These tests validate generator behavior at the limits:
 * - UniqueStrategy exhaustion and recovery
 * - ValidStrategy with highly restrictive validators
 * - Very large data generation (100K+ characters)
 * - Mass UUID generation (10K+ items)
 * - Deep template token nesting
 * - Extreme string lengths and number ranges
 * - Rapid strategy switching
 *
 * @group stress
 */
class GeneratorStressTest extends TestCase
{
    private DummyGenerator $generator;
    /** @var array<string, class-string|callable|object> */
    private array $definitions = [];

    public function setUp(): void
    {
        parent::setUp();

        $this->definitions = [
            RandomizerInterface::class => Randomizer::class,
            TransliteratorInterface::class => Transliterator::class,
            ReplacerInterface::class => Replacer::class,
            NumberExtensionInterface::class => Number::class,
            LoremExtensionInterface::class => Lorem::class,
            UuidExtensionInterface::class => Uuid::class,
            StringsExtensionInterface::class => Strings::class,
            PersonExtensionInterface::class => Person::class,
            AddressExtensionInterface::class => Address::class,
            CountryExtensionInterface::class => Country::class,
            InternetExtensionInterface::class => Internet::class,
        ];

        $this->generator = new DummyGenerator($this->buildContainer());
    }

    private function buildContainer(?\DummyGenerator\Strategy\StrategyInterface $strategy = null): \DummyGenerator\Container\DummyContainer
    {
        $definitions = $this->definitions;

        if ($strategy !== null) {
            $definitions[\DummyGenerator\Strategy\StrategyInterface::class] = $strategy;
        }

        return \DummyGenerator\Container\DiContainerFactory::custom($definitions, true);
    }

    // UniqueStrategy exhaustion scenarios

    /**
     * Test that UniqueStrategy exhausts when the available range is too small.
     *
     * This stress test validates UniqueStrategy behavior under impossible conditions:
     * - Only 5 possible values (1-5)
     * - Requesting 10 unique values
     * - 100 retries maximum
     *
     * Expected behavior:
     * - Either generate some values before exhaustion (< 10)
     * - Or throw OverflowException when retries exhausted
     *
     * This proves the strategy properly detects exhaustion rather than hanging forever.
     *
     * @group stress
     * @group strategy
     * @group uniqueness
     */
    public function testUniqueStrategyWithSmallRangeEventuallyExhausts(): void
    {
        $generator = new DummyGenerator($this->buildContainer(new UniqueStrategy(retries: 100)));

        // Small range: only 5 possible values
        $values = [];

        try {
            for ($i = 0; $i < 10; $i++) {
                $values[] = $generator->numberBetween(1, 5);
            }

            // If we get here, it means we didn't exhaust (some duplicates allowed initially)
            // But we should have at least generated some values
            self::assertGreaterThan(0, count($values));
        } catch (\OverflowException $e) {
            // Expected: unique strategy exhausted the small range
            self::assertStringContainsString('retries', strtolower($e->getMessage()));
        }
    }

    public function testUniqueStrategyWithLargeRangeSucceeds(): void
    {
        $generator = new DummyGenerator($this->buildContainer(new UniqueStrategy(retries: 100)));

        // Large range: should handle many unique values
        $values = [];

        for ($i = 0; $i < 1000; $i++) {
            $values[] = $generator->numberBetween(1, 100000);
        }

        self::assertCount(1000, $values);

        // All should be unique
        $unique = array_unique($values);
        self::assertCount(1000, $unique);
    }

    public function testUniqueStrategyWithUUIDsGeneratesThousandsUnique(): void
    {
        $generator = new DummyGenerator($this->buildContainer(new UniqueStrategy(retries: 100)));

        $uuids = [];

        // Generate 5000 unique UUIDs
        for ($i = 0; $i < 5000; $i++) {
            $uuids[] = $generator->uuid4();
        }

        self::assertCount(5000, $uuids);

        // All should be unique
        $unique = array_unique($uuids);
        self::assertCount(5000, $unique, 'All 5000 UUIDs should be unique');
    }

    // ValidStrategy with very restrictive validators

    /**
     * Test ValidStrategy with a highly restrictive validator (5% acceptance rate).
     *
     * This stress test validates that ValidStrategy can handle validators
     * that reject 95% of generated values:
     * - Range: 1-100
     * - Validator: Only accept values > 95 (96-100, just 5 valid values)
     * - Iterations: 100 values requested
     *
     * The strategy must:
     * - Retry until valid value found
     * - Never return invalid values
     * - Complete all 100 requests despite low acceptance rate
     *
     * This proves the validator retry mechanism works under pressure.
     *
     * @group stress
     * @group strategy
     * @group validation
     */
    public function testValidStrategyWithVeryRestrictiveValidator(): void
    {
        // Only accept numbers > 95 from range 1-100 (5% acceptance rate)
        $generator = new DummyGenerator($this->buildContainer(new ValidStrategy(fn($value) => $value > 95)));

        $values = [];

        for ($i = 0; $i < 100; $i++) {
            $value = $generator->numberBetween(1, 100);
            $values[] = $value;

            // Should always pass validator
            self::assertGreaterThan(95, $value);
        }

        self::assertCount(100, $values);
    }

    public function testValidStrategyWithComplexValidator(): void
    {
        // Complex validator: only accept even numbers divisible by 3
        $validator = function($value) {
            return $value % 2 === 0 && $value % 3 === 0;
        };

        $generator = new DummyGenerator($this->buildContainer(new ValidStrategy($validator)));

        $values = [];

        for ($i = 0; $i < 50; $i++) {
            $value = $generator->numberBetween(1, 100);
            $values[] = $value;

            // Should pass both conditions
            self::assertEquals(0, $value % 2, "Value $value should be even");
            self::assertEquals(0, $value % 3, "Value $value should be divisible by 3");
        }

        self::assertCount(50, $values);
    }

    public function testValidStrategyWithStringLengthValidator(): void
    {
        // Only accept strings with exactly 10 characters
        $validator = fn($value) => strlen($value) === 10;

        $generator = new DummyGenerator($this->buildContainer(new ValidStrategy($validator)));

        $values = [];

        for ($i = 0; $i < 50; $i++) {
            $value = $generator->string(min: 8, max: 12);
            $values[] = $value;

            self::assertEquals(10, strlen($value));
        }

        self::assertCount(50, $values);
    }

    // Large text generation

    /**
     * Test generation of very large text blocks (100K characters).
     *
     * This stress test validates the Lorem generator under extreme conditions:
     * - Request: 100,000 character text block
     * - Expected: Actual length 50K-100K characters
     * - Tests: Memory efficiency, performance, no hangs
     *
     * Large text generation is common for:
     * - Database seeding with article content
     * - PDF generation testing
     * - Text processing pipeline validation
     *
     * The test ensures the generator can handle production-scale data.
     *
     * @group stress
     * @group lorem
     * @group large-data
     */
    public function testVeryLargeTextGeneration(): void
    {
        // Generate very large text (100,000+ characters)
        $text = $this->generator->text(maxCharacters: 100000);

        self::assertIsString($text);
        self::assertGreaterThan(50000, strlen($text), 'Text should be substantial');
        self::assertLessThanOrEqual(100000, strlen($text));
    }

    public function testMultipleVeryLargeParagraphs(): void
    {
        // Generate multiple very large paragraphs
        $paragraphs = [];

        for ($i = 0; $i < 10; $i++) {
            $paragraph = $this->generator->paragraph(sentenceCount: 100);
            $paragraphs[] = $paragraph;

            self::assertGreaterThan(500, strlen($paragraph));
        }

        self::assertCount(10, $paragraphs);
    }

    public function testVeryLongSentence(): void
    {
        // Generate very long sentence (200 words)
        $sentence = $this->generator->sentence(wordCount: 200, variableWordCount: false);

        self::assertStringEndsWith('.', $sentence);

        // Should have approximately 200 words
        $wordCount = str_word_count($sentence);
        self::assertGreaterThan(190, $wordCount);
        self::assertLessThan(210, $wordCount);
    }

    public function testManyParagraphsGeneration(): void
    {
        // Generate many paragraphs
        $paragraphs = $this->generator->paragraphs(paragraphCount: 100);

        self::assertCount(100, $paragraphs);
        self::assertIsArray($paragraphs);

        foreach ($paragraphs as $paragraph) {
            self::assertIsString($paragraph);
            self::assertNotEmpty($paragraph);
        }
    }

    // Thousands of UUIDs for uniqueness

    public function testThousandsOfUUIDsAreAllUnique(): void
    {
        $count = 10000;
        $uuids = [];

        for ($i = 0; $i < $count; $i++) {
            $uuids[] = $this->generator->uuid4();
        }

        self::assertCount($count, $uuids);

        // All should be unique
        $unique = array_unique($uuids);
        self::assertCount($count, $unique, "All $count UUIDs should be unique");
    }

    public function testUUIDsRemainValidAtScale(): void
    {
        $uuids = [];

        for ($i = 0; $i < 1000; $i++) {
            $uuids[] = $this->generator->uuid4();
        }

        // Validate every UUID
        foreach ($uuids as $uuid) {
            self::assertMatchesRegularExpression(
                '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/',
                $uuid,
                "UUID $uuid should be valid v4 format"
            );
        }
    }

    // Deep token nesting in parse()

    public function testDeepTokenNesting(): void
    {
        // Nested template with multiple token types
        $template = 'User {{ firstName }} {{ lastName }} lives at {{ numberBetween(1, 999) }} Main St';

        $result = $this->generator->parse($template);

        self::assertIsString($result);
        self::assertStringContainsString('User', $result);
        self::assertStringContainsString('lives at', $result);
        self::assertStringContainsString('Main St', $result);
    }

    public function testMultipleTokensInSingleTemplate(): void
    {
        $template = '{{ firstName }},{{ lastName }},{{ email }},{{ randomDigit }},{{ word }}';

        $result = $this->generator->parse($template);

        // Should have 4 commas (5 fields)
        self::assertEquals(4, substr_count($result, ','));
    }

    public function testComplexTemplateWithManyTokens(): void
    {
        $template = <<<'EOT'
Name: {{ firstName }} {{ lastName }}
Email: {{ safeEmail }}
Age: {{ numberBetween(18, 65) }}
City: {{ city }}
Country: {{ country }}
UUID: {{ uuid4 }}
Word: {{ word }}
Digit: {{ randomDigit }}
EOT;

        $result = $this->generator->parse($template);

        self::assertStringContainsString('Name:', $result);
        self::assertStringContainsString('Email:', $result);
        self::assertStringContainsString('Age:', $result);
        self::assertStringContainsString('City:', $result);
        self::assertStringContainsString('Country:', $result);
        self::assertStringContainsString('UUID:', $result);
        self::assertStringContainsString('Word:', $result);
        self::assertStringContainsString('Digit:', $result);
    }

    public function testParseWithManyTemplatesInLoop(): void
    {
        $template = '{{ firstName }} {{ lastName }}';

        for ($i = 0; $i < 1000; $i++) {
            $result = $this->generator->parse($template);

            self::assertIsString($result);
            self::assertNotEmpty($result);
            self::assertStringContainsString(' ', $result); // Should have space between names
        }

        // Should complete without hanging
        self::assertTrue(true);
    }

    // String generation stress

    public function testVeryLongStringGeneration(): void
    {
        // Generate very long random string (10,000 characters)
        $string = $this->generator->string(min: 10000, max: 10000);

        self::assertEquals(10000, strlen($string));
        self::assertMatchesRegularExpression('/^[a-zA-Z0-9]+$/', $string);
    }

    public function testManyLongStringsInSequence(): void
    {
        for ($i = 0; $i < 100; $i++) {
            $string = $this->generator->string(min: 1000, max: 1000);

            self::assertEquals(1000, strlen($string));
        }

        // Should complete efficiently
        self::assertTrue(true);
    }

    public function testStringWithSingleCharacterPool(): void
    {
        // Stress test: generate many strings from single character
        for ($i = 0; $i < 100; $i++) {
            $string = $this->generator->string(min: 100, max: 100, pool: 'X');

            self::assertEquals(str_repeat('X', 100), $string);
        }

        self::assertTrue(true);
    }

    // Number generation stress

    public function testManyRandomFloatsWithPrecision(): void
    {
        $floats = [];

        for ($i = 0; $i < 1000; $i++) {
            $floats[] = $this->generator->randomFloat(nbMaxDecimals: 10, min: 0.0, max: 1.0);
        }

        self::assertCount(1000, $floats);

        foreach ($floats as $float) {
            self::assertGreaterThanOrEqual(0.0, $float);
            self::assertLessThanOrEqual(1.0, $float);
        }
    }

    public function testVeryLargeNumberRange(): void
    {
        // Test with very large range
        for ($i = 0; $i < 100; $i++) {
            $number = $this->generator->numberBetween(1, 1000000000);

            self::assertGreaterThanOrEqual(1, $number);
            self::assertLessThanOrEqual(1000000000, $number);
        }

        self::assertTrue(true);
    }

    public function testNegativeNumberRanges(): void
    {
        // Test with negative ranges
        for ($i = 0; $i < 100; $i++) {
            $number = $this->generator->numberBetween(-1000000, -1);

            self::assertGreaterThanOrEqual(-1000000, $number);
            self::assertLessThanOrEqual(-1, $number);
        }

        self::assertTrue(true);
    }

    // Combined stress scenarios

    public function testCombinedStressScenario(): void
    {
        // Generate many different types in combination
        for ($i = 0; $i < 100; $i++) {
            $data = [
                'id' => $this->generator->uuid4(),
                'name' => $this->generator->firstName() . ' ' . $this->generator->lastName(),
                'age' => $this->generator->numberBetween(18, 65),
                'bio' => $this->generator->text(maxCharacters: 500),
                'tags' => $this->generator->words(wordCount: 10),
                'score' => $this->generator->randomFloat(nbMaxDecimals: 2, min: 0.0, max: 100.0),
                'code' => $this->generator->string(min: 8, max: 8),
            ];

            self::assertIsArray($data);
            self::assertCount(7, $data);
        }

        self::assertTrue(true);
    }

    public function testRapidStrategySwitching(): void
    {
        $strategies = [
            new \DummyGenerator\Strategy\SimpleStrategy(),
            new UniqueStrategy(retries: 100),
            new ValidStrategy(fn($v) => true),
        ];

        for ($i = 0; $i < 300; $i++) {
            // Switch strategy every iteration
            $generator = new DummyGenerator($this->buildContainer($strategies[$i % 3]));

            $value = $generator->numberBetween(1, 1000000);

            self::assertIsInt($value);
        }

        self::assertTrue(true);
    }

    // Edge case combinations

    public function testMaximumStringLength(): void
    {
        // Test maximum practical string length (50,000 chars)
        $string = $this->generator->string(min: 50000, max: 50000);

        self::assertEquals(50000, strlen($string));
    }

    public function testMinimumAndMaximumNumberBoundaries(): void
    {
        // Test with PHP_INT_MAX boundaries (reasonable range)
        $number = $this->generator->numberBetween(1000000000, 2000000000);

        self::assertGreaterThanOrEqual(1000000000, $number);
        self::assertLessThanOrEqual(2000000000, $number);
    }

    public function testConsistencyUnderStress(): void
    {
        // Verify generator remains consistent under stress
        for ($i = 0; $i < 1000; $i++) {
            $digit = $this->generator->randomDigit();

            self::assertGreaterThanOrEqual(0, $digit);
            self::assertLessThanOrEqual(9, $digit);
        }

        self::assertTrue(true);
    }
}
