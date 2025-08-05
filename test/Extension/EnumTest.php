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
        self::assertIsString($this->generator->value(SuitBackedStringEnum::class));
    }

    public function testIntValue(): void
    {
        self::assertIsInt($this->generator->value(SuitBackedIntEnum::class));
    }

    public function testValueForInvalidClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->value('none_enum_string');
    }

    public function testValueForNonBacked(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Argument should be backed PHP Enum');
        $this->generator->value(SuitEnum::class);
    }

    public function testElement(): void
    {
        self::assertInstanceOf(UnitEnum::class, $this->generator->element(SuitEnum::class));
    }

    public function testElementForInvalidClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->element('none_enum_string');
    }

    public function testElementForNonEnumClass(): void
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Invalid PHP Enum');
        $this->generator->element(BarProvider::class);
    }
}