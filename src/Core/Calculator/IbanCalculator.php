<?php

declare(strict_types=1);

namespace DummyGenerator\Core\Calculator;

use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;

/**
 * Utility class for working with IBAN numbers
 */
class IbanCalculator implements IbanCalculatorInterface
{
    public function checksum(string $iban): string
    {
        // Move first four digits to end and set checksum to '00'
        $checkString = substr($iban, 4) . substr($iban, 0, 2) . '00';

        // Replace all letters with their number equivalents
        $checkString = preg_replace_callback('/[A-Z]/', fn (array $a) => $this->alphaToNumberCallback($a), $checkString) ?? '';

        // Perform mod 97 and subtract from 98
        $checksum = 98 - $this->mod97($checkString);

        return str_pad((string)$checksum, 2, '0', STR_PAD_LEFT);
    }

    public function isValid(string $iban): bool
    {
        return $this->checksum($iban) === substr($iban, 2, 2);
    }

    /**
     * Convert a letter to a number.
     *
     * @param array<int|string, string> $match
     * @return string
     */
    protected function alphaToNumberCallback(array $match): string
    {
        return (string) $this->alphaToNumber($match[0]);
    }

    /**
     * Converts letter to number
     */
    protected function alphaToNumber(string $char): int
    {
        return ord($char) - 55;
    }

    /**
     * Calculates mod97 number on a numeric string
     */
    protected function mod97(string $number): int
    {
        $checksum = (int) $number[0];

        for ($i = 1, $size = strlen($number); $i < $size; ++$i) {
            $checksum = (10 * $checksum + (int)$number[$i]) % 97;
        }

        return $checksum;
    }
}
