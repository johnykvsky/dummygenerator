# Quick Start

Quickest start is:

```php
        $generator = new DummyGenerator(DefinitionContainerBuilder::all());
        echo $generator->firstName(); // generate random first name
```

Done. This load `DummyGenerator` with all extensions available in core. If you want to know what can you generate beside first name - visit [this page](extensions.md) containing full list of available extensions and their methods.

Once you know how to start, let's get into details. Above example could be written as:

```php
    $generator = new DummyGenerator(DefinitionContainerBuilder::all(), new SimpleStrategy());
```

And now we can see that `DummyGenerator` nas 2 parameters:

* DefinitionContainer
* Strategy

First one holds definitions - by definition I mean classes that can be used for generation stuff you need. Each item you can generate has own interface and implementation, that you can change to your own if needed.

You can look at `src/Definitions` directory for available definitions interfaces or to `src/Extensions` for their implementations.

If you like, you can skip all definitions included with `DummyGenerator` and just add your own:

```php
    $generator = new DummyGenerator(new DefinitionContainer(['my_own_item' => new MyOwnItem()]));
```

Now that we know what definitions are, let's move to second parameter - Strategy. Strategy is used on generated data to validate it according to our needs. `DummyGenerator` has build in 4 strategies:

* Simple - default one, just generates data
* Unique - gives only unique values, same result will not be generated twice
* Chance - gives values based on percentage chance of getting them
* Valid - gives values if used conditional check is true, i.e. you can make it generate data without 'a' letter.

For `Unique` and `Valid` strategy there is fixed amount of retries after which exception will be throws - to make sure it will not try to generate unique value forever.

Examples of different strategies:

```php
        $simple = new SimpleStrategy();
        $unique = new UniqueStrategy(retries: 500); // we have 500 retries to get unique values
        $chance = new ChanceStrategy(weight: 50); // 50% chance to get value
        $valid = new ValidStrategy(fn($x) => $x <= 50); // generated value has to be lower or equal than 50 
```

## Container builder

Container builder allows to load predefined sets of extensions. You can choose between loading Basic, Default or All extensions by using:

```php
    $basic = DefinitionContainerBuilder::base();
    $default = DefinitionContainerBuilder::default();
    $all = DefinitionContainerBuilder::all();
```

Just pass any of them to `DummyGenerator`. 

Available packs are:

* Base: Coordinates, Country, DateTime, Hash, Language, Lorem, Number
* Default, all from Base plus: Internet, Person
* All, all from Default plus: Address, Barcode, Biased, Blood, Color, Company, File, Payment, PhoneNumber, Text, UserAgent, Version

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

But what if I want to add/replace some definition "on the fly"? It can be done like this:

```php
    $generator->firstName(); // will generate i.e. "Harry"
    $generator->addDefinition(PersonExtensionInterface::class, ElvesPerson::class);
    $generator->firstName(); // will generate i.e. "Fingolfin"
```

**beware** - this will clear internal cache for all extensions, so each call will resolve extensions classes. Not a big deal, but worth keeping in mind.

## Text

Text extension is a bit different for one reason - it uses external `.txt` file as source to large text. By default, it's in `resources/en_US.txt` but you can either:

* pass text to `Text` constructor (i.e. `$text = new Text(file_get_contents('my_file.txt'));`)
* extend `Text` class and use different location in `$defaultText` property
