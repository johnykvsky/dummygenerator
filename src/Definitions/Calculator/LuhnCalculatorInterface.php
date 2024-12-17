<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Calculator;

/**
 * Utility class for generating and validating Luhn numbers.
 *
 * Luhn algorithm is used to validate credit card numbers, IMEI numbers, and
 * National Provider Identifier numbers.
 *
 * @see http://en.wikipedia.org/wiki/Luhn_algorithm
 */
interface LuhnCalculatorInterface extends CalculatorInterface
{
    /**
     * Checks whether a number (partial number + check digit) is Luhn compliant
     */
    public function isValid(string $number): bool;
    public function computeCheckDigit(string $partialNumber): string;
    /**
     * Generate a Luhn compliant number.
     */
    public function generateLuhnNumber(string $partialValue): string;
}
