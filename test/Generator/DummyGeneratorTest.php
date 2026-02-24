<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Generator;

use DummyGenerator\Core\Color;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Definitions\Exception\DefinitionNotFound;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\ChanceStrategy;
use DummyGenerator\Strategy\CompositeStrategy;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ValidStrategy;
use DummyGenerator\Test\Fixtures\BarProvider;
use DummyGenerator\Test\Fixtures\BazProvider;
use DummyGenerator\Test\Fixtures\FooProvider;
use DummyGenerator\Test\Fixtures\ProviderColor;
use DummyGenerator\Test\Fixtures\ProviderDefinitionPack;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

class DummyGeneratorTest extends TestCase
{
    public function testCanGetExtensionFromGenerator(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set('some_name', fn () => new class implements ExtensionInterface {
        });

        $generator = new DummyGenerator($container);

        self::assertInstanceOf(ExtensionInterface::class, $generator->ext('some_name'));
    }

    public function testMissingExtensionThrowsException(): void
    {
        $container = TestContainerFactory::empty(true);

        $generator = new DummyGenerator($container);

        $this->expectException(DefinitionNotFound::class);
        $generator->ext('some_id');
    }

    public function testParseMagicString(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        $parsed = $generator->parse('this is some {{ foo }} magic!');

        self::assertEquals('this is some foobar magic!', $parsed);
    }

    public function testParseRegularString(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('this is not some magic', $generator->parse('this is not some magic'));
    }

    public function testStrategyChange(): void
    {
        $container = TestContainerFactory::withStrategy(new class implements \DummyGenerator\Strategy\StrategyInterface {
            public function generate(string $name, callable $callback): mixed
            {
                return 'strategy-result';
            }
        });
        $container->set(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);
        self::assertSame('strategy-result', $generator->foo());
    }

    public function testChainedStrategiesShortCircuit(): void
    {
        $container = TestContainerFactory::withStrategy(new CompositeStrategy([
            new UniqueStrategy(5),
            new ChanceStrategy(0.0, default: 'default'),
        ]), true);
        $container->set(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertSame('default', $generator->foo());
    }

    public function testValidStrategyChainingEnsuresPredicate(): void
    {
        $provider = new CounterProvider();
        $container = TestContainerFactory::withStrategy(new ValidStrategy(static fn (int $value): bool => $value % 2 === 0, 10), true);
        $container->set(CounterProvider::class, $provider);

        $generator = new DummyGenerator($container);

        self::assertSame(2, $generator->next());
        self::assertSame(4, $generator->next());
        self::assertSame(4, $provider->getValue());
    }

    public function testChanceShortCircuitsBeforeValid(): void
    {
        $provider = new CounterProvider();
        $container = TestContainerFactory::withStrategy(new CompositeStrategy([
            new ValidStrategy(static fn (int $value): bool => $value % 2 === 0, 10),
            new ChanceStrategy(0.0, default: 'default'),
        ]), true);
        $container->set(CounterProvider::class, $provider);

        $generator = new DummyGenerator($container);

        self::assertSame('default', $generator->next());
        self::assertSame(0, $provider->getValue());
    }

    public function testChanceThenValidAllowsChanceToPassThrough(): void
    {
        $provider = new CounterProvider();
        $container = TestContainerFactory::withStrategy(new CompositeStrategy([
            new ChanceStrategy(1.0),
            new ValidStrategy(static fn (int $value): bool => $value % 2 === 0, 10),
        ]), true);
        $container->set(CounterProvider::class, $provider);

        $generator = new DummyGenerator($container);

        self::assertSame(2, $generator->next());
        self::assertSame(2, $provider->getValue());
    }


    public function testProviderChange(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(ColorExtensionInterface::class, Color::class);

        $generator = new DummyGenerator($container);

        self::assertInstanceOf(Color::class, $generator->ext(ColorExtensionInterface::class));
        self::assertInstanceOf(ProviderColor::class, $generator->withProvider(new ProviderDefinitionPack())->ext(ColorExtensionInterface::class));
        self::assertInstanceOf(Color::class, $generator->ext(ColorExtensionInterface::class));
    }

    public function testExtensionProcessing(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('foobar', $generator->foo());
        self::assertEquals('bazastral', $generator->fooManChu('astral'));
    }

    public function testInvalidMethodProcessing(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(BarProvider::class, new BarProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('bar', $generator->bar());

        $this->expectException(\InvalidArgumentException::class);
        self::assertEquals('barfoo', $generator->barbaz());
    }

    public function testOrderOfAddingMatters(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(FooProvider::class, new FooProvider());
        $container->set(BarProvider::class, new BarProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('foo', $generator->bar());

        $container = TestContainerFactory::empty(true);
        $container->set(BarProvider::class, new BarProvider());
        $container->set(FooProvider::class, new FooProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('bar', $generator->bar());
    }

    public function testCanOverwriteExtension(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(FooProvider::class, new FooProvider());
        $container->set(FooProvider::class, new BazProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('baz', $generator->baz());

        $this->expectException(\InvalidArgumentException::class);
        self::assertEquals('foobar', $generator->foo());
    }

    public function testAddDefinition(): void
    {
        $container = TestContainerFactory::empty(true);
        $container->set(FooProvider::class, new FooProvider());
        $container->set(BarProvider::class, new BarProvider());

        $generator = new DummyGenerator($container);

        self::assertEquals('foo', $generator->bax());
        self::assertEquals('bar', $generator->bars());

        $generator = $generator->withDefinition(FooProvider::class, new BazProvider());

        self::assertEquals('baz', $generator->bax());
    }
}

final class CounterProvider implements ExtensionInterface
{
    private int $value = 0;

    public function next(): int
    {
        return ++$this->value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
