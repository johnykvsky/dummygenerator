<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Extension\NumberExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class Number implements NumberExtensionInterface
{
    public function __construct(
        protected RandomizerInterface $randomizer
    ) {
    }

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

        if ($nbDigits <= 0) {
            throw new ExtensionArgumentException('randomNumber() $nbDigits must be greater than 0');
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

    public function boolean(int|float $chanceOfGettingTrue = 50): bool
    {
        return $this->randomizer->getBool($chanceOfGettingTrue);
    }

    public function percentage(int $decimals = 0, float $min = 0.0, float $max = 100.0): float|int
    {
        if ($min < 0.0 || $max > 100.0) {
            throw new ExtensionArgumentException('percentage() min and max must be between 0 and 100');
        }

        if ($min > $max) {
            throw new ExtensionArgumentException('percentage() min cannot be greater than max');
        }

        if ($decimals < 0) {
            throw new ExtensionArgumentException('percentage() decimals must be greater than or equal to 0');
        }

        if ($decimals === 0) {
            return $this->randomizer->getInt((int) round($min), (int) round($max));
        }

        return $this->randomFloat($decimals, $min, $max);
    }

    public function hexadecimal(int $nbDigits = 6): string
    {
        if ($nbDigits <= 0) {
            throw new ExtensionArgumentException('hexadecimal() $nbDigits must be greater than 0');
        }

        $bytesNeeded = (int) ceil($nbDigits / 2);
        $hex = bin2hex($this->randomizer->getBytes($bytesNeeded));

        return substr($hex, 0, $nbDigits);
    }

    public function binary(int $length = 8): string
    {
        if ($length <= 0) {
            throw new ExtensionArgumentException('binary() $length must be greater than 0');
        }

        $result = '';
        for ($i = 0; $i < $length; ++$i) {
            $result .= (string) $this->randomizer->getInt(0, 1);
        }

        return $result;
    }
}
