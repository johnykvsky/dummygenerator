# Overwriting stuff

Interfaces are quite important here. With them, you can overwrite every part of DummyGenerator. Extension, Randomizer, Calculator or how DummyGenerator replace strings.

## How to overwrite stuff

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

Easy, right?

As an example you can look at `BiasedTest` - default Randomizer is overwritten there with `XoshiroRandomizer` since it allows to use `seed`.

## Calculators, Randomizer, Replacer and Traits

Idea of this package is to generate dummy data. To do that, most extensions if not all, use internally Randomizer. In old Faker it was `mt_rand()` hardcoded everywhere. But we are better than that - we have `RandomizerInterface` to the rescue!

If for whatever reason you think you can get better randomizer - no problem, you can replace it just by using `DefinitionContainer` and overriding `RandomizerInterface`. Same for Calculators, Transliterator or Replacer.

Also, all of them can be used in your custom extension, just 2 things to remember:
* your extension need to implement proper extension interface
* you need to use proper trait matched with interface

And those pairs look like this:

* `RandomizerAwareExtensionInterface` matched with `RandomizerAwareExtensionTrait`
* `ReplacerAwareExtensionInterface` matched with `ReplacerAwareExtensionTrait`
* `EanCalculatorExtensionInterface` matched with `EanCalculatorAwareExtensionTrait`
* `IbanCalculatorExtensionInterface` matched with `IbanCalculatorAwareExtensionTrait`
* `IsbnCalculatorExtensionInterface` matched with `IsbnCalculatorAwareExtensionTrait`
* `LuhnCalculatorExtensionInterface` matched with `LuhnCalculatorAwareExtensionTrait`

And that's all. If in your extension you implement `RandomizerAwareExtensionInterface`, add trait `RandomizerAwareExtensionTrait` - you can use `$this->randomizer` as implementation of either default `Randomizer` or any other service you have put in `DefinitionContainer` under the name `RandomizerInterface::class`

You can see that in action looking at `Implementaton` folder, how it is done in extensions. Of course, you can implement more than one (or even all of them) `*AwareExtensionInterface` with matching `*AwareExtensionTrait`, no problem, have fun.
