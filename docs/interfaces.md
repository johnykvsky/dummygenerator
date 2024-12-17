# Available interfaces

Let's start with Strategy, you can't override it with container, sorry. But if you want to create custom strategy, it has to implement `StrategyInterface`. Then you just pass it to `DummyGenerator` as second argument.

Next thing - if you want to add something to `DefinitionContainer` class that you are adding must implement `DefinitionInterface`. Directly or not, since you can go with

* `ExtensionInterface`
* `CalculatorInterface`
* `RandomizerInterface`
* `ReplacerInterface`
* `TransliteratorInterface`

And all of them implement `DefinitionInterface`. But if you are creating an extension, `ExtensionInterface` or any of `*AwareExtensionInterface` is enough.

Below are interfaces that you can overwrite with custom implementation. If you want to overwrite any of them just use InterfaceName::class, in example `ReplacerInterface::class`, as ID/name when adding to `DefinitionContainer`

Calculators:
* `EanCalculatorInterfaceInterface`
* `IbanCalculatorInterfaceInterface`
* `IsbnCalculatorInterfaceInterface`
* `LuhnCalculatorInterfaceInterface`

If you want to create new Calculator, it must implement `CalculatorInterface`

Randomizer:
* `RandomizerInterface`

If you want to add new Randomizer, it must implement `RandomizerInterface`

Replacer:
* `ReplacerInterface`

If you want to create new Replacer, it must implement `ReplacerInterface`

Transliterator:
* `TransliteratorInterface`

If you want to add new Transliterator, it must implement `TransliteratorInterface`

Extensions:
* `AddressExtensionInterface`
* `BarcodeExtensionInterface`
* `BiasedExtensionInterface`
* `BloodExtensionInterface`
* `ColorExtensionInterface`
* `CompanyExtensionInterface`
* `CoordinatesExtensionInterface`
* `CountryExtensionInterface`
* `DateTimeExtensionInterface`
* `FileExtensionInterface`
* `HashExtensionInterface`
* `InternetExtensionInterface`
* `LanguageExtensionInterface`
* `LoremExtensionInterface`
* `NumberExtensionInterface`
* `PaymentExtensionInterface`
* `PersonExtensionInterface`
* `PhoneNumberExtensionInterface`
* `TextExtensionInterface`
* `UserAgentExtensionInterface`
* `VersionExtensionInterface`

Extensions has general interface `ExtensionInterface`, and as mentioned you have to implement it if you want to make new extension.

Of course if you want you can extend any Extension, Calculator, Replacer, Randomizer or Transliterator and just overwrite single method or add new one.
