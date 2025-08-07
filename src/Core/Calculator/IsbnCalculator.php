<?php

declare(strict_types = 1);

namespace DummyGenerator\Core\Calculator;

use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

/**
 * Utility class for validating ISBN-10
 */
class IsbnCalculator implements IsbnCalculatorInterface
{
    public const string PATTERN = '/^\d{9}[\dX]$/';

    public function checksum(string $input): string
    {
        // We're calculating check digit for ISBN-10, so the length of the input should be 9
        $length = 9;

        if (strlen($input) !== $length) {
            throw new ExtensionArgumentException(sprintf('Input length should be equal to %d', $length));
        }

        $digits = str_split($input);
        array_walk(
            $digits,
            static function (&$digit, $position) {
                $digit = (10 - $position) * (int) $digit;
            },
        );
        $result = (11 - array_sum($digits) % 11) % 11;

        // 10 is replaced by X
        return ($result < 10) ? (string) $result : 'X';
    }

    public function isValid(string $isbn): bool
    {
        if (!preg_match(static::PATTERN, $isbn)) {
            return false;
        }

        return $this->checksum(substr($isbn, 0, -1)) === substr($isbn, -1);
    }
}
