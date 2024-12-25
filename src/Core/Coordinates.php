<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionLogicException;

class Coordinates implements CoordinatesExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    public function latitude(float $min = -90.0, float $max = 90.0): float
    {
        if ($min < -90 || $max < -90) {
            throw new ExtensionLogicException('Latitude cannot be less that -90.0');
        }

        if ($min > 90 || $max > 90) {
            throw new ExtensionLogicException('Latitude cannot be greater that 90.0');
        }

        return round($this->randomizer->getFloat($min, $max), 6);
    }

    public function longitude(float $min = -180.0, float $max = 180.0): float
    {
        if ($min < -180 || $max < -180) {
            throw new ExtensionLogicException('Longitude cannot be less that -180.0');
        }

        if ($min > 180 || $max > 180) {
            throw new ExtensionLogicException('Longitude cannot be greater that 180.0');
        }

        return round($this->randomizer->getFloat($min, $max), 6);
    }

    public function coordinates(): array
    {
        return [
            'latitude' => $this->latitude(),
            'longitude' => $this->longitude(),
        ];
    }
}
