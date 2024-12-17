<?php

declare(strict_types=1);

namespace DummyGenerator\Strategy;

interface StrategyInterface
{
    public function generate(string $name, callable $callback): mixed;
}
