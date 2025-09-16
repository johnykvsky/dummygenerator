<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\DefinitionContainerBuilder;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\AnyDateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;
use DummyGenerator\Definitions\Extension\BloodExtensionInterface;
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

        self::assertTrue($container->has(AnyDateTimeExtensionInterface::class));
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

        self::assertTrue($container->has(AddressExtensionInterface::class));
        self::assertTrue($container->has(BarcodeExtensionInterface::class));
        self::assertTrue($container->has(BloodExtensionInterface::class));
        self::assertTrue($container->has(DateTimeExtensionInterface::class));
    }
}
