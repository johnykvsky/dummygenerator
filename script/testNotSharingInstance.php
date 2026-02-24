<?php

declare(strict_types = 1);

use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;

require __DIR__ . '/../vendor/autoload.php';

echo PHP_EOL . "values should be the same, as same randomizer seed is used:" . PHP_EOL;

$container = DiContainerFactory::all();
$container->set(RandomizerInterface::class, new XoshiroRandomizer(123));
$generator = new DummyGenerator($container);

echo $generator->uuid4() . PHP_EOL;;

$container2 = DiContainerFactory::all();
$container2->set(RandomizerInterface::class, new XoshiroRandomizer(123));
$generator2 = new DummyGenerator($container2);

echo $generator2->uuid4() . PHP_EOL;;



