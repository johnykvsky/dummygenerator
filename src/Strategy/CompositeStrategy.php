<?php

declare(strict_types = 1);

namespace DummyGenerator\Strategy;

class CompositeStrategy implements StrategyInterface
{
    /** @var StrategyInterface[] */
    protected array $strategies;

    /** @param StrategyInterface[] $strategies */
    public function __construct(array $strategies)
    {
        foreach ($strategies as $strategy) {
            if (!$strategy instanceof StrategyInterface) {
                throw new \InvalidArgumentException('All strategies must implement StrategyInterface');
            }
        }

        $this->strategies = array_values($strategies);
    }

    /** @return StrategyInterface[] */
    public function getStrategies(): array
    {
        return $this->strategies;
    }

    public function withStrategy(StrategyInterface $strategy): self
    {
        $strategies = $this->strategies;
        $strategies[] = $strategy;

        return new self($strategies);
    }

    public function generate(string $name, callable $callback): mixed
    {
        $run = function (int $index) use (&$run, $name, $callback): mixed {
            if ($index < 0) {
                return $callback();
            }

            $strategy = $this->strategies[$index];
            $called = false;
            $next = static function () use (&$called, $run, $index): mixed {
                $called = true;
                return $run($index - 1);
            };

            $result = $strategy->generate($name, $next);

            if ($strategy instanceof ShortCircuitStrategyInterface && !$called) {
                return new ShortCircuitResult($result);
            }

            return $result;
        };

        $result = $run(count($this->strategies) - 1);

        return $result instanceof ShortCircuitResult ? $result->value : $result;
    }
}
