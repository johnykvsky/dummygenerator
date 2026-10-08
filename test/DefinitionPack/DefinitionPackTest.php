<?php

declare(strict_types=1);

namespace DummyGenerator\Test\DefinitionPack;

use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Core\Address;
use DummyGenerator\Core\AnyDateTime;
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
use DummyGenerator\Core\Enum;
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
use DummyGenerator\Core\Strings;
use DummyGenerator\Core\Transliterator\SimpleTransliterator;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Core\UserAgent;
use DummyGenerator\Core\Uuid;
use DummyGenerator\Core\Version;
use DummyGenerator\DefinitionPack\DefinitionPack;
use DummyGenerator\DefinitionPack\DefinitionPackInterface;
use DummyGenerator\Definitions\Calculator\CalculatorInterface;
use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\AnyDateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;
use DummyGenerator\Definitions\Extension\BiasedExtensionInterface;
use DummyGenerator\Definitions\Extension\BloodExtensionInterface;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\Definitions\Extension\CompanyExtensionInterface;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\CountryExtensionInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
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
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;
use DummyGenerator\Definitions\Extension\UserAgentExtensionInterface;
use DummyGenerator\Definitions\Extension\UuidExtensionInterface;
use DummyGenerator\Definitions\Extension\VersionExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use PHPUnit\Framework\TestCase;

class DefinitionPackTest extends TestCase
{
    public function testBaseExtensionsReturnsExpectedMapping(): void
    {
        $pack = new DefinitionPack();
        $extensions = $pack->baseExtensions();

        $expected = [
            AnyDateTimeExtensionInterface::class => AnyDateTime::class,
            EnumExtensionInterface::class => Enum::class,
            LoremExtensionInterface::class => Lorem::class,
            NumberExtensionInterface::class => Number::class,
            StringsExtensionInterface::class => Strings::class,
            UuidExtensionInterface::class => Uuid::class,
        ];

        self::assertSame($expected, $extensions);
        foreach ($extensions as $interface => $class) {
            self::assertTrue(interface_exists($interface));
            self::assertTrue(class_exists($class));
            self::assertTrue(is_subclass_of($class, ExtensionInterface::class));
            self::assertTrue(is_subclass_of($class, $interface));
        }
    }

    public function testDefaultExtensionsReturnsExpectedMapping(): void
    {
        $pack = new DefinitionPack();
        $extensions = $pack->defaultExtensions();

        $expected = [
            CoordinatesExtensionInterface::class => Coordinates::class,
            CountryExtensionInterface::class => Country::class,
            HashExtensionInterface::class => Hash::class,
            InternetExtensionInterface::class => Internet::class,
            LanguageExtensionInterface::class => Language::class,
            PersonExtensionInterface::class => Person::class,
        ];

        self::assertSame($expected, $extensions);
        foreach ($extensions as $interface => $class) {
            self::assertTrue(interface_exists($interface));
            self::assertTrue(class_exists($class));
            self::assertTrue(is_subclass_of($class, ExtensionInterface::class));
            self::assertTrue(is_subclass_of($class, $interface));
        }
    }

    public function testComplementaryExtensionsReturnsExpectedMapping(): void
    {
        $pack = new DefinitionPack();
        $extensions = $pack->complementaryExtensions();

        $expected = [
            AddressExtensionInterface::class => Address::class,
            BarcodeExtensionInterface::class => Barcode::class,
            BiasedExtensionInterface::class => Biased::class,
            BloodExtensionInterface::class => Blood::class,
            ColorExtensionInterface::class => Color::class,
            CompanyExtensionInterface::class => Company::class,
            DateTimeExtensionInterface::class => DateTime::class,
            FileExtensionInterface::class => File::class,
            PaymentExtensionInterface::class => Payment::class,
            PhoneNumberExtensionInterface::class => PhoneNumber::class,
            UserAgentExtensionInterface::class => UserAgent::class,
            VersionExtensionInterface::class => Version::class,
        ];

        self::assertSame($expected, $extensions);
        foreach ($extensions as $interface => $class) {
            self::assertTrue(interface_exists($interface));
            self::assertTrue(class_exists($class));
            self::assertTrue(is_subclass_of($class, ExtensionInterface::class));
            self::assertTrue(is_subclass_of($class, $interface));
        }
    }

    public function testCalculatorsReturnsExpectedMapping(): void
    {
        $pack = new DefinitionPack();
        $calculators = $pack->calculators();

        $expected = [
            EanCalculatorInterface::class => EanCalculator::class,
            IbanCalculatorInterface::class => IbanCalculator::class,
            IsbnCalculatorInterface::class => IsbnCalculator::class,
            LuhnCalculatorInterface::class => LuhnCalculator::class,
        ];

        self::assertSame($expected, $calculators);
        foreach ($calculators as $interface => $class) {
            self::assertTrue(interface_exists($interface));
            self::assertTrue(class_exists($class));
            self::assertTrue(is_subclass_of($class, CalculatorInterface::class));
            self::assertTrue(is_subclass_of($class, $interface));
        }
    }

    public function testCoreDefinitionsReturnsExpectedMapping(): void
    {
        $pack = new DefinitionPack();
        $core = $pack->coreDefinitions();

        $expectedTransliterator = class_exists(\Transliterator::class)
            ? Transliterator::class
            : SimpleTransliterator::class;

        $expected = [
            RandomizerInterface::class => Randomizer::class,
            ReplacerInterface::class => Replacer::class,
            TransliteratorInterface::class => $expectedTransliterator,
        ];

        self::assertSame($expected, $core);
        foreach ($core as $interface => $class) {
            self::assertTrue(interface_exists($interface));
            self::assertTrue(class_exists($class));
            self::assertTrue(is_subclass_of($class, DefinitionInterface::class));
            self::assertTrue(is_subclass_of($class, $interface));
        }
    }

    public function testCustomDefinitionPackPassedToDiContainerFactory(): void
    {
        $customPack = new class implements DefinitionPackInterface {
            public function baseExtensions(): array
            {
                return [EnumExtensionInterface::class => Enum::class];
            }

            public function defaultExtensions(): array
            {
                return [CountryExtensionInterface::class => Country::class];
            }

            public function complementaryExtensions(): array
            {
                return [ColorExtensionInterface::class => Color::class];
            }

            public function calculators(): array
            {
                return [LuhnCalculatorInterface::class => LuhnCalculator::class];
            }

            public function coreDefinitions(): array
            {
                return [RandomizerInterface::class => Randomizer::class];
            }
        };

        // DiContainerFactory::base()
        $baseContainer = DiContainerFactory::base($customPack, false);
        self::assertTrue($baseContainer->has(RandomizerInterface::class));
        self::assertTrue($baseContainer->has(EnumExtensionInterface::class));
        self::assertFalse($baseContainer->has(CountryExtensionInterface::class));
        self::assertFalse($baseContainer->has(ColorExtensionInterface::class));
        self::assertFalse($baseContainer->has(LuhnCalculatorInterface::class));

        // DiContainerFactory::default()
        $defaultContainer = DiContainerFactory::default($customPack, false);
        self::assertTrue($defaultContainer->has(RandomizerInterface::class));
        self::assertTrue($defaultContainer->has(EnumExtensionInterface::class));
        self::assertTrue($defaultContainer->has(CountryExtensionInterface::class));
        self::assertFalse($defaultContainer->has(ColorExtensionInterface::class));
        self::assertFalse($defaultContainer->has(LuhnCalculatorInterface::class));

        // DiContainerFactory::all()
        $allContainer = DiContainerFactory::all($customPack, false);
        self::assertTrue($allContainer->has(RandomizerInterface::class));
        self::assertTrue($allContainer->has(EnumExtensionInterface::class));
        self::assertTrue($allContainer->has(CountryExtensionInterface::class));
        self::assertTrue($allContainer->has(ColorExtensionInterface::class));
        self::assertTrue($allContainer->has(LuhnCalculatorInterface::class));
    }
}
