<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
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

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(PersonExtensionInterface::class, Person::class);
        $container->add(AddressExtensionInterface::class, Address::class);
        $this->generator = new DummyGenerator($container);
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
}
