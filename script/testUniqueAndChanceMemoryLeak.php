<?php

declare(strict_types = 1);

use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\ChanceStrategy;
use DummyGenerator\Strategy\CompositeStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Strategy\UniqueStrategy;

require __DIR__ . '/../vendor/autoload.php';

$container = DiContainerFactory::all();
$container->set(StrategyInterface::class, new CompositeStrategy([
    new UniqueStrategy(1000),
    new ChanceStrategy(0.5, default: null),
]));
$generator = new DummyGenerator($container);

echo "Before: " . (memory_get_usage(true) / 1024 / 1024) . " MB\n";

for ($i = 0; $i < 15; $i++) {
    $generator->email();
    echo "Iteration $i: " . (memory_get_usage(true) / 1024 / 1024) . " MB\n";
}
