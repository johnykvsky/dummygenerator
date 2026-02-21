<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Performance;

use DummyGenerator\Core\Internet;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Number;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\Uuid;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Extension\UuidExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ValidStrategy;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Performance benchmarks for the DummyGenerator.
 *
 * These tests validate performance characteristics including:
 * - Memory usage under various load conditions
 * - Time efficiency of batch operations
 * - Strategy overhead comparison
 * - Container caching behavior
 * - Memory leak detection
 *
 * @group performance
 */
class GeneratorPerformanceTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty(true);
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(UuidExtensionInterface::class, Uuid::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $this->generator = new DummyGenerator($container);
    }

    // Benchmark: Multiple calls to common methods

    /**
     * Test that generating 1000 random numbers uses reasonable memory.
     *
     * This benchmark validates that:
     * - The generator can handle batch operations (1000 calls)
     * - Memory usage remains under 1MB for simple operations
     * - No memory leaks occur during repeated calls
     *
     * The 1MB threshold is conservative; typical usage should be much lower.
     *
     * @group performance
     * @group memory
     */
    public function testCanGenerate1000RandomNumbers(): void
    {
        $startMemory = memory_get_usage();

        for ($i = 0; $i < 1000; $i++) {
            $this->generator->numberBetween(1, 100);
        }

        $endMemory = memory_get_usage();
        $memoryUsed = $endMemory - $startMemory;

        // Memory usage should be reasonable (less than 1MB for 1000 simple operations)
        self::assertLessThan(1024 * 1024, $memoryUsed, 'Memory usage should be under 1MB');
    }

    public function testCanGenerate1000UUIDs(): void
    {
        $uuids = [];

        for ($i = 0; $i < 1000; $i++) {
            $uuids[] = $this->generator->uuid4();
        }

        // All should be valid UUIDs
        self::assertCount(1000, $uuids);

        // Spot check a few
        foreach (array_slice($uuids, 0, 10) as $uuid) {
            self::assertMatchesRegularExpression('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid);
        }
    }

    public function testCanGenerate1000Names(): void
    {
        $names = [];

        for ($i = 0; $i < 1000; $i++) {
            $names[] = $this->generator->firstName();
        }

        // All should be non-empty strings
        self::assertCount(1000, $names);

        foreach ($names as $name) {
            self::assertIsString($name);
            self::assertNotEmpty($name);
        }
    }

    public function testCanGenerate1000EmailAddresses(): void
    {
        $emails = [];

        for ($i = 0; $i < 1000; $i++) {
            $emails[] = $this->generator->email();
        }

        // All should contain @
        self::assertCount(1000, $emails);

        foreach ($emails as $email) {
            self::assertStringContainsString('@', $email);
        }
    }

    public function testCanGenerate1000LoremWords(): void
    {
        $words = [];

        for ($i = 0; $i < 1000; $i++) {
            $words[] = $this->generator->word();
        }

        self::assertCount(1000, $words);

        foreach ($words as $word) {
            self::assertIsString($word);
            self::assertNotEmpty($word);
        }
    }

    // Memory usage tracking for large data generation

    public function testLargeTextGenerationMemoryUsage(): void
    {
        $startMemory = memory_get_usage();

        // Generate 10 large text blocks
        for ($i = 0; $i < 10; $i++) {
            $text = $this->generator->text(maxCharacters: 5000);
            self::assertLessThanOrEqual(5000, strlen($text));
        }

        $endMemory = memory_get_usage();
        $memoryUsed = $endMemory - $startMemory;

        // Should use reasonable memory (less than 5MB for 10 * 5000 char texts)
        self::assertLessThan(5 * 1024 * 1024, $memoryUsed);
    }

    public function testMultipleParagraphGenerationMemoryUsage(): void
    {
        $startMemory = memory_get_usage();

        // Generate 100 paragraphs
        for ($i = 0; $i < 100; $i++) {
            $paragraph = $this->generator->paragraph(sentenceCount: 5);
            self::assertIsString($paragraph);
        }

        $endMemory = memory_get_usage();
        $memoryUsed = $endMemory - $startMemory;

        // Memory usage should be reasonable
        self::assertLessThan(2 * 1024 * 1024, $memoryUsed);
    }

    // Strategy overhead comparison

    /**
     * Benchmark SimpleStrategy performance with 1000 generations.
     *
     * SimpleStrategy is the baseline (no-op) strategy. This test validates that:
     * - 1000 number generations complete in under 1 second
     * - Strategy overhead is minimal
     * - Generator maintains consistent performance
     *
     * This serves as a baseline for comparing other strategies.
     *
     * @group performance
     * @group strategy
     */
    public function testSimpleStrategyPerformance(): void
    {
        $container = TestContainerFactory::withStrategy(new SimpleStrategy());
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(UuidExtensionInterface::class, Uuid::class);
        $container->set(InternetExtensionInterface::class, Internet::class);
        $generator = new DummyGenerator($container);

        $startTime = microtime(true);

        for ($i = 0; $i < 1000; $i++) {
            $generator->numberBetween(1, 100);
        }

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        // Should complete reasonably fast (less than 1 second)
        self::assertLessThan(1.0, $duration, 'SimpleStrategy should be fast');
    }

    public function testUniqueStrategyPerformance(): void
    {
        $container = TestContainerFactory::withStrategy(new UniqueStrategy(retries: 100), true);
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(UuidExtensionInterface::class, Uuid::class);
        $container->set(InternetExtensionInterface::class, Internet::class);
        $generator = new DummyGenerator($container);

        $startTime = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            $generator->uuid4();
        }

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        // Should complete reasonably fast
        self::assertLessThan(1.0, $duration, 'UniqueStrategy should handle 100 UUIDs quickly');
    }

    public function testValidStrategyPerformance(): void
    {
        $container = TestContainerFactory::withStrategy(new ValidStrategy(fn($value) => $value > 50));
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(UuidExtensionInterface::class, Uuid::class);
        $container->set(InternetExtensionInterface::class, Internet::class);
        $generator = new DummyGenerator($container);

        $startTime = microtime(true);

        for ($i = 0; $i < 100; $i++) {
            $number = $generator->numberBetween(1, 100);
            self::assertGreaterThan(50, $number);
        }

        $endTime = microtime(true);
        $duration = $endTime - $startTime;

        // Should complete reasonably fast even with validation
        self::assertLessThan(1.0, $duration, 'ValidStrategy should be reasonably fast');
    }

    // Container lookup performance

    public function testContainerFirstCallVsCachedCall(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(RandomizerInterface::class, Randomizer::class);

        // First call (will instantiate)
        $startTime = microtime(true);
        $first = $container->get(NumberExtensionInterface::class);
        $firstCallTime = microtime(true) - $startTime;

        // Second call (cached)
        $startTime = microtime(true);
        $second = $container->get(NumberExtensionInterface::class);
        $secondCallTime = microtime(true) - $startTime;

        // Both should return same instance
        self::assertSame($first, $second);

        // Both should be very fast (just check they complete)
        self::assertLessThan(0.1, $firstCallTime);
        self::assertLessThan(0.1, $secondCallTime);
    }

    public function testContainerMethodLookupPerformance(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(RandomizerInterface::class, Randomizer::class);
        $generator = new DummyGenerator($container);

        // Lookup method multiple times
        for ($i = 0; $i < 100; $i++) {
            $value = $generator->numberBetween(1, 10);
            self::assertIsInt($value);
        }

        // Should complete without hanging
        self::assertTrue(true);
    }

    // Batch operations

    public function testBatchUUIDGeneration(): void
    {
        $batchSize = 5000;
        $uuids = [];

        for ($i = 0; $i < $batchSize; $i++) {
            $uuids[] = $this->generator->uuid4();
        }

        self::assertCount($batchSize, $uuids);

        // Spot check uniqueness (all 5000 should be unique)
        $unique = array_unique($uuids);
        self::assertCount($batchSize, $unique, 'All UUIDs should be unique');
    }

    public function testBatchEmailGeneration(): void
    {
        $batchSize = 1000;
        $emails = [];

        for ($i = 0; $i < $batchSize; $i++) {
            $emails[] = $this->generator->safeEmail();
        }

        self::assertCount($batchSize, $emails);

        // All should be valid emails
        foreach (array_slice($emails, 0, 10) as $email) {
            self::assertStringContainsString('@', $email);
            self::assertStringContainsString('.', $email);
        }
    }

    // Complex operations

    public function testComplexTemplateParsingPerformance(): void
    {
        $template = 'User: {{ firstName }} {{ lastName }}, Email: {{ email }}, Age: {{ numberBetween(18, 65) }}';

        for ($i = 0; $i < 100; $i++) {
            $result = $this->generator->parse($template);

            self::assertStringContainsString('User:', $result);
            self::assertStringContainsString('Email:', $result);
            self::assertStringContainsString('Age:', $result);
        }

        // Should complete without hanging
        self::assertTrue(true);
    }

    public function testNestedMethodCallsPerformance(): void
    {
        // Multiple method calls in sequence
        for ($i = 0; $i < 100; $i++) {
            $firstName = $this->generator->firstName();
            $lastName = $this->generator->lastName();
            $email = $this->generator->email();
            $age = $this->generator->numberBetween(18, 65);

            self::assertIsString($firstName);
            self::assertIsString($lastName);
            self::assertIsString($email);
            self::assertIsInt($age);
        }

        // Should complete efficiently
        self::assertTrue(true);
    }

    // Memory leak detection

    public function testNoMemoryLeakOnRepeatedCalls(): void
    {
        $iterations = 1000;

        // Warm up
        for ($i = 0; $i < 10; $i++) {
            $this->generator->word();
        }

        // Measure baseline
        gc_collect_cycles();
        $startMemory = memory_get_usage();

        // Execute many iterations
        for ($i = 0; $i < $iterations; $i++) {
            $this->generator->word();
        }

        gc_collect_cycles();
        $endMemory = memory_get_usage();

        $memoryGrowth = $endMemory - $startMemory;

        // Memory growth should be minimal (less than 100KB for 1000 simple calls)
        self::assertLessThan(100 * 1024, $memoryGrowth, 'Memory should not leak significantly');
    }

    public function testNoMemoryLeakWithDifferentStrategies(): void
    {
        $strategies = [
            new SimpleStrategy(),
            new UniqueStrategy(retries: 100),
            new ValidStrategy(fn($v) => true),
        ];

        gc_collect_cycles();
        $startMemory = memory_get_usage();

        foreach ($strategies as $strategy) {
            $container = TestContainerFactory::withStrategy($strategy, true);
            $container->set(RandomizerInterface::class, Randomizer::class);
            $container->set(TransliteratorInterface::class, Transliterator::class);
            $container->set(ReplacerInterface::class, Replacer::class);
            $container->set(NumberExtensionInterface::class, Number::class);
            $container->set(PersonExtensionInterface::class, Person::class);
            $container->set(LoremExtensionInterface::class, Lorem::class);
            $container->set(UuidExtensionInterface::class, Uuid::class);
            $container->set(InternetExtensionInterface::class, Internet::class);
            $generator = new DummyGenerator($container);

            for ($i = 0; $i < 100; $i++) {
                $generator->numberBetween(1, 1000000);
            }
        }

        gc_collect_cycles();
        $endMemory = memory_get_usage();

        $memoryGrowth = $endMemory - $startMemory;

        // Memory shouldn't grow excessively with strategy switching
        self::assertLessThan(500 * 1024, $memoryGrowth);
    }

    // Concurrent-like operations (sequential but rapid)

    public function testRapidFireGeneration(): void
    {
        $results = [];

        // Generate many different types rapidly
        for ($i = 0; $i < 100; $i++) {
            $results[] = [
                'uuid' => $this->generator->uuid4(),
                'name' => $this->generator->name(),
                'email' => $this->generator->email(),
                'number' => $this->generator->randomDigit(),
                'word' => $this->generator->word(),
            ];
        }

        self::assertCount(100, $results);

        // Verify structure of results
        foreach ($results as $result) {
            self::assertArrayHasKey('uuid', $result);
            self::assertArrayHasKey('name', $result);
            self::assertArrayHasKey('email', $result);
            self::assertArrayHasKey('number', $result);
            self::assertArrayHasKey('word', $result);
        }
    }
}
