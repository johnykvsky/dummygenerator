<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionLogicException;
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

    public function testLatitudeMinimumException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->generator->latitude(min: -100.0, max: -100.0);
    }

    public function testLatitudeMaximumException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->generator->latitude(min: 100.0, max: 100.0);
    }

    public function testLongitude(): void
    {
        $longitude = $this->generator->longitude(min: $min = -10.0, max: $max = 10.0);

        self::assertTrue($longitude >= $min && $longitude <= $max);
    }

    public function testLongitudeMinimumException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->generator->longitude(min: -200.0, max: -200.0);
    }

    public function testLongitudeMaximumException(): void
    {
        $this->expectException(ExtensionLogicException::class);
        $this->generator->longitude(min: 200.0, max: 200.0);
    }

    public function testCoordinates(): void
    {
        $coordinates = $this->generator->coordinates();

        self::assertArrayHasKey('latitude', $coordinates);
        self::assertArrayHasKey('longitude', $coordinates);
    }
}