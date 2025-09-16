<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;

class Number implements NumberExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    public function numberBetween(int $min = 0, int $max = 2147483647): int
    {
        return $this->randomizer->getInt($min, $max);
    }

    public function randomDigit(): int
    {
        return $this->randomizer->getInt(0, 9);
    }

    public function randomDigitNot(int $except = 0, int $retries = 1000): int
    {
        $count = 0;
        do {
            if ($count > $retries) {
                throw new ExtensionRuntimeException('Retries limit exceeded for randomDigitNot.');
            }

            $result = $this->numberBetween(0, 9);
            $count++;
        } while ($result === $except);

        return $result;
    }

    public function randomDigitNotZero(): int
    {
        return $this->randomizer->getInt(1, 9);
    }

    public function randomFloat(?int $nbMaxDecimals = null, float $min = 0, ?float $max = null): float
    {
        if ($max > PHP_FLOAT_MAX) {
            throw new ExtensionArgumentException('randomFloat() can only generate numbers up to PHP_FLOAT_MAX');
        }

        $float = $this->randomizer->getFloat($min, $max ?? PHP_FLOAT_MAX);

        if (null === $nbMaxDecimals) {
            $nbMaxDecimals = $this->randomDigitNot();
        }

        return round($float, $nbMaxDecimals);
    }

    public function randomNumber(?int $nbDigits = null, bool $strict = false): int
    {
        if (null === $nbDigits) {
            $nbDigits = $this->randomDigitNotZero();
        }

        $max = 10 ** $nbDigits - 1;

        if ($max > PHP_INT_MAX) {
            throw new ExtensionArgumentException('randomNumber() can only generate numbers up to PHP_INT_MAX');
        }

        if ($strict) {
            return $this->randomizer->getInt(10 ** ($nbDigits - 1), $max);
        }

        return $this->randomizer->getInt(0, $max);
    }

    public function boolean(int $chanceOfGettingTrue = 50): bool
    {
        return $this->randomizer->getBool($chanceOfGettingTrue);
    }
}
