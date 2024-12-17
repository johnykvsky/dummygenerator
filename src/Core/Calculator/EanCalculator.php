<?php

declare(strict_types=1);

namespace DummyGenerator\Core\Calculator;

use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;

/**
 * Utility class for validating EAN-8 and EAN-13 numbers
 */
class EanCalculator implements EanCalculatorInterface
{
    /**
     * @var string EAN validation pattern
     */
    public const string PATTERN = '/^(?:\d{8}|\d{13})$/';

    public function checksum(string $digits): int
    {
        $sequence = (strlen($digits) + 1) === 8 ? [3, 1] : [1, 3];
        $sums = 0;

        foreach (str_split($digits) as $n => $digit) {
            $sums += ((int) $digit) * $sequence[$n % 2];
        }

        return (10 - $sums % 10) % 10;
    }

    public function isValid(string $ean): bool
    {
        if (!preg_match(self::PATTERN, $ean)) {
            return false;
        }

        return $this->checksum(substr($ean, 0, -1)) === (int) substr($ean, -1);
    }
}
