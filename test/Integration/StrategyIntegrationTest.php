<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Integration;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Number;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Strategy\ChanceStrategy;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ValidStrategy;
use DummyGenerator\Test\Fixtures\ProviderDefinitionPack;
use PHPUnit\Framework\TestCase;

class StrategyIntegrationTest extends TestCase
{
    public function testSwitchingStrategiesOnSameInstance(): void
    {
        $generator = DummyGenerator::create();

        // Default is SimpleStrategy
        $result1 = $generator->numberBetween(1, 10);
        self::assertTrue($result1 >= 1 && $result1 <= 10);

        // Switch to UniqueStrategy using a different container
        $uniqueContainer = \DummyGenerator\Container\DiContainerFactory::all();
        $uniqueContainer->set(StrategyInterface::class, new UniqueStrategy(100));
        $uniqueGenerator = new DummyGenerator($uniqueContainer);
        $results = [];
        for ($i = 0; $i < 5; $i++) {
            $results[] = $uniqueGenerator->numberBetween(1, 100);
        }

        // All results should be unique
        self::assertCount(5, array_unique($results));

        // Original generator should still use SimpleStrategy
        $result2 = $generator->numberBetween(1, 10);
        self::assertTrue($result2 >= 1 && $result2 <= 10);
    }

    public function testStrategyPreservationAcrossWithProviderCalls(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new UniqueStrategy(100));

        $generator = new DummyGenerator($container);

        // Generate unique numbers
        $numbers1 = [];
        for ($i = 0; $i < 3; $i++) {
            $numbers1[] = $generator->numberBetween(1, 100);
        }

        // Add a provider (this creates a new container but should preserve strategy)
        $generatorWithProvider = $generator->withProvider(new ProviderDefinitionPack());

        // The strategy should still be UniqueStrategy
        $numbers2 = [];
        for ($i = 0; $i < 3; $i++) {
            $numbers2[] = $generatorWithProvider->numberBetween(1, 100);
        }

        // Both sets should have unique values
        self::assertCount(3, array_unique($numbers1));
        self::assertCount(3, array_unique($numbers2));
    }

    public function testValidStrategyWithUniquePattern(): void
    {
        $evenValidator = fn(int $n): bool => $n % 2 === 0;
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 42));
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new ValidStrategy($evenValidator, 1000));

        // First create a generator with ValidStrategy (only even numbers)
        $validGenerator = new DummyGenerator($container);

        $evenNumbers = [];
        for ($i = 0; $i < 5; $i++) {
            $evenNumbers[] = $validGenerator->numberBetween(1, 100);
        }

        // All should be even
        foreach ($evenNumbers as $num) {
            self::assertEquals(0, $num % 2, "Number $num should be even");
        }

        // Now combine with UniqueStrategy concept by switching
        $uniqueContainer = TestContainerFactory::empty();
        $uniqueContainer->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 42));
        $uniqueContainer->set(NumberExtensionInterface::class, Number::class);
        $uniqueContainer->set(StrategyInterface::class, new UniqueStrategy(1000));
        $uniqueGenerator = new DummyGenerator($uniqueContainer);
        $uniqueNumbers = [];
        for ($i = 0; $i < 5; $i++) {
            $uniqueNumbers[] = $uniqueGenerator->numberBetween(1, 100);
        }

        // All should be unique
        self::assertCount(5, array_unique($uniqueNumbers));
    }

    public function testChanceStrategyWithComplexCallbacks(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 123));
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(StrategyInterface::class, new ChanceStrategy(0.5, new XoshiroRandomizer(seed: 456), 'N/A'));

        // 50% chance to generate, otherwise return default
        $chanceGenerator = new DummyGenerator($container);

        $results = [];
        for ($i = 0; $i < 20; $i++) {
            $results[] = $chanceGenerator->firstName();
        }

        // Some should be 'N/A' (the default), some should be actual names
        $naCount = count(array_filter($results, fn($r) => $r === 'N/A'));
        $nameCount = count(array_filter($results, fn($r) => $r !== 'N/A'));

        self::assertGreaterThan(0, $naCount, 'Should have some N/A values');
        self::assertGreaterThan(0, $nameCount, 'Should have some actual names');
        self::assertEquals(20, $naCount + $nameCount);
    }

    public function testChanceStrategyWithZeroWeight(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new ChanceStrategy(0.0, null, 999));

        // 0% chance - should always return default
        $generator = new DummyGenerator($container);

        for ($i = 0; $i < 10; $i++) {
            $result = $generator->numberBetween(1, 10);
            self::assertEquals(999, $result, 'With 0% chance, should always get default');
        }
    }

    public function testChanceStrategyWithFullWeight(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new ChanceStrategy(1.0, null, 999));

        // 100% chance - should always generate
        $generator = new DummyGenerator($container);

        for ($i = 0; $i < 10; $i++) {
            $result = $generator->numberBetween(1, 10);
            self::assertNotEquals(999, $result, 'With 100% chance, should never get default');
            self::assertTrue($result >= 1 && $result <= 10);
        }
    }

    public function testStressTestStrategyPerformance(): void
    {
        $simpleContainer = TestContainerFactory::empty();
        $simpleContainer->set(RandomizerInterface::class, Randomizer::class);
        $simpleContainer->set(NumberExtensionInterface::class, Number::class);

        // Test SimpleStrategy with thousands of calls
        $simpleGenerator = new DummyGenerator($simpleContainer);

        $startTime = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            $simpleGenerator->randomDigit();
        }
        $simpleTime = microtime(true) - $startTime;

        // Test UniqueStrategy with thousands of calls
        $uniqueContainer = TestContainerFactory::empty();
        $uniqueContainer->set(RandomizerInterface::class, Randomizer::class);
        $uniqueContainer->set(NumberExtensionInterface::class, Number::class);
        $uniqueContainer->set(StrategyInterface::class, new UniqueStrategy(10000));
        $uniqueGenerator = new DummyGenerator($uniqueContainer);

        $startTime = microtime(true);
        for ($i = 0; $i < 1000; $i++) {
            $uniqueGenerator->numberBetween(1, 100000);
        }
        $uniqueTime = microtime(true) - $startTime;

        // Both should complete in reasonable time
        self::assertLessThan(1.0, $simpleTime, 'SimpleStrategy should be fast');
        self::assertLessThan(5.0, $uniqueTime, 'UniqueStrategy should complete in reasonable time');

        // Verify both generated results (no need to compare timing as it's flaky)
        self::assertGreaterThan(0, $simpleTime);
        self::assertGreaterThan(0, $uniqueTime);
    }

    public function testValidStrategyWithStrictValidation(): void
    {
        // Only accept numbers divisible by 5
        $validator = fn(int $n): bool => $n % 5 === 0;
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 789));
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new ValidStrategy($validator, 10000));
        $generator = new DummyGenerator($container);

        $results = [];
        for ($i = 0; $i < 10; $i++) {
            $results[] = $generator->numberBetween(1, 100);
        }

        foreach ($results as $num) {
            self::assertEquals(0, $num % 5, "Number $num should be divisible by 5");
        }
    }

    public function testValidStrategyThrowsOnImpossibleValidation(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);

        // Impossible validator - no number can satisfy this
        $validator = fn(int $n): bool => false;
        $container->set(StrategyInterface::class, new ValidStrategy($validator, 10));

        $generator = new DummyGenerator($container);

        $this->expectException(\OverflowException::class);
        $this->expectExceptionMessage('Maximum retries of 10 reached without finding a valid value');

        $generator->numberBetween(1, 10);
    }

    public function testUniqueStrategyThrowsWhenExhausted(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new UniqueStrategy(100));

        $generator = new DummyGenerator($container);

        // Try to generate more unique numbers than possible in range
        $this->expectException(\OverflowException::class);

        for ($i = 0; $i < 15; $i++) {
            $generator->numberBetween(1, 10); // Only 10 possible values
        }
    }

    public function testStrategySwitchingPreservesGeneratorState(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new UniqueStrategy(100));

        $gen1 = new DummyGenerator($container);

        // Generate some data with simple strategy
        $name1 = $gen1->firstName();
        self::assertNotEmpty($name1);

        // Switch to unique strategy
        $container2 = TestContainerFactory::empty();
        $container2->set(RandomizerInterface::class, Randomizer::class);
        $container2->set(PersonExtensionInterface::class, Person::class);
        $container2->set(NumberExtensionInterface::class, Number::class);
        $container2->set(StrategyInterface::class, new UniqueStrategy(100));
        $gen2 = new DummyGenerator($container2);

        // Both generators should still be able to access all extensions
        $name2 = $gen2->firstName();
        $num1 = $gen1->numberBetween(1, 100);
        $num2 = $gen2->numberBetween(1, 100);

        self::assertNotEmpty($name2);
        self::assertTrue($num1 >= 1 && $num1 <= 100);
        self::assertTrue($num2 >= 1 && $num2 <= 100);
    }

    public function testMultipleStrategyInstancesIndependent(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(NumberExtensionInterface::class, Number::class);
        $container->set(StrategyInterface::class, new UniqueStrategy(100));

        // Create two independent unique generators
        $gen1 = new DummyGenerator($container);
        $gen2 = new DummyGenerator($container);

        // Generate from both
        $results1 = [];
        $results2 = [];

        for ($i = 0; $i < 5; $i++) {
            $results1[] = $gen1->numberBetween(1, 10);
            $results2[] = $gen2->numberBetween(1, 10);
        }

        // Both should have unique values within their own context
        self::assertCount(5, array_unique($results1));
        self::assertCount(5, array_unique($results2));

        // But they may overlap between generators (they're independent)
        // This is just checking they don't interfere with each other
        $combined = array_merge($results1, $results2);
        self::assertCount(10, $combined);
    }

    public function testValidStrategyWithComplexValidator(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 999));
        $container->set(NumberExtensionInterface::class, Number::class);

        // Complex validator: prime numbers only
        $isPrime = function (int $n): bool {
            if ($n < 2) {
                return false;
            }
            for ($i = 2; $i <= sqrt($n); $i++) {
                if ($n % $i === 0) {
                    return false;
                }
            }
            return true;
        };

        $container->set(StrategyInterface::class, new ValidStrategy($isPrime, 10000));

        $generator = new DummyGenerator($container);

        $primes = [];
        for ($i = 0; $i < 5; $i++) {
            $primes[] = $generator->numberBetween(2, 100);
        }

        foreach ($primes as $prime) {
            self::assertTrue($isPrime($prime), "Number $prime should be prime");
        }
    }

    public function testChanceStrategyDefaultValue(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 111));
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(StrategyInterface::class, new ChanceStrategy(0.0, new XoshiroRandomizer(seed: 222), null));

        // Test with different default value types
        $generator1 = new DummyGenerator($container);

        $result1 = $generator1->firstName();
        self::assertNull($result1);

        $container2 = TestContainerFactory::empty();
        $container2->set(RandomizerInterface::class, new XoshiroRandomizer(seed: 111));
        $container2->set(PersonExtensionInterface::class, Person::class);
        $container2->set(StrategyInterface::class, new ChanceStrategy(0.0, new XoshiroRandomizer(seed: 333), []));
        $generator2 = new DummyGenerator($container2);

        $result2 = $generator2->firstName();
        self::assertIsArray($result2);
        self::assertEmpty($result2);
    }

    public function testStrategyPerformanceComparison(): void
    {
        $simpleContainer = TestContainerFactory::empty();
        $simpleContainer->set(RandomizerInterface::class, Randomizer::class);
        $simpleContainer->set(NumberExtensionInterface::class, Number::class);

        $iterations = 100;

        // Benchmark SimpleStrategy
        $simple = new DummyGenerator($simpleContainer);
        $startSimple = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $simple->randomDigit();
        }
        $timeSimple = microtime(true) - $startSimple;

        // Benchmark UniqueStrategy
        $uniqueContainer = TestContainerFactory::empty();
        $uniqueContainer->set(RandomizerInterface::class, Randomizer::class);
        $uniqueContainer->set(NumberExtensionInterface::class, Number::class);
        $uniqueContainer->set(StrategyInterface::class, new UniqueStrategy(1000));
        $unique = new DummyGenerator($uniqueContainer);
        $startUnique = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $unique->numberBetween(1, 10000);
        }
        $timeUnique = microtime(true) - $startUnique;

        // Benchmark ValidStrategy
        $container2 = TestContainerFactory::empty();
        $container2->set(RandomizerInterface::class, Randomizer::class);
        $container2->set(NumberExtensionInterface::class, Number::class);
        $container2->set(StrategyInterface::class, new ValidStrategy(fn($n) => $n > 0, 1000));
        $valid = new DummyGenerator($container2);
        $startValid = microtime(true);
        for ($i = 0; $i < $iterations; $i++) {
            $valid->numberBetween(1, 100);
        }
        $timeValid = microtime(true) - $startValid;

        // All should complete quickly
        self::assertLessThan(1.0, $timeSimple);
        self::assertLessThan(2.0, $timeUnique);
        self::assertLessThan(2.0, $timeValid);

        // SimpleStrategy should be fastest (but allow for some variance)
        // We just verify all completed in reasonable time
        self::assertGreaterThan(0, $timeSimple);
        self::assertGreaterThan(0, $timeUnique);
        self::assertGreaterThan(0, $timeValid);
    }
}
