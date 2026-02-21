<?php

declare(strict_types=1);

namespace DummyGenerator\Test\ProviderPack;

use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Test\Fixtures\ProviderColor;
use DummyGenerator\Test\Fixtures\ProviderDefinitionPack;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

/**
 * Tests for the ProviderPack system.
 *
 * ProviderPacks allow grouping custom provider definitions together
 * for easier registration with the generator.
 *
 * @group provider-pack
 * @group integration
 */
class ProviderPackTest extends TestCase
{
    /**
     * Test that ProviderPack can be used to register custom providers.
     *
     * @group provider-pack
     */
    public function testProviderPackRegistersCustomProviders(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);

        $generator = new DummyGenerator($container);
        $generatorWithProvider = $generator->withProvider(new ProviderDefinitionPack());

        // Should be able to access the custom color provider
        $extension = $generatorWithProvider->ext(ColorExtensionInterface::class);
        self::assertInstanceOf(ProviderColor::class, $extension);
    }

    /**
     * Test that ProviderPack all() method returns array of definitions.
     *
     * @group provider-pack
     */
    public function testProviderPackAllReturnsArrayOfDefinitions(): void
    {
        $pack = new ProviderDefinitionPack();
        $definitions = $pack->all();

        self::assertIsArray($definitions);
        self::assertArrayHasKey(ColorExtensionInterface::class, $definitions);
        self::assertEquals(ProviderColor::class, $definitions[ColorExtensionInterface::class]);
    }

    /**
     * Test that multiple ProviderPacks can be used sequentially.
     *
     * @group provider-pack
     */
    public function testMultipleProviderPacksCanBeUsedSequentially(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);

        $generator = new DummyGenerator($container);

        // Apply first pack
        $gen1 = $generator->withProvider(new ProviderDefinitionPack());
        self::assertInstanceOf(ProviderColor::class, $gen1->ext(ColorExtensionInterface::class));

        // Apply second pack (should work on same instance)
        $gen2 = $gen1->withProvider(new ProviderDefinitionPack());
        self::assertInstanceOf(ProviderColor::class, $gen2->ext(ColorExtensionInterface::class));
    }

    /**
     * Test that ProviderPack definitions override container definitions.
     *
     * @group provider-pack
     */
    public function testProviderPackOverridesContainerDefinitions(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(ColorExtensionInterface::class, \DummyGenerator\Core\Color::class);

        $generator = new DummyGenerator($container);

        // Before provider pack, should use core Color
        $coreColor = $generator->ext(ColorExtensionInterface::class);
        self::assertInstanceOf(\DummyGenerator\Core\Color::class, $coreColor);

        // After provider pack, should use custom ProviderColor
        $generatorWithProvider = $generator->withProvider(new ProviderDefinitionPack());
        $customColor = $generatorWithProvider->ext(ColorExtensionInterface::class);
        self::assertInstanceOf(ProviderColor::class, $customColor);
    }

    /**
     * Test that generator with ProviderPack is immutable.
     *
     * @group provider-pack
     */
    public function testGeneratorWithProviderPackIsImmutable(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);

        $generator = new DummyGenerator($container);
        $generatorWithProvider = $generator->withProvider(new ProviderDefinitionPack());

        // Original generator should not be affected
        self::assertNotSame($generator, $generatorWithProvider);
    }

    /**
     * Test that ProviderPack provides custom color names.
     *
     * @group provider-pack
     */
    public function testProviderPackProvidesCustomColorNames(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);

        $generator = new DummyGenerator($container);
        $generatorWithProvider = $generator->withProvider(new ProviderDefinitionPack());

        // ProviderColor should provide custom Polish color names
        $colorName = $generatorWithProvider->safeColorName();
        self::assertIsString($colorName);
        self::assertContains($colorName, ['czarny', 'brązowy', 'zielony']);
    }
}
