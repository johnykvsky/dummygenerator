<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Deprecation;

use DummyGenerator\Core\Person;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Test that constructor injection works correctly after migration from awareness traits.
 */
final class DeprecationWarningTest extends TestCase
{
    public function testConstructorInjectionWorksWithoutDeprecationWarnings(): void
    {
        // Set up error handler to capture any deprecation warnings
        $deprecations = [];
        set_error_handler(function (int $errno, string $errstr) use (&$deprecations) {
            if ($errno === E_USER_DEPRECATED) {
                $deprecations[] = $errstr;
            }
            return true;
        });

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);
        $generator->name();

        restore_error_handler();

        // No deprecation warnings should be triggered (constructor injection is used)
        self::assertEmpty($deprecations, 'No deprecation warnings should be triggered with constructor injection');
    }

    public function testExtensionsWorkWithConstructorInjection(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(PersonExtensionInterface::class, Person::class);

        $generator = new DummyGenerator($container);

        $result = $generator->firstName();

        self::assertIsString($result);
        self::assertNotEmpty($result);
    }
}
