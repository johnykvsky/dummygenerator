### Welcome

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

DummyGenerator supports language packs - to get more info about that please go to [DummyProviders](https://github.com/johnykvsky/dummyproviders)
By default DummyGenerator contains only general English language data. DummyProviders add proper `en_GB`, `en_US` and `pl_PL`.

---

### Documentation overview

1. [getting-started.md](getting-started.md) - Install, create a generator, and see the basic API in action.
2. [overview.md](overview.md) - General overview of application architecture
3. [strategies.md](strategies.md) - Unique/valid/chance strategies, chaining rules, and short-circuit behavior.
4. [customization.md](customization.md) - Swap randomizers, replace extensions, add custom extensions
5. [extensions-howto.md](extensions-howto.md) - More info about extensions `Enum`, `String` and `AnyDateTime`
6. [generators.md](generators.md) - The list of generator methods grouped by extension.
