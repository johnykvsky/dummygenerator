<?php

declare(strict_types = 1);

use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\CompositeStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ValidStrategy;

require __DIR__ . '/../vendor/autoload.php';

$container = DiContainerFactory::all();
$container->set(StrategyInterface::class, new CompositeStrategy([
    new UniqueStrategy(1000),
    new ValidStrategy(fn($value) => $value > 95),
]));
$generator = new DummyGenerator($container);

try {
    for ($i = 0; $i < 10; $i++) {
        $n = $generator->numberBetween(1, 100);
        echo "Iteration $i: " . $n  . PHP_EOL;
    }
} catch (Exception $exception) {
    echo $exception->getMessage() . PHP_EOL;
}
