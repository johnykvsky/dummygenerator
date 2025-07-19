<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Enum;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Test\Fixtures\SuitBackedEnum;
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

    public function testValue(): void
    {
        self::assertIsString($this->generator->value(SuitBackedEnum::class));
    }

    public function testElement(): void
    {
        self::assertInstanceOf(UnitEnum::class, $this->generator->element(SuitEnum::class));
    }
}