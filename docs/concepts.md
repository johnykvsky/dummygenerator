# Concepts

DummyGenerator is built from a few core concepts.

## Generator

`DummyGenerator` is the main facade. It routes method calls to extensions and applies strategies around each call.

## Extensions

Extensions provide the actual data generation methods like `firstName()` or `uuid4()`.

Extensions live in `src/Core` and implement interfaces in `src/Definitions/Extension`.

## Container

DummyGenerator uses a `DummyContainerInterface` for dependency injection. The default implementation (`DummyContainer`) is built by `DiContainerFactory` and is backed by PHP-DI internally. Use constructor autowiring or `#[Inject('service-id')]` attributes for explicit wiring.

## Strategies

Strategies wrap generation with behavior such as uniqueness, validation, or chance-based generation.

Strategies are configured via the container by registering a `StrategyInterface` implementation (including `CompositeStrategy` for chaining).

## Templates

The template parser replaces tokens like `{{ firstName }}` and supports arguments like `{{ numberBetween(1, 100) }}`.
