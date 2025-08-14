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

Other way to work with localised extensions is to load them all and use by hand-picking:

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

### How can I change definition on the fly

It can be done like this:

```php
$generator->firstName(); // will generate i.e. "Harry"
$generator->addDefinition(PersonExtensionInterface::class, ElvesPerson::class);
$generator->firstName(); // will generate i.e. "Fingolfin"
```

**Beware**, this will clear internal cache for all extensions, so they will be resolved again. Not a big deal, but worth keeping in mind.

### How can I use different strategy

If we want to use different strategy, like uniqueness with max 5 retries:

```php
$generator = new DummyGenerator(DefinitionContainerBuilder::default(), new UniqueStrategy(5));
```

or if you want to replace it on the fly, just do it with:

```php
$geneator->withStrategy(new UniqueStrategy(5))->firstName();
```

Method `withStrategy` returns new generator, so you can do also that:
```php
$generator->firstName(); // default strategy
$generator2 = $geneator->withStrategy(new UniqueStrategy(5));
$generator2->firstName(); // uniqueness
$generator2->firstName(); // uniqueness
$generator->firstName(); // default strategy
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
$generator = new DummyGenerator($container);
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

### How can I use EnumExtension

Enum extension allows you to get random element or value from selected Enum object. It has two methods:
 * enumValue(), that will get value from backed enums (which has to be string or int)
 * enumElement(), that will get one of `cases()` element from enum (it will be `UnitEnum` object) 

`enumValue()` has to be used on backed enums, but `enumElement()` works for backed and non-backed enums.

For following enum:

```php
enum SuitBackedIntEnum: string
{
    case Hearts = 'Hearts';
    case Diamonds = 'Diamonds';
    case Clubs = 'Clubs';
    case Spades = 'Spades';
}
```

You can do following:

```php
$container = DefinitionContainerBuilder::base(); // base extensions 
$generator = new DummyGenerator($container);
$generator->enumElement(SuitBackedIntEnum::class); // it will get random element, i.e. SuitBackedIntEnum::Diamonds
// or
$generator->enumValue(SuitBackedIntEnum::class); // it will get random value, i.e. "Spades"
```

### How can I use StringsExtension

With `LoremExtension` you can generate `words()` or `text()`. You can generate single word too - with `word()`, it will give you random words from Lorem Ipsum sample.

In [dummyproviders](https://github.com/johnykvsky/dummyproviders) there is also `TextExtension` that allows you to generate random text with given length with `realText()`.

But sometimes you want just a simple random string, with given length or given structure: only letters, with some numbers, with capital letters. This is where `StringsExtension` can help you:

```php
$container = DefinitionContainerBuilder::base(); // base extensions 
$generator = new DummyGenerator($container);
$string1 = $generator->string(); // it will give you random string, lowercase, with length between 3 and 8
$string2 = $generator->string(3, 3); // it will give you random string, lowercase, with length equal to 3
$string4 = $generator->string(3, 10, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'); // it will give you random string, mixed case, with length from 3 to 10
```

As you can see you can pass any chars pool for generation. `StringsExtension` comes with 3 predefined pools:

 * `Strings::ALPHA_POOL` equals to `abcdefghijklmnopqrstuvwxyz`;
 * `Strings::ALPHA_CASE_POOL` equals to `abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`;
 * `Strings::ALPHA_NUM_POOL` equals to `0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`;

If you want to have string with possible spaces - just create your own pool, i.e. `abcdefghijkl mnopqrstuvwxyz`

At core, it uses `\Random\Randomizer::getBytesFromString()` to generate random string.

### How can I use seed()

You have to change default randomizer to `XoshiroRandomizer` with desired seed number, i.e. for `seed=123` it would be:

```php
    // standard initialization, adapt this to your needs
    $container = \DummyGenerator\Container\DefinitionContainerBuilder::base();
    $generator = new \DummyGenerator\DummyGenerator($container);  
    // replace randomizer with XoshiroRandomizer, which supports seed
    $generator->addDefinition(
        \DummyGenerator\Definitions\Randomizer\RandomizerInterface::class,
        new \DummyGenerator\Core\Randomizer\XoshiroRandomizer(seed: 123)
    );
```

### How can I use providers

Please look at [readme](https://github.com/johnykvsky/dummyproviders) in providers repository.

In same way you can create and use your own language providers.
