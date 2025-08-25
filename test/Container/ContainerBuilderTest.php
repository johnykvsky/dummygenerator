<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\DefinitionContainerBuilder;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;
use PHPUnit\Framework\TestCase;

class ContainerBuilderTest extends TestCase
{
    public function testCanGetBaseDefinitions(): void
    {
        $container = DefinitionContainerBuilder::base();

        self::assertTrue($container->has(DateTimeExtensionInterface::class));
        self::assertTrue($container->has(EnumExtensionInterface::class));
        self::assertTrue($container->has(StringsExtensionInterface::class));
        self::assertFalse($container->has(CoordinatesExtensionInterface::class));
        self::assertFalse($container->has(PersonExtensionInterface::class));
    }

    public function testCanGetDefaultDefinitions(): void
    {
        $container = DefinitionContainerBuilder::default();

        self::assertTrue($container->has(CoordinatesExtensionInterface::class));
        self::assertTrue($container->has(PersonExtensionInterface::class));
        self::assertFalse($container->has(AddressExtensionInterface::class));
    }

    public function testCanGetAllDefinitions(): void
    {
        $container = DefinitionContainerBuilder::all();

        self::assertTrue($container->has(CoordinatesExtensionInterface::class));
        self::assertTrue($container->has(PersonExtensionInterface::class));
        self::assertTrue($container->has(AddressExtensionInterface::class));
    }
}
