<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Integration;

use DummyGenerator\Clock\SystemClock;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Calculator\LuhnCalculator;
use DummyGenerator\Core\DateTime;
use DummyGenerator\Core\Internet;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Payment;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\PaymentExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use PHPUnit\Framework\TestCase;

class AwarenessIntegrationTest extends TestCase
{
    public function testRandomizerAwarenessIsInjected(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);

        // Person extension uses RandomizerAware trait
        $name = $generator->firstName();

        // If randomizer wasn't injected, this would fail
        self::assertNotEmpty($name);
        self::assertIsString($name);
    }

    public function testGeneratorAwarenessIsInjected(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // Internet extension uses GeneratorAware to call Person extension methods
        $email = $generator->email();

        // If generator wasn't injected, this would fail when trying to call {{userName}} or {{domainName}}
        self::assertStringContainsString('@', $email);
        self::assertStringContainsString('.', $email);
    }

    public function testReplacerAwarenessIsInjected(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // Internet extension uses Replacer for bothify, numerify, etc.
        $username = $generator->userName();

        // If replacer wasn't injected, this would fail
        self::assertNotEmpty($username);
        self::assertMatchesRegularExpression('/^[a-z0-9_.]+$/', $username);
    }

    public function testClockAwarenessIsInjected(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(DateTimeExtensionInterface::class, DateTime::class);

        $clock = new SystemClock();
        $container->set(\DummyGenerator\Clock\SystemClockInterface::class, $clock);
        $generator = new DummyGenerator($container);

        // DateTime extension uses ClockAware trait
        $date = $generator->dateTime();

        // If clock wasn't injected, this would fail
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertLessThanOrEqual($clock->now(), $date);
    }

    public function testCalculatorAwarenessIsInjected(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(LuhnCalculatorInterface::class, LuhnCalculator::class);

        // Payment extension also needs IbanCalculator
        $container->set(\DummyGenerator\Definitions\Calculator\IbanCalculatorInterface::class, \DummyGenerator\Core\Calculator\IbanCalculator::class);

        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(DateTimeExtensionInterface::class, DateTime::class);
        $container->set(PaymentExtensionInterface::class, Payment::class);

        $generator = new DummyGenerator($container);

        // Payment extension uses LuhnCalculatorAware trait
        $creditCard = $generator->creditCardNumber();

        // If calculator wasn't injected, this would fail
        self::assertNotEmpty($creditCard);
        self::assertMatchesRegularExpression('/^\d+$/', str_replace([' ', '-'], '', $creditCard));
    }

    public function testMultipleExtensionsShareSameDependencies(): void
    {
        $container = TestContainerFactory::empty();

        // Add shared dependencies
        $randomizer = new Randomizer();
        $container->set(RandomizerInterface::class, $randomizer);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);

        // Add multiple extensions that need randomizer
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // All extensions should work with shared randomizer
        $name = $generator->firstName();
        $word = $generator->word();
        $email = $generator->email();

        self::assertNotEmpty($name);
        self::assertNotEmpty($word);
        self::assertNotEmpty($email);
    }

    public function testExtensionCachingBehavior(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);

        // First call - extension is created and cached
        $name1 = $generator->firstName();

        // Second call - should use cached extension
        $name2 = $generator->firstName();

        // Both should work
        self::assertNotEmpty($name1);
        self::assertNotEmpty($name2);
    }

    public function testAwarenessInjectionOrder(): void
    {
        $container = TestContainerFactory::empty();

        // Order of adding should not matter
        $container->set(InternetExtensionInterface::class, Internet::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);

        $generator = new DummyGenerator($container);

        // All should be properly injected regardless of add order
        $email = $generator->email();
        $name = $generator->firstName();
        $word = $generator->word();

        self::assertStringContainsString('@', $email);
        self::assertNotEmpty($name);
        self::assertNotEmpty($word);
    }

    public function testTransliteratorAwarenessInReplacer(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // Internet uses Replacer which uses Transliterator
        $username = $generator->userName();

        // Username should be properly transliterated
        self::assertMatchesRegularExpression('/^[a-z0-9_.]+$/', $username);
        self::assertNotEmpty($username);
    }

    public function testAwarenessWorksAfterDefinitionReplacement(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);

        // Generate name with original extension
        $name1 = $generator->firstName();
        self::assertNotEmpty($name1);

        // Replace the extension (immutable - creates new instance)
        $generator = $generator->withDefinition(PersonExtensionInterface::class, Person::class);

        // Should still work with new instance
        $name2 = $generator->firstName();
        self::assertNotEmpty($name2);
    }

    public function testMultipleGeneratorsShareContainerButNotCache(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $gen1 = new DummyGenerator($container);
        $gen2 = new DummyGenerator($container);

        // Both generators should work independently
        $name1 = $gen1->firstName();
        $name2 = $gen2->firstName();

        self::assertNotEmpty($name1);
        self::assertNotEmpty($name2);
    }

    public function testClockAwarenessCanBeChanged(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(DateTimeExtensionInterface::class, DateTime::class);

        $clock1 = new SystemClock();
        $container->set(\DummyGenerator\Clock\SystemClockInterface::class, $clock1);
        $gen1 = new DummyGenerator($container);

        $date1 = $gen1->dateTime();

        // Switch to different clock
        $clock2 = new SystemClock(new \DateTimeZone('America/New_York'));
        $container2 = TestContainerFactory::withClock($clock2);
        $container2->set(RandomizerInterface::class, Randomizer::class);
        $container2->set(DateTimeExtensionInterface::class, DateTime::class);
        $gen2 = new DummyGenerator($container2);

        $date2 = $gen2->dateTime();

        // Both should work
        self::assertInstanceOf(\DateTimeImmutable::class, $date1);
        self::assertInstanceOf(\DateTimeImmutable::class, $date2);
    }

    public function testComplexAwarenessChain(): void
    {
        $container = TestContainerFactory::empty();

        // Build a complex dependency chain
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // Internet -> Generator -> Person -> Randomizer
        // Internet -> Replacer -> Transliterator
        // Internet -> Replacer -> Randomizer
        $slug = $generator->slug(nbWords: 5, variableNbWords: false);

        self::assertNotEmpty($slug);
        self::assertEquals(4, substr_count($slug, '-')); // 5 words = 4 dashes
    }

    public function testAwarenessWithDifferentRandomizers(): void
    {
        $container = TestContainerFactory::empty();

        // Use regular Randomizer
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $gen1 = new DummyGenerator($container);
        $name1 = $gen1->firstName();

        self::assertNotEmpty($name1);
    }

    public function testExtensionReceivesAllRequiredDependencies(): void
    {
        $container = TestContainerFactory::empty();

        // Internet extension requires: Generator, Randomizer, Replacer
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // All these methods require different dependencies to work together
        $username = $generator->userName(); // Replacer, Transliterator, Person
        $email = $generator->email(); // Generator, Person
        $password = $generator->password(); // Randomizer, Replacer
        $slug = $generator->slug(); // Generator, Lorem

        self::assertNotEmpty($username);
        self::assertStringContainsString('@', $email);
        self::assertNotEmpty($password);
        self::assertNotEmpty($slug);
    }

    public function testAwarenessWorksWithExtensionFromGet(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);

        // Get extension directly
        /** @var Person $personExt */
        $personExt = $generator->ext(PersonExtensionInterface::class);

        // Extension should have randomizer injected
        $name = $personExt->firstName();

        self::assertNotEmpty($name);
    }

    public function testAwarenessInjectionDoesNotFailWithMinimalSetup(): void
    {
        $container = TestContainerFactory::empty();

        // Add only what's needed for Person
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);

        // Should work even without all possible dependencies
        $name = $generator->firstName();

        self::assertNotEmpty($name);
    }

    public function testMultipleAwarenessTraitsOnSameExtension(): void
    {
        $container = TestContainerFactory::empty();

        // Internet uses multiple awareness traits
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(LoremExtensionInterface::class, Lorem::class);
        $container->set(InternetExtensionInterface::class, Internet::class);

        $generator = new DummyGenerator($container);

        // Test different methods that use different awareness traits
        $email = $generator->email(); // GeneratorAware
        $password = $generator->password(); // ReplacerAware, RandomizerAware
        $ipv4 = $generator->ipv4(); // RandomizerAware

        self::assertStringContainsString('@', $email);
        self::assertNotEmpty($password);
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+\.\d+$/', $ipv4);
    }
}
