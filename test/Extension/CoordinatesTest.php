<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Coordinates;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(CoordinatesExtensionInterface::class, Coordinates::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testLatitude(): void
    {
        $latitude = $this->generator->latitude(min: $min = -15.0, max: $max = 15.0);

        self::assertTrue($latitude >= $min && $latitude <= $max);
    }

    public function testLongitude(): void
    {
        $longitude = $this->generator->longitude(min: $min = -10.0, max: $max = 10.0);

        self::assertTrue($longitude >= $min && $longitude <= $max);
    }

    public function testCoordinates(): void
    {
        $coordinates = $this->generator->coordinates();

        self::assertArrayHasKey('latitude', $coordinates);
        self::assertArrayHasKey('longitude', $coordinates);
    }
}