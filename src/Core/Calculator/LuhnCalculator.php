<?php

declare(strict_types = 1);

namespace DummyGenerator\Core\Calculator;

use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

/**
 * Utility class for validating LUHN numbers
 */
class LuhnCalculator implements LuhnCalculatorInterface
{
    protected function checksum(string $number): int
    {
        $length = strlen($number);
        $sum = 0;

        for ($i = $length - 1; $i >= 0; $i -= 2) {
            $sum += (int) $number[$i];
        }

        for ($i = $length - 2; $i >= 0; $i -= 2) {
            $sum += array_sum(str_split((string) ((int) $number[$i] * 2)));
        }

        return $sum % 10;
    }

    public function computeCheckDigit(string $partialNumber): string
    {
        $checkDigit = $this->checksum($partialNumber . '0');

        if ($checkDigit === 0) {
            return '0';
        }

        return (string) (10 - $checkDigit);
    }

    public function isValid(string $number): bool
    {
        return $this->checksum($number) === 0;
    }

    public function generateLuhnNumber(string $partialValue): string
    {
        if (!preg_match('/^\d+$/', $partialValue)) {
            throw new ExtensionArgumentException('Argument should be an integer.');
        }

        return $partialValue . $this->computeCheckDigit($partialValue);
    }
}
