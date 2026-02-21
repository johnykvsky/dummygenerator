<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Strategy\ChanceStrategy;
use DummyGenerator\Strategy\CompositeStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use PHPUnit\Framework\TestCase;

final class CompositeStrategyTest extends TestCase
{
    public function testInvalidStrategyThrows(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('All strategies must implement StrategyInterface');

        new CompositeStrategy([new \stdClass()]);
    }

    public function testGetStrategiesReturnsOriginalOrder(): void
    {
        $a = new class implements StrategyInterface {
            public function generate(string $name, callable $callback): mixed
            {
                return $callback();
            }
        };
        $b = new class implements StrategyInterface {
            public function generate(string $name, callable $callback): mixed
            {
                return $callback();
            }
        };

        $composite = new CompositeStrategy([$a, $b]);

        self::assertSame([$a, $b], $composite->getStrategies());
    }

    public function testWithStrategyAppends(): void
    {
        $a = new class implements StrategyInterface {
            public function generate(string $name, callable $callback): mixed
            {
                return $callback();
            }
        };
        $b = new class implements StrategyInterface {
            public function generate(string $name, callable $callback): mixed
            {
                return $callback();
            }
        };

        $composite = new CompositeStrategy([$a]);
        $updated = $composite->withStrategy($b);

        self::assertSame([$a], $composite->getStrategies());
        self::assertSame([$a, $b], $updated->getStrategies());
    }
    public function testStrategiesWrapLeftToRight(): void
    {
        $calls = [];

        $a = new class($calls) implements StrategyInterface {
            /** @var string[] */
            private array $calls;

            public function __construct(array &$calls)
            {
                $this->calls = &$calls;
            }

            public function generate(string $name, callable $callback): mixed
            {
                $this->calls[] = 'A';
                return $callback();
            }
        };

        $b = new class($calls) implements StrategyInterface {
            /** @var string[] */
            private array $calls;

            public function __construct(array &$calls)
            {
                $this->calls = &$calls;
            }

            public function generate(string $name, callable $callback): mixed
            {
                $this->calls[] = 'B';
                return $callback();
            }
        };

        $composite = new CompositeStrategy([$a, $b]);

        $composite->generate('test', function () use (&$calls): string {
            $calls[] = 'core';
            return 'ok';
        });

        self::assertSame(['B', 'A', 'core'], $calls);
    }

    public function testShortCircuitSkipsInnerStrategies(): void
    {
        $state = ['inner_calls' => 0, 'core_calls' => 0];

        $inner = new class($state) implements StrategyInterface {
            /** @var array{inner_calls: int, core_calls: int} */
            private array $state;

            public function __construct(array &$state)
            {
                $this->state = &$state;
            }

            public function generate(string $name, callable $callback): mixed
            {
                $this->state['inner_calls']++;
                return $callback();
            }
        };

        $chance = new ChanceStrategy(0.0, default: 'default');
        $composite = new CompositeStrategy([$inner, $chance]);

        $result = $composite->generate('test', function () use (&$state): string {
            $state['core_calls']++;
            return 'core';
        });

        self::assertSame('default', $result);
        self::assertSame(0, $state['inner_calls']);
        self::assertSame(0, $state['core_calls']);
    }
}
