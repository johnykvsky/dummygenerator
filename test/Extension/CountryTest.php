<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Definitions\Extension\CountryExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Country;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class CountryTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(CountryExtensionInterface::class, Country::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testCountryCode(): void
    {
        self::assertEquals(2, strlen($this->generator->countryISOAlpha2()));
    }

    public function testBloodRh(): void
    {
        self::assertEquals(3, strlen($this->generator->countryISOAlpha3()));
    }
}