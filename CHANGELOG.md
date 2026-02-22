# Changelog

---

## v0.2.0

### New / changed
- Documentation is now in the same repo, see `docs` folder
- Support for Strategy pipelines (chaining): `StrategyChain`, `CompositeStrategy`, and short-circuit strategy support.
- Template parser extracted to own implementation under `src/Template/`.
- Updated tests
- `DummyGenerator::create()` replaced `DummyGeneratorFactory` which is removed
- `PHP-DI` replaced previously used DI Container
- Core extensions refactored to use autowire via constructor injection instead of awareness traits.
- `GeneratorProxy` is used for injecting generator
- Strategy and Clock are now loaded from container
- Template parser is loaded from container

### Removed
- Awareness interfaces and traits
- `DummyGenerator` constructor params for Strategy and Clock
- `DummyGenerator` methods `clock()`, `withStrategy()`, `usedStrategy()`, `withClock()`
- `DummyGeneratorFactory`

### Dependencies
- Dev dependency updates in `composer.json` (PHPUnit 13, latest PHPStan and coding standards).
- added dependency on PHP=SI

---
## v0.1.0

First "stable" release, with all planned extensions and support for language providers.
