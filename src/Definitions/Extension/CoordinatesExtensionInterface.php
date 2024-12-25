<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface CoordinatesExtensionInterface extends ExtensionInterface
{
    /**
     * @return float Uses signed degrees format (returns a float number between -90 and 90)
     *
     * @example 77.147489
     */
    public function latitude(float $min = -90.0, float $max = 90.0): float;

    /**
     * @return float Uses signed degrees format (returns a float number between -180 and 180)
     *
     * @example 86.211205
     */
    public function longitude(float $min = -180.0, float $max = 180.0): float;

    /**
     * @return array{latitude: float, longitude: float}
     *
     * @example array(77.147489, 86.211205)
     */
    public function coordinates(): array;
}
