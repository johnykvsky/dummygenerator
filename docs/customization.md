# Customization

You can replace any part of the package by swapping definitions in the container.

## Replace the Randomizer

```php
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

$container = DiContainerFactory::base();
$container->set(RandomizerInterface::class, Randomizer::class);
$container->set(TemplateParserInterface::class, TemplateParser::class);

$generator = new DummyGenerator($container);
```

### Seeded Randomizer

If you need reproducible output, use the seeded `XoshiroRandomizer`:

```php
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

$container = DiContainerFactory::base();
$container->set(RandomizerInterface::class, new XoshiroRandomizer(12345));
$container->set(TemplateParserInterface::class, TemplateParser::class);
```

## Replace an Extension

```php
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

class MyPerson implements PersonExtensionInterface {
    public function firstName(?string $gender = null): string { return 'Alex'; }
    public function lastName(): string { return 'Doe'; }
    public function name(?string $gender = null): string { return 'Alex Doe'; }
    public function title(?string $gender = null): string { return 'Mx.'; }
    public function firstNameMale(): string { return 'Alex'; }
    public function firstNameFemale(): string { return 'Alex'; }
    public function titleMale(): string { return 'Mx.'; }
    public function titleFemale(): string { return 'Mx.'; }
}

$container = DiContainerFactory::base();
$container->set(PersonExtensionInterface::class, MyPerson::class);
$container->set(TemplateParserInterface::class, TemplateParser::class);

$generator = new DummyGenerator($container);
```

## Definition Types (Class-String vs Callable vs Instance)

When you add or replace definitions in the container (a `DummyContainerInterface`), you can use:

- **Class-string** (`MyExtension::class`): Preferred. Allows PHP-DI to autowire the class without eager instantiation.
- **Callable factory** (`fn () => new MyExtension(...)`): Use when you need custom construction or runtime configuration. It will be instantiated on first use.
- **Prebuilt instance** (`new MyExtension(...)`): Useful when you already have a configured object, but it will be treated as a fixed singleton.

Recommendation: use class-strings for extensions whenever possible, and reserve callables for cases where you must inject non-container configuration.

### DummyContainerInterface API

`DummyContainerInterface` exposes a small, explicit API:

- `get(string $id): mixed`
- `has(string $id): bool`
- `set(string $id, mixed $value): void`

## Add a Custom Extension

```php
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

class ProductExtension implements ExtensionInterface {
    public function productName(): string { return 'Widget'; }
}

$container = DiContainerFactory::base();
$container->set(ProductExtension::class, new ProductExtension());
$container->set(TemplateParserInterface::class, TemplateParser::class);

$generator = new DummyGenerator($container);

$generator->productName();
```

## GeneratorProxy (How Generator Injection Works)

When an extension type-hints `GeneratorInterface`, the container injects a `GeneratorProxy`. The proxy implements `GeneratorInterface` and lazily resolves the real generator from the `DummyContainerInterface`.

Key points:
- Before `DummyGenerator` is constructed, the proxy has no generator and will throw a `MissingDependencyException` if used.
- When `DummyGenerator` is constructed, it registers itself as the `GeneratorInterface` entry.
- After that, the proxy resolves to the real `DummyGenerator` instance on each call and forwards methods (`parse()` or dynamic calls).

This allows you to build a container independently and still have generator-aware extensions once the `DummyGenerator` is created.

Example (extension using the proxy):

```php
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\GeneratorInterface;

class GeneratorAwareExtension implements ExtensionInterface {
    public function __construct(private GeneratorInterface $generator) {}

    public function example(): string {
        return $this->generator->parse('{{productName}}');
    }
}
```

### Attribute Injection

DummyGenerator uses a `DummyContainerInterface` (backed by PHP-DI by default). For explicit service IDs, you can use attributes:

```php
use DI\Attribute\Inject;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class CustomExtension implements ExtensionInterface {
    public function __construct(
        #[Inject(RandomizerInterface::class)]
        private RandomizerInterface $randomizer
    ) {}
}
```

## Provider Packs

Use a provider pack to swap multiple definitions at once - this is mainly to be used by language providers.

```php
use DummyGenerator\ProviderPack\ProviderPackInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;

class MyPack implements ProviderPackInterface {
    public function all(): array {
        return [
            PersonExtensionInterface::class => MyPerson::class,
            AddressExtensionInterface::class => MyAddress::class,
        ];
    }
}

$generator = $generator->withProvider(new MyPack());
```
