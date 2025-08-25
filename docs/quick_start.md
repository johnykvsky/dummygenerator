# The core

DummyGenerator architecture was build on top of one thing: to be able to replace all internals.

To achieve that all internal classes are loaded into container and their implementation can be replaced anytime.

It's worth remembering: 
* there is a container holding extensions used by DummyGenerator
* there are ways to replace items in the container.

Simple example to start `DummyGenerator` is
```php
$generator = DummyGeneratorFactory::create();
echo $generator->firstName();
```

But this is a "shortcut", a factory that hides internals. Actually `DummyGenerator` accepts 3 parameters:
* Clock
* Strategy
* Container

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

There is also `FrozenClock` ready to be used in tests - you can set it with fixed date.

# The Strategy

Strategy tells DummyGenerator how to generate things. You have 4 predefined strategies ready to be used: 

* `SimpleStrategy` default one, just generates data, no validation/checks are applied
* `UniqueStrategy` makes sure generated data is unique
* `ValidStrategy` allow to pass a callback validator that will check generated data
* `ChanceStrategy` let you get data with given percentage chance

For `Unique` and `Valid` strategy there is fixed amount of retries after which exception will be throws - to make sure it will not try to generate unique value forever.

Sample strategies looks like:
```php
$simple = new SimpleStrategy();
$unique = new UniqueStrategy(retries: 500); // we have 500 retries to get unique value
$chance = new ChanceStrategy(weight: 50); // 50% chance to get value
$valid = new ValidStrategy(fn($x) => $x <= 50); // generated value has to be lower or equal than 50 
```

# The container

Container holds definitions - by definition I mean classes that can be used for generation stuff you need.
Each item you can generate has own interface and implementation, that you can change if needed.

Available definitions interfaces are in `src/Definitions` directory and their implementation is in `src/Extensions`.

Each of them can be added to container and passed to `DummyGenerator`.

To make it easier there is `DefinitionContainerBuilder` that uses `DefinitionPack` with 3 predefined definitions packages:

* **Base**: DateTime, Enum, Lorem, Number, Strings, Uuid 
* **Default**, all from Base plus: Coordinates, Country, Hash, Internet, Language, Person
* **All**, all from Default plus: Address, Barcode, Biased, Blood, Color, Company, File, Payment, PhoneNumber, UserAgent, Version

Sample usage of `DefinitionContainerBuilder` looks like this:
```php
$container = DefinitionContainerBuilder::all(); // to get all extensions
```

# Replacing Strategy or Clock

You can create `DummyGenerator` with given strategy or clock, but you can also change strategy or clock in currently used generator:
```php
$generator = new DummyGenerator(DefinitionContainerBuilder::all(), new SimpleStrategy(), new SystemClock());
$clock = new SystemClock(new \DateTimeZone('UTC'));
$strategy = new UniqueStrategy(retries: 500);
$generator = $generator->withClock($clock)->withStrategy($strategy);

```

# The Generator

Knowing all this, instead of using `DummyGeneratorFactory` you can create generator with:
```php
$generator = new DummyGenerator(DefinitionContainerBuilder::all(), new SimpleStrategy(), new SystemClock());
// but SimpleStrategy and SystemClock are default, so it could be replaced with
$generator = new DummyGenerator(DefinitionContainerBuilder::all()); 
// and if you're fine with only base extensions then it could actually be
$generator = new DummyGenerator();
```

But if you don't want all extensions, just two that you have created:

```php
class MyOwnExtension implements ExtensionInterface
{
    public function foo(string $something): string
    {
        return $something . ' is awesome!';
    }
}

class MyOtherExtension implements ExtensionInterface
{
    public function boo(string $something): string
    {
        return $something . ' is a crap!';
    }
}
```

You can initialize `DummyGenerator` like this:
```php
$container = new DefinitionContainer(); // initialize container with no extensions
$container->add(MyOwnExtension::class, MyOwnExtension::class); // first parameter is ID/name, second value (extension itself)
$container->add('my_other_extension', MyOtherExtension::class); // ID can be regular string
$generator = new DummyGenerator($container);
echo $generator->foo('Anna'); // gives 'Anna is awesome!'
echo $generator->boo('School'); // gives 'School is a crap!' 
```
