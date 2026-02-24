# Customization

## The flow

When you generate a name with `->firstName()` actual flow looks like this:

* DummyGenerator method `__call()` is launched
* generator is trying to find extension containing passed method name by reaching out to container `findProcessor()`
* container is looping through all loaded definitions, checking if any of them has given method
* if it's found, then definition instance is returned to generator
* generator (with used strategy) is trying to get data from extension by running given method (in our example: `firstName()`)

This has one thing worth notifying: you should not have multiple extensions with same method names. `__call()` checks them one by one, in order of adding. So if you have by some reason 2 extensions that has same method (like `getName()` in both of them) and you run `$generator->getName()` it will execute `getName()` in extension that was added earlier to container.

There is a walkaround for that - instead of doing `$generator->firstName()` and using internally magic method `__call()` you can hand-pick the extension with `->ext()`:

```php
echo $generator->firstName(); // default first name
echo $generator->ext(MyCustomNames::class)->firstName(); // will get custom name
echo $generator->ext(MySpecialNames::class)->firstName(); // will get special name
```

In above example MyCustomNames and MySpecialNames has to be added to container after build in Person extension (as it will be resolved first when looking for `firstName()`).

# The Clock

Clock is simple implementation of PSR-20 Clock. It allows to set a clock with a timezone for generated dates.

Clock has a param to set timezone:
```php
$clock = new SystemClock('Europe/London')
```

Thanks to Clock in your extension (look at `DateTime` for example) you will have access to `$this->clock->now()` that will return `\DateTimeImmutable` object with current date time.

If no timezone param is passed it checks for `date_default_timezone_get()` and if it's missing then `UTC` timezone is used. But `date_default_timezone_get()` is returning `UTC` as default anyway.

Generator itself can return clock so you can do this to get current time:
```php
$generator->clock->now();
```

There is also `FrozenClock` ready to be used in tests - you can set it with fixed date:

```php
$clock = new FrozenClock(new \DateTimeImmutable('2025-08-11'), new \DateTimeZone('UTC'));
$container = DiContainerFactory::all();
$container->set(SystemClockInterface::class, $clock);
$generator =  DummyGenerator($container)
// or
$generator = DummyGenerator::create();
$generator = $generator->withDefinition(SystemClockInterface::class, $clock);
```

You can replace any part of the package by swapping definitions in the container. Remember: `withDefinition()` is immutable!

# Seed

DummyGenerator generate random data. Which is fine, but sometimes (i.e.: in tests) you want it to generate same data each time. This is where `seed()` comes to the rescue.

Method `seed()` accepts param with a seed number. If you initialize generator with `seed(1434)`  it will always return same name for `->firstName()`, same address for `->buildingNumber()`, same color for `->hexColor()` and so on.

### How can I use Randomizer with seed()

You have to change default randomizer to `XoshiroRandomizer` with desired seed number, i.e. for `seed=123` it would be:

```php
$container = DiContainerFactory::all();
$container->set(RandomizerInterface::class, new \DummyGenerator\Core\Randomizer\XoshiroRandomizer(seed: 123));
$generator = new \DummyGenerator\DummyGenerator($container);  
// and from now on generator will use fixed seed to get data
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
