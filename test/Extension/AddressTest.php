<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Address;
use DummyGenerator\Core\Person;
use DummyGenerator\Definitions\Extension\AddressExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class AddressTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(AddressExtensionInterface::class, Address::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testCityPrefix(): void
    {
        self::assertNotEmpty($this->generator->cityPrefix());
    }

    public function testCitySuffix(): void
    {
        self::assertNotEmpty($this->generator->citySuffix());
    }

    public function testStreetSuffix(): void
    {
        self::assertNotEmpty($this->generator->streetSuffix());
    }

    public function testBuildingNumber(): void
    {
        self::assertNotEmpty($this->generator->buildingNumber());
    }

    public function testCity(): void
    {
        self::assertNotEmpty($this->generator->city());
    }

    public function testStreetName(): void
    {
        self::assertNotEmpty($this->generator->streetName());
    }

    public function testStreetAddress(): void
    {
        self::assertNotEmpty($this->generator->streetAddress());
    }

    public function testPostCode(): void
    {
        self::assertNotEmpty($this->generator->postcode());
    }

    public function testAddress(): void
    {
        self::assertNotEmpty($this->generator->address());
    }

    public function testCountry(): void
    {
        self::assertNotEmpty($this->generator->country());
    }

    // Enhanced validation tests

    public function testCityPrefixIsString(): void
    {
        $cityPrefix = $this->generator->cityPrefix();

        self::assertIsString($cityPrefix);
        self::assertNotEmpty($cityPrefix);
    }

    public function testCitySuffixIsString(): void
    {
        $citySuffix = $this->generator->citySuffix();

        self::assertIsString($citySuffix);
        self::assertNotEmpty($citySuffix);
    }

    public function testStreetSuffixIsString(): void
    {
        $streetSuffix = $this->generator->streetSuffix();

        self::assertIsString($streetSuffix);
        self::assertNotEmpty($streetSuffix);
    }

    public function testBuildingNumberContainsDigits(): void
    {
        $buildingNumber = $this->generator->buildingNumber();

        self::assertIsString($buildingNumber);
        self::assertMatchesRegularExpression('/\d+/', $buildingNumber);
    }

    public function testCityContainsText(): void
    {
        $city = $this->generator->city();

        self::assertIsString($city);
        self::assertGreaterThan(2, strlen($city));
    }

    public function testStreetNameContainsText(): void
    {
        $streetName = $this->generator->streetName();

        self::assertIsString($streetName);
        self::assertGreaterThan(3, strlen($streetName));
    }

    public function testStreetAddressContainsComponents(): void
    {
        $streetAddress = $this->generator->streetAddress();

        self::assertIsString($streetAddress);
        self::assertGreaterThan(5, strlen($streetAddress));

        // Should contain some digits (building number)
        self::assertMatchesRegularExpression('/\d+/', $streetAddress);
    }

    public function testPostcodeIsNotEmpty(): void
    {
        $postcode = $this->generator->postcode();

        self::assertIsString($postcode);
        self::assertNotEmpty($postcode);
    }

    public function testAddressContainsMultipleComponents(): void
    {
        $address = $this->generator->address();

        self::assertIsString($address);
        self::assertGreaterThan(10, strlen($address));

        // A full address should contain some digits
        self::assertMatchesRegularExpression('/\d+/', $address);
    }

    public function testCountryIsString(): void
    {
        $country = $this->generator->country();

        self::assertIsString($country);
        self::assertGreaterThan(2, strlen($country));
    }

    public function testAddressGeneratesDifferentAddresses(): void
    {
        $addresses = [];
        for ($i = 0; $i < 20; $i++) {
            $addresses[] = $this->generator->address();
        }

        $uniqueAddresses = array_unique($addresses);
        self::assertGreaterThan(1, count($uniqueAddresses), 'Should generate different addresses');
    }

    public function testCityGeneratesDifferentCities(): void
    {
        $cities = [];
        for ($i = 0; $i < 20; $i++) {
            $cities[] = $this->generator->city();
        }

        $uniqueCities = array_unique($cities);
        self::assertGreaterThan(1, count($uniqueCities), 'Should generate different cities');
    }

    public function testStreetNameGeneratesDifferentStreets(): void
    {
        $streets = [];
        for ($i = 0; $i < 20; $i++) {
            $streets[] = $this->generator->streetName();
        }

        $uniqueStreets = array_unique($streets);
        self::assertGreaterThan(1, count($uniqueStreets), 'Should generate different street names');
    }

    public function testPostcodeFormat(): void
    {
        $postcode = $this->generator->postcode();

        // Postcodes typically contain numbers and possibly letters
        self::assertMatchesRegularExpression('/[0-9]/', $postcode);
    }
}
