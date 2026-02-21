# Changelog

---

## v0.2.0

### Added
- Full `docs/` documentation set with an entry index and topic pages.
- Strategy pipeline support: `StrategyChain`, `CompositeStrategy`, and short-circuit strategy support.
- Template parser implementation under `src/Template/`.
- New and expanded test suites across calculators, extensions, generator, integration, performance, stress, and templates.

### Changed
- `DummyGenerator::create()` replaced `DummyGeneratorFactory`
- Core extensions refactored to use constructor injection instead of awareness traits.
- `PHP-DI` replaced previously used DI Container
- Removed all `*Aware` approach, now DI container resolved dependencies, `GeneratorProxy` is used for injecting generator
- `DummyGenerator` updated to support strategy chaining, performance monitoring, and template parsing integration.
- Strategy implementations updated to cooperate with short-circuit behavior.
- Strategy and Clock are now loaded from container
- Template parser is loaded from container

### Removed
- Awareness interfaces and traits under `src/Definitions/Extension/Awareness/`.
- `DummyGenerator` constructor params for Strategy and Clock
- `DummyGenerator` methods `clock()`, `withStrategy()`, `usedStrategy()`, `withClock()`, `removeDefinition()`
- `DummyGeneratorFactory`

### Dependencies
- Dev dependency updates in `composer.json` (PHPUnit 13, newer PHPStan and coding standards).

---
## v0.1.0

First "stable" release, with all planned extensions and support for language providers.
