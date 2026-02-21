# Migration from FakerPHP/Faker

This guide helps you move from `fakerphp/faker` to DummyGenerator. It focuses on conceptual mapping and common usage patterns.

## Quick Mapping

- Faker “formatters” map to DummyGenerator extension methods.
- Faker “providers” map to DummyGenerator extensions.
- Faker modifiers (`unique()`, `optional()`, `valid()`) map to DummyGenerator strategies.

Faker’s generator dispatches unknown method calls to attached providers. DummyGenerator uses a similar dynamic call pattern but resolves methods from its extension container.

## Creating a Generator

Faker:
```php
$faker = Faker\Factory::create();
```
DummyGenerator:
```php
use DummyGenerator\DummyGenerator;

$generator = DummyGenerator::create();
```

Faker uses a factory to build a generator with default providers. DummyGenerator constructs a generator with all default extensions preloaded.

## Providers / Extensions

Faker adds providers to a generator, and each provider’s public methods become formatters. 

DummyGenerator equivalent is replacing or adding an extension in the container:

```php
$generator = $generator->withDefinition(
    PersonExtensionInterface::class,
    MyPerson::class
);
```

## Modifiers and Strategies

Faker modifiers are invoked before a formatter call:

- `unique()` forces unique values.
- `optional()` returns a default some percentage of the time.
- `valid()` retries until a validator succeeds.

DummyGenerator uses strategies that can be chained left-to-right:

```php
use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ChanceStrategy;
use DummyGenerator\Strategy\ValidStrategy;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Strategy\CompositeStrategy;
use DummyGenerator\Strategy\StrategyInterface;

$container = DiContainerFactory::all(); // returns DummyContainer
$container->set(StrategyInterface::class, new CompositeStrategy([
    new UniqueStrategy(1000),
    new ChanceStrategy(0.5, default: null),
    new ValidStrategy(fn ($v) => $v !== null),
]));
$generator = new DummyGenerator($container);
```

Ordering matters: later strategies wrap earlier ones.

## Locale Support

Faker supports locales in its factory (`Factory::create('fr_FR')`) and falls back to `en_US` when a localized provider is missing.

DummyGenerator by default uses generic English language for all core generators. Other languages are available in separate package `johnykvsky/dummyproviders` - those are `en_GB`, `en_US` and `pl_PL`.
Converting languages from FakerPHP to DummyGenerator is quite easy, those three are good examples how it can be done.

## Seeding

Faker exposes a `seed()` method on the generator to make results reproducible.

DummyGenerator uses a seeded randomizer you provide in the container (e.g., `XoshiroRandomizer`) to achieve the same effect.

## Formatter Lists

Faker documents all built-in formatters per provider and locale.

DummyGenerator exposes its full method list in `docs/generators.md`.

## Date/Time Behavior

Faker date/time formatters accept optional bounds and timezones.

DummyGenerator provides equivalent methods (see `DateTime` extension in `docs/generators.md`).

## Compatibility Notes

- Faker’s `optional()` is most closely matched by `ChanceStrategy` with a default value.
- Faker’s `unique()` and `valid()` map directly to `UniqueStrategy` and `ValidStrategy`.
