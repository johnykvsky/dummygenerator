# Container and Strategy

## Container extensions packs

`DefinitionContainerBuilder` offers 3 level for loading extensions:

Base extensions with `DefinitionContainerBuilder::base()`:

* Coordinates
* Country
* DateTime
* Hash
* Language
* Lorem
* Number

Default extensions with `DefinitionContainerBuilder::default()` gives all from `Base` plus:

* Internet
* Person

All extensions with `DefinitionContainerBuilder::all()` gives all from `Default` plus:

* Address
* Barcode
* Biased
* Blood
* Color
* Company
* File
* Payment
* PhoneNumber
* Text
* UserAgent
* Version

If you want to add more of your own, you can do that in following way:

```php
        $container = DefinitionContainerBuilder::default();
        $container->add(AddressExtensionInterface::class, \My\Custom\Provider\pl_PL\Address::class, ;
        $container->add(LicensePlate::class, \My\Custom\Provider\pl_PL\LicensePlate::class);
        $generator = new DummyGenerator($container);
```

In this example two things happened:
* `Address` extension will be overwritten with our custom `Address` made for `pl_PL` language
* new extensions `LicensePlate` is added, so we can now use `$generator->licensePlate()` as it is defined in given class

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
but as mentioned: this way we skip `__call` in `DummyGenerator` so it won't work with any other strategy than `Simple`. It's not bad, just keep that in mind.

**Beware**, you need to pay attention to one thing: naming. Magic method `__call` checks if requested method (like `firstName`) exists in any extension. It checks them one by one, in order of adding. So if you have by some reason 2 extensions that has same method (like `getName()` in both of them) and you run `$generator->getName()` it will execute `getName()` in extension that was added earlier to container.

## Strategy

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
