<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Strings;
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class StringsTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(StringsExtensionInterface::class, Strings::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testString(): void
    {
        self::assertNotEmpty($this->generator->string());
    }

    public function testStringLength(): void
    {
        $string = $this->generator->string(5, 5);
        self::assertEquals(5, strlen($string));
    }

    public function testStringLongLength(): void
    {
        $string = $this->generator->string(100, 100);
        self::assertEquals(100, strlen($string));
    }

    public function testStringPool(): void
    {
        $string = $this->generator->string(3, 3, '11111');
        self::assertEquals('111', $string);
    }

}
