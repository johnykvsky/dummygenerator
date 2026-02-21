### Welcome stranger

DummyGenerator is a powerful, modern PHP library for generating realistic fake data. Perfect for:

- **Testing**: Generate realistic test data for unit and integration tests
- **Development**: Seed databases with sample data during development
- **Prototyping**: Quickly populate mockups and prototypes
- **Demos**: Create compelling demonstration data

### Key Features

- ✅ **Extensions-based API**: Generator methods are provided by extensions
- ✅ **Strategies**: Wrap generation with uniqueness, validation, or chance
- ✅ **Templates**: Parse `{{ tokens }}` with arguments
- ✅ **Customization**: Swap randomizers or replace extensions in the container
- ✅ **Reproducibility**: Seeded randomizer for deterministic output
- ✅ **Performance Monitoring**: Track generator usage via the monitor

---

### Documentation overview

1. [getting-started.md](getting-started.md) - Install, create a generator, and see the basic API in action.
2. [concepts.md](concepts.md) - Overview of the core building blocks: generator, extensions, container, strategies, templates.
3. [generators.md](generators.md) - The authoritative list of generator methods grouped by extension.
4. [strategies.md](strategies.md) - Unique/valid/chance strategies, chaining rules, and short-circuit behavior.
5. [customization.md](customization.md) - Swap randomizers, replace extensions, add custom extensions, and provider packs.
6. [templates.md](templates.md) - Template syntax, arguments, and examples for dynamic text.
7. [advanced.md](advanced.md) - Seeding, performance monitoring, and testing tips.
8. [migration-faker.md](migration-faker.md) - Mapping guide for moving from FakerPHP/Faker.

What this documentation covers:
- How to install and use the generator in everyday cases.
- How generation is structured (extensions + container) and how strategies wrap calls.
- How to customize behavior and keep outputs reproducible for tests.
- How to migrate from Faker-style usage to DummyGenerator patterns.

Suggested paths:
- New users: [getting-started.md](getting-started.md) -> [concepts.md](concepts.md) -> [generators.md](generators.md)
- Power users: [customization.md](customization.md) -> [strategies.md](strategies.md) -> [advanced.md](advanced.md)
