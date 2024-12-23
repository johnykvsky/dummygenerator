# Quick Start

Quickest start is:

```php
        $generator = new DummyGenerator(DefinitionContainerBuilder::all());
        echo $generator->firstName();
```

Done.

This load `DummyGenerator` with all extensions available in core. `DummyGenerator` has 4 strategies that can be used:

* Simple - default one, just works
* Unique - gives only unique values
* Chance - gives values based on percentage chance of getting them
* Valid - gives values if used conditional check is true

In above example Simple strategy is used. To make it explicit, use all extensions and Simple strategy we could initialise `DummyGenerator` with:

```php
    $generator = new DummyGenerator(DefinitionContainerBuilder::all(), new SimpleStrategy());
```

It has exactly the same settings as first example.

You can choose between loading Basic, Default or All extensions by using:

```php
    $basic = DefinitionContainerBuilder::base();
    $default = DefinitionContainerBuilder::default();
    $all = DefinitionContainerBuilder::all();
```

Just pass any of them to `DummyGenerator`. Same for strategies, create your own if you need and pass it to `DummyGenerator`:

```php
        $simple = new SimpleStrategy();
        $unique = new UniqueStrategy(retries: 500); // we have 500 retries to get unique values
        $chance = new ChanceStrategy(weight: 50); // 50% chance to get value
        $valid = new ValidStrategy(fn($x) => $x <= 50); // generated value has to be lower or equal than 50 
```

But hey, what if I don't need any extensions from Core? Or want only 3 of them? Let's say you have 2 custom extensions, and you need only them.

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

No problem, just try this:

```php
        $container = new DefinitionContainer(); // initialize container with no extensions
        $container->add(MyOwnExtension::class, MyOwnExtension::class); // first parameter is ID/name, second value (extension itself)
        $container->add('my_other_extension', MyOtherExtension::class); // ID can be regular string
        $generator = new DummyGenerator($container);
        echo $generator->foo('Anna'); // gives 'Anna is awesome!'
        echo $generator->boo('School'); // gives 'School is a crap!' 
```

## Text

Text extension is a bit different for one reason - it uses external `txt` file as source to large test. By default it's in `resources/en_US.txt` but you can either:

* pass file location to `Text` constructor
* extend `Text` class and use different location in `$defaultText` property