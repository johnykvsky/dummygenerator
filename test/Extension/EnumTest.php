<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Enum;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Test\Fixtures\BarProvider;
use DummyGenerator\Test\Fixtures\SuitBackedIntEnum;
use DummyGenerator\Test\Fixtures\SuitBackedStringEnum;
use DummyGenerator\Test\Fixtures\SuitEnum;
use PHPUnit\Framework\TestCase;
use UnitEnum;

class EnumTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(EnumExtensionInterface::class, Enum::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testStringValue(): void
    {
        self::assertIsString($this->generator->enumValue(SuitBackedStringEnum::class));
    }

    public function testIntValue(): void
    {
        self::assertIsInt($this->generator->enumValue(SuitBackedIntEnum::class));
    }

    public function testValueForInvalidClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->enumValue('none_enum_string');
    }

    public function testValueForNonBacked(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Argument should be backed PHP Enum');
        $this->generator->enumValue(SuitEnum::class);
    }

    public function testElement(): void
    {
        self::assertInstanceOf(UnitEnum::class, $this->generator->enumCase(SuitEnum::class));
    }

    public function testBackedElement(): void
    {
        self::assertInstanceOf(UnitEnum::class, $this->generator->enumCase(SuitBackedStringEnum::class));
    }

    public function testElementForInvalidClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->enumCase('none_enum_string');
    }

    public function testElementForNonEnumClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->enumCase(BarProvider::class);
    }
}