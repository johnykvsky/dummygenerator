# Strategies

Strategies wrap generator method calls with additional behavior.

## Available Strategies

### SimpleStrategy

Default strategy. It returns the generated value directly.

### UniqueStrategy

Ensures unique values per method name. Throws when the retry limit is exceeded.

### ValidStrategy

Accepts a validator and retries until the value is valid or the retry limit is exceeded.

### ChanceStrategy

Returns the generated value only some percentage of the time. Otherwise, returns a default value.

## Chaining Strategies

Chaining is left-to-right in the builder, and later strategies wrap earlier ones.

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
```

In this example, the chance strategy decides whether a value is produced, and uniqueness is only enforced when a value is produced.

## Short-Circuit Behavior

Strategies like `ChanceStrategy` can return without invoking the inner generator. Short-circuited results skip inner strategies.
