# Extensions

Here is list of extensions available in the `DummyGenerator`, all those are interfaces are available to be overwritten with your own implementation:

* `AddressExtensionInterface`
* `BarcodeExtensionInterface`
* `BiasedExtensionInterface`
* `BloodExtensionInterface`
* `ColorExtensionInterface`
* `CompanyExtensionInterface`
* `CoordinatesExtensionInterface`
* `CountryExtensionInterface`
* `DateTimeExtensionInterface`
* `EnumExtensionInterface`
* `FileExtensionInterface`
* `HashExtensionInterface`
* `InternetExtensionInterface`
* `LanguageExtensionInterface`
* `LoremExtensionInterface`
* `NumberExtensionInterface`
* `PaymentExtensionInterface`
* `PersonExtensionInterface`
* `PhoneNumberExtensionInterface`
* `StringsExtensionInterface`
* `TextExtensionInterface`
* `UserAgentExtensionInterface`
* `VersionExtensionInterface`

Apart from this you can overwrite calculators:

* `EanCalculatorInterfaceInterface`
* `IbanCalculatorInterfaceInterface`
* `IsbnCalculatorInterfaceInterface`
* `LuhnCalculatorInterfaceInterface`

And 3 internal "helpers":

* `RandomizerInterface`
* `ReplacerInterface`
* `TransliteratorInterface`

## Available methods

You can find the list of all available extensions, with their methods, params and sample result [here](extensions_spec.txt).