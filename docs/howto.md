### How can I add my own localised definitions

```php
        $container = DefinitionContainerBuilder::default();
        $container->add(AddressExtensionInterface::class, \My\Custom\Provider\pl_PL\Address::class, ;
        $container->add(LicensePlate::class, \My\Custom\Provider\pl_PL\LicensePlate::class);
        $generator = new DummyGenerator($container);
```

In this example two things happened:
* `Address` extension will be overwritten with our custom `Address` made for `pl_PL` language
* new extensions `LicensePlate` is added, so we can now use `$generator->licensePlate()` as it is defined in given class

### How can I use language-based definitions

Other way to work with localized extensions is to load them all and use by hand-picking:

```php
        $container = DefinitionContainerBuilder::default();
        $container->add(AddressPL::class, \My\Custom\Provider\AddressPL::class);
        $container->add(AddressDE::class, \My\Custom\Provider\AddressDE::class);
        $generator = new DummyGenerator($container);
        echo $generator->firstName(); // default (English) first name
        echo $generator->ext(AddressPL::class)->firstName(); // Polish first name
        echo $generator->ext(AddressDE::class)->firstName(); // German first name
```

but this way we skip `__call` in `DummyGenerator` so it won't work with any other strategy than `Simple`. It's not bad, just keep that in mind.

**Beware**, you need to pay attention to one thing: naming. Magic method `__call` checks if requested method (like `firstName`) exists in any extension. It checks them one by one, in order of adding. So if you have by some reason 2 extensions that has same method (like `getName()` in both of them) and you run `$generator->getName()` it will execute `getName()` in extension that was added earlier to container.

### How can I use different strategy

If we want to use different strategy, like uniqueness:

```php
        $generator = new DummyGenerator(DefinitionContainerBuilder::default(), new UniqueStrategy(5));
```

or by:

```php
        $geneator->withStrategy(new UniqueStrategy(5))->firstName();
```

Method `withStrategy` returns new generator, so you can do also that:
```php
        $generator->firstName(); // default strategy
        $generator2 = $geneator->withStrategy(new UniqueStrategy(5));
        $generator2->firstName(); // uniqueness
        $generator2->firstName(); // uniqueness
        $generator1->firstName(); // default strategy
```

### How can I overwrite default implementations

Very easily, just use base interface as a name and point to your implementation. Voilà, done.

In example:
```php
        $container = DefinitionContainerBuilder::all(); // all extensions
        $container->add(CompanyExtensionInterface::class, MyCustomCompany::class); // now MyCustomerCountry will be used ie. for $generator->company()
        $container->add(RandomizerInterface::class, MyRandomizer::class); // now MyRandomizer will be used for every internal call ie. to randomElement()
        $container->add(TransliteratorInterface::class, TransliteratorOnSteroids::class); // now TransliteratorOnSteroids will be used for transliterate()  
        $container->add(LuhnCalculatorInterface::class, ProperLuhnCalculator::class); // now ProperLuhnCalculator will be used Luhn operations 
        $generator = new DummyGenerator();
```

### How can I use generator or randomizer in my custom extension

There are just 2 things to remember:

* your extension need to implement proper extension interface
* you need to use proper trait matched with interface

And those pairs look like this:

* `RandomizerAwareExtensionInterface` matched with `RandomizerAwareExtensionTrait`
* `ReplacerAwareExtensionInterface` matched with `ReplacerAwareExtensionTrait`
* `EanCalculatorExtensionInterface` matched with `EanCalculatorAwareExtensionTrait`
* `IbanCalculatorExtensionInterface` matched with `IbanCalculatorAwareExtensionTrait`
* `IsbnCalculatorExtensionInterface` matched with `IsbnCalculatorAwareExtensionTrait`
* `LuhnCalculatorExtensionInterface` matched with `LuhnCalculatorAwareExtensionTrait`

And that's all. Behind the scenes when fetching definition, container check if it implements one of those interfaces and add proper dependency. 

If in your extension you implement `RandomizerAwareExtensionInterface`, add trait `RandomizerAwareExtensionTrait` - you can use `$this->randomizer` as implementation of either default `Randomizer` or any other service you have put in `DefinitionContainer` under the name `RandomizerInterface::class`

One notice - there is also `TransliteratorAwareReplacerInterface` and `TransliteratorAwareReplacerTrait` since `Replacer` itself does `transliterate()` and needs this dependency.