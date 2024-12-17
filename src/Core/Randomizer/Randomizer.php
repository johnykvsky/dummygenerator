<?php

declare(strict_types=1);

namespace DummyGenerator\Core\Randomizer;

use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use Random\IntervalBoundary;
use Random\Randomizer as BaseRandomizer;

class Randomizer implements RandomizerInterface
{
    protected BaseRandomizer $randomizer;

    public function __construct()
    {
        $this->randomizer = new BaseRandomizer();
    }

    public function getInt(int $min, int $max): int
    {
        return $this->randomizer->getInt($min, $max);
    }

    public function getFloat(float $min, float $max): float
    {
        return $this->randomizer->getFloat($min, $max, IntervalBoundary::ClosedClosed);
    }

    public function getBool(int $chanceOfTrue = 50): bool
    {
        return $this->randomizer->getInt(1, 100) <= $chanceOfTrue;
    }

    public function getBytes(int $length = 16): string
    {
        return $this->randomizer->getBytes($length);
    }

    public function randomLetter(): string
    {
        return chr($this->randomizer->getInt(97, 122));
    }

    public function randomElement(array $array): mixed
    {
        if ($array === []) {
            return null;
        }

        $array = $this->shuffleElements($array);

        return reset($array);
    }

    public function randomKey(array $array = []): int|string|null
    {
        if (!$array) {
            return null;
        }

        return $this->randomElement(array_keys($array));
    }

    public function shuffleElements(array $array): array
    {
        return $this->randomizer->shuffleArray($array);
    }
}
