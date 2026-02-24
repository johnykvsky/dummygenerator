<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionLogicException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Coordinates;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CoordinatesTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(CoordinatesExtensionInterface::class, Coordinates::class);
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

    // Enhanced validation tests

    public function testLatitudeDefaultRangeIsValid(): void
    {
        // Test default range without specifying min/max
        for ($i = 0; $i < 20; $i++) {
            $latitude = $this->generator->latitude();
            self::assertTrue($latitude >= -90.0 && $latitude <= 90.0, "Latitude $latitude should be between -90 and 90");
        }
    }

    public function testLongitudeDefaultRangeIsValid(): void
    {
        // Test default range without specifying min/max
        for ($i = 0; $i < 20; $i++) {
            $longitude = $this->generator->longitude();
            self::assertTrue($longitude >= -180.0 && $longitude <= 180.0, "Longitude $longitude should be between -180 and 180");
        }
    }

    public function testLatitudeAtBoundaries(): void
    {
        // Test that we can generate at the exact boundaries
        $latitude = $this->generator->latitude(min: -90.0, max: -90.0);
        self::assertEquals(-90.0, $latitude);

        $latitude = $this->generator->latitude(min: 90.0, max: 90.0);
        self::assertEquals(90.0, $latitude);
    }

    public function testLongitudeAtBoundaries(): void
    {
        // Test that we can generate at the exact boundaries
        $longitude = $this->generator->longitude(min: -180.0, max: -180.0);
        self::assertEquals(-180.0, $longitude);

        $longitude = $this->generator->longitude(min: 180.0, max: 180.0);
        self::assertEquals(180.0, $longitude);
    }

    /**
     * Test latitude generation for different hemispheres and boundary scenarios.
     *
     * @param float $min Minimum latitude
     * @param float $max Maximum latitude
     * @param string $scenario Description of geographic scenario
     */
    #[DataProvider('latitudeRangeProvider')]
    public function testLatitudeWithVariousRanges(float $min, float $max, string $scenario): void
    {
        $latitude = $this->generator->latitude(min: $min, max: $max);
        self::assertGreaterThanOrEqual($min, $latitude, "Failed for: $scenario");
        self::assertLessThanOrEqual($max, $latitude, "Failed for: $scenario");
    }

    /**
     * Test longitude generation for different hemispheres and boundary scenarios.
     *
     * @param float $min Minimum longitude
     * @param float $max Maximum longitude
     * @param string $scenario Description of geographic scenario
     */
    #[DataProvider('longitudeRangeProvider')]
    public function testLongitudeWithVariousRanges(float $min, float $max, string $scenario): void
    {
        $longitude = $this->generator->longitude(min: $min, max: $max);
        self::assertGreaterThanOrEqual($min, $longitude, "Failed for: $scenario");
        self::assertLessThanOrEqual($max, $longitude, "Failed for: $scenario");
    }

    /**
     * Data provider for latitude range tests.
     *
     * @return array<string, array{min: float, max: float, scenario: string}>
     */
    public static function latitudeRangeProvider(): array
    {
        return [
            'northern hemisphere' => [0.0, 90.0, 'Northern hemisphere only'],
            'southern hemisphere' => [-90.0, 0.0, 'Southern hemisphere only'],
            'equatorial region' => [-10.0, 10.0, 'Near equator'],
            'europe region' => [35.0, 70.0, 'European latitudes'],
        ];
    }

    /**
     * Data provider for longitude range tests.
     *
     * @return array<string, array{0: float, 1: float, 2: string}>
     */
    public static function longitudeRangeProvider(): array
    {
        return [
            'eastern hemisphere' => [0.0, 180.0, 'Eastern hemisphere only'],
            'western hemisphere' => [-180.0, 0.0, 'Western hemisphere only'],
            'europe africa' => [-10.0, 40.0, 'Europe/Africa region'],
            'americas' => [-170.0, -30.0, 'Americas region'],
        ];
    }

    public function testCoordinatesHaveCorrectStructure(): void
    {
        $coordinates = $this->generator->coordinates();

        self::assertIsArray($coordinates);
        self::assertCount(2, $coordinates);
        self::assertArrayHasKey('latitude', $coordinates);
        self::assertArrayHasKey('longitude', $coordinates);
        self::assertIsFloat($coordinates['latitude']);
        self::assertIsFloat($coordinates['longitude']);
    }

    public function testCoordinatesValuesAreInValidRange(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $coordinates = $this->generator->coordinates();

            self::assertTrue(
                $coordinates['latitude'] >= -90.0 && $coordinates['latitude'] <= 90.0,
                "Latitude {$coordinates['latitude']} should be between -90 and 90"
            );

            self::assertTrue(
                $coordinates['longitude'] >= -180.0 && $coordinates['longitude'] <= 180.0,
                "Longitude {$coordinates['longitude']} should be between -180 and 180"
            );
        }
    }

    public function testLatitudeHasVariety(): void
    {
        $latitudes = [];
        for ($i = 0; $i < 30; $i++) {
            $latitudes[] = $this->generator->latitude();
        }

        $uniqueLatitudes = array_unique($latitudes);
        self::assertGreaterThan(1, count($uniqueLatitudes), 'Should generate different latitude values');
    }

    public function testLongitudeHasVariety(): void
    {
        $longitudes = [];
        for ($i = 0; $i < 30; $i++) {
            $longitudes[] = $this->generator->longitude();
        }

        $uniqueLongitudes = array_unique($longitudes);
        self::assertGreaterThan(1, count($uniqueLongitudes), 'Should generate different longitude values');
    }


    public function testLatitudeMinGreaterThanMaxException(): void
    {
        $this->expectException(\ValueError::class);
        $this->generator->latitude(min: 50.0, max: 40.0);
    }

    public function testLongitudeMinGreaterThanMaxException(): void
    {
        $this->expectException(\ValueError::class);
        $this->generator->longitude(min: 100.0, max: 50.0);
    }

    public function testCoordinatesGeneratesDifferentLocations(): void
    {
        $locations = [];
        for ($i = 0; $i < 30; $i++) {
            $coords = $this->generator->coordinates();
            $locations[] = "{$coords['latitude']},{$coords['longitude']}";
        }

        $uniqueLocations = array_unique($locations);
        self::assertGreaterThan(1, count($uniqueLocations), 'Should generate different coordinate pairs');
    }
}