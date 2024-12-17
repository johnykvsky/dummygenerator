<?php

declare(strict_types=1);

namespace DummyGenerator\Strategy;

class SimpleStrategy implements StrategyInterface
{
    public function generate(string $name, callable $callback): mixed
    {
        return $callback();
    }
}
