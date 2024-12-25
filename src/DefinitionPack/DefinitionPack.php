<?php

declare(strict_types = 1);

namespace DummyGenerator\DefinitionPack;

use DummyGenerator\Core\Address;
use DummyGenerator\Core\Barcode;
use DummyGenerator\Core\Biased;
use DummyGenerator\Core\Blood;
use DummyGenerator\Core\Calculator\EanCalculator;
use DummyGenerator\Core\Calculator\IbanCalculator;
use DummyGenerator\Core\Calculator\IsbnCalculator;
use DummyGenerator\Core\Calculator\LuhnCalculator;
use DummyGenerator\Core\Color;
use DummyGenerator\Core\Company;
use DummyGenerator\Core\Coordinates;
use DummyGenerator\Core\Country;
use DummyGenerator\Core\DateTime;
use DummyGenerator\Core\File;
use DummyGenerator\Core\Hash;
use DummyGenerator\Core\Internet;
use DummyGenerator\Core\Language;
use DummyGenerator\Core\Lorem;
use DummyGenerator\Core\Number;
use DummyGenerator\Core\Payment;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\PhoneNumber;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Text;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Core\UserAgent;
use DummyGenerator\Core\Version;
use DummyGenerator\Definitions\Calculator\CalculatorInterface;
use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;
use DummyGenerator\Definitions\Extension\BiasedExtensionInterface;
use DummyGenerator\Definitions\Extension\BloodExtensionInterface;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\Definitions\Extension\CompanyExtensionInterface;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\CountryExtensionInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Extension\FileExtensionInterface;
use DummyGenerator\Definitions\Extension\HashExtensionInterface;
use DummyGenerator\Definitions\Extension\InternetExtensionInterface;
use DummyGenerator\Definitions\Extension\LanguageExtensionInterface;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Extension\PaymentExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Extension\PhoneNumberExtensionInterface;
use DummyGenerator\Definitions\Extension\TextExtensionInterface;
use DummyGenerator\Definitions\Extension\UserAgentExtensionInterface;
use DummyGenerator\Definitions\Extension\VersionExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;

readonly class DefinitionPack implements DefinitionPackInterface
{
    /** @var array<string, class-string<DefinitionInterface>> */
    private array $coreDefinitions;
    /** @var array<string, class-string<CalculatorInterface>> */
    private array $calculators;
    /** @var array<string, class-string<ExtensionInterface>> */
    private array $baseExtensions;
    /** @var array<string, class-string<ExtensionInterface>> */
    private array $defaultExtensions;
    /** @var array<string, class-string<ExtensionInterface>> */
    private array $complementaryExtensions;

    public function __construct()
    {
        $this->calculators = [
            EanCalculatorInterface::class => EanCalculator::class,
            IbanCalculatorInterface::class => IbanCalculator::class,
            IsbnCalculatorInterface::class => IsbnCalculator::class,
            LuhnCalculatorInterface::class => LuhnCalculator::class,
        ];

        $this->coreDefinitions = [
            RandomizerInterface::class => Randomizer::class,
            ReplacerInterface::class => Replacer::class,
            TransliteratorInterface::class => Transliterator::class,
        ];

        $this->baseExtensions = [
            CoordinatesExtensionInterface::class => Coordinates::class,
            CountryExtensionInterface::class => Country::class,
            DateTimeExtensionInterface::class => DateTime::class,
            HashExtensionInterface::class => Hash::class,
            LanguageExtensionInterface::class => Language::class,
            LoremExtensionInterface::class => Lorem::class,
            NumberExtensionInterface::class => Number::class,
            ];

        $this->defaultExtensions = [
            PersonExtensionInterface::class => Person::class,
            InternetExtensionInterface::class => Internet::class,
        ];

        $this->complementaryExtensions = [
            AddressExtensionInterface::class => Address::class,
            BarcodeExtensionInterface::class => Barcode::class,
            BiasedExtensionInterface::class => Biased::class,
            BloodExtensionInterface::class => Blood::class,
            ColorExtensionInterface::class => Color::class,
            CompanyExtensionInterface::class => Company::class,
            FileExtensionInterface::class => File::class,
            PaymentExtensionInterface::class => Payment::class,
            PhoneNumberExtensionInterface::class => PhoneNumber::class,
            TextExtensionInterface::class => Text::class,
            UserAgentExtensionInterface::class => UserAgent::class,
            VersionExtensionInterface::class => Version::class,
        ];
    }

    /** @return array<string, class-string<ExtensionInterface>> */
    public function baseExtensions(): array
    {
        return $this->baseExtensions;
    }

    /** @return array<string, class-string<ExtensionInterface>> */
    public function defaultExtensions(): array
    {
        return $this->defaultExtensions;
    }

    /** @return array<string, class-string<ExtensionInterface>> */
    public function complementaryExtensions(): array
    {
        return $this->complementaryExtensions;
    }

    /** @return array<string, class-string<CalculatorInterface>> */
    public function calculators(): array
    {
        return $this->calculators;
    }

    /** @return array<string, class-string<DefinitionInterface>> */
    public function coreDefinitions(): array
    {
        return $this->coreDefinitions;
    }
}
