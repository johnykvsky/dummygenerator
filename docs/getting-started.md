# Getting Started

DummyGenerator is a modern PHP library for generating realistic fake data for tests, seeding, and demos.

## Install

```bash
composer require johnykvsky/dummygenerator
```

## Basic Usage

```php
use DummyGenerator\DummyGenerator;

$generator = DummyGenerator::create();

echo $generator->firstName();
echo $generator->lastName();
echo $generator->email();
echo $generator->address();
```

## Strategies In One Minute

```php
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\ChanceStrategy;
use DummyGenerator\Strategy\CompositeStrategy;
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\StrategyInterface;

$container = DiContainerFactory::all(); // returns DummyContainer
$container->set(StrategyInterface::class, new CompositeStrategy([
    new UniqueStrategy(1000),
    new ChanceStrategy(0.5, default: null),
]));
$generator = new DummyGenerator($container);

$maybeUniqueName = $generator->firstName();
```

## Templates

```php
$template = '{{ firstName }} {{ lastName }} <{{ email }}>';
$result = $generator->parse($template);
```
