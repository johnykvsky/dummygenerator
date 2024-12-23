<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Generator;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Exception\DefinitionNotFound;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Test\Fixtures\BarProvider;
use DummyGenerator\Test\Fixtures\BazProvider;
use DummyGenerator\Test\Fixtures\FooProvider;
use PHPUnit\Framework\TestCase;

class DummyGeneratorTest extends TestCase
{
    public function testCanGetExtensionFromGenerator(): void
    {
        $container = new DefinitionContainer(['some_name' => fn () => new class implements ExtensionInterface {
        }]);

        $generator = new DummyGenerator($container);

        self::assertInstanceOf(ExtensionInterface::class, $generator->ext('some_name'));
    }

    public function testMissingExtensionThrowsException(): void
    {
        $container = new DefinitionContainer([]);

        $generator = new DummyGenerator($container);

        $this->expectException(DefinitionNotFound::class);
        $generator->ext('some_id');
    }

    public function testParseMagicString(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        $parsed = $generator->parse('this is some {{ foo }} magic!');

        self::assertEquals('this is some foobar magic!', $parsed);
    }

    public function testParseRegularString(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('this is not some magic', $generator->parse('this is not some magic'));
    }

    public function testStrategyChange(): void
    {
        $generator = new DummyGenerator(new DefinitionContainer([]));

        self::assertTrue($generator->usedStrategy(SimpleStrategy::class));

        $uniqueGenerator = $generator->withStrategy(new UniqueStrategy(5));

        self::assertTrue($generator->usedStrategy(SimpleStrategy::class));
        self::assertTrue($uniqueGenerator->usedStrategy(UniqueStrategy::class));
    }

    public function testExtensionProcessing(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('foobar', $generator->foo());
        self::assertEquals('bazastral', $generator->fooManChu('astral'));
    }

    public function testInvalidMethodProcessing(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(BarProvider::class, new BarProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('bar', $generator->bar());

        $this->expectException(\InvalidArgumentException::class);
        self::assertEquals('barfoo', $generator->barbaz());
    }

    public function testOrderOfAddingMatters(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(FooProvider::class, new FooProvider());
        $container->add(BarProvider::class, new BarProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('foo', $generator->bar());

        $container = new DefinitionContainer([]);
        $container->add(BarProvider::class, new BarProvider());
        $container->add(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('bar', $generator->bar());
    }

    public function testCanOverwriteExtension(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(FooProvider::class, new FooProvider());
        $container->add(FooProvider::class, new BazProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('baz', $generator->baz());

        $this->expectException(\InvalidArgumentException::class);
        self::assertEquals('foobar', $generator->foo());
    }

    public function testAddDefinition(): void
    {
        $container = new DefinitionContainer([]);
        $container->add(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);
        $generator->addDefinition(FooProvider::class, new BazProvider());

        self::assertEquals('baz', $generator->baz());
    }
}
