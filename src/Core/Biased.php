<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\BiasedExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

class Biased implements BiasedExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    public function biasedNumberBetween(int $min = 0, int $max = 100, callable|string $function = 'sqrt'): int
    {
        if (!is_callable($function)) {
            throw new ExtensionArgumentException('Given $function must be a callable');
        }

        do {
            $x = $this->randomizer->getInt(0, PHP_INT_MAX) / PHP_INT_MAX;
            $y = $this->randomizer->getInt(0, PHP_INT_MAX) / (PHP_INT_MAX + 1);
        } while ($function($x) < $y);

        return (int) floor($x * ($max - $min + 1) + $min);
    }

    public function unbiased(): int
    {
        return 1;
    }

    public function linearLow(float $x): float
    {
        return 1 - $x;
    }

    public function linearHigh(float $x): float
    {
        return $x;
    }
}
