<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface NumberExtensionInterface extends ExtensionInterface
{
    /**
     * Returns a random number between $int1 and $int2 (any order)
     *
     * @param int $min default to 0
     * @param int $max defaults to 32 bit max integer, ie 2147483647
     *
     * @example 79907610
     */
    public function numberBetween(int $min, int $max): int;

    /**
     * Returns a random number between 0 and 9
     *
     * @example 8
     */
    public function randomDigit(): int;

    /**
     * Generates a random digit, which cannot be $except
     *
     * @example 123
     */
    public function randomDigitNot(int $except, int $retries = 1000): int;

    /**
     * Returns a random number between 1 and 9
     *
     * @example 3
     */
    public function randomDigitNotZero(): int;

    /**
     * Return a random float number
     *
     * @example 48.8932
     */
    public function randomFloat(?int $nbMaxDecimals, float $min, ?float $max): float;

    /**
     * Returns a random integer with 0 to $nbDigits digits.
     *
     * The maximum value returned is PHP_INT_MAX
     *
     * @param int|null $nbDigits Defaults to a random number between 1 and 9
     * @param bool     $strict   Whether the returned number should have exactly $nbDigits
     *
     * @example 79907610
     */
    public function randomNumber(?int $nbDigits, bool $strict): int;

    /**
     * Return a boolean, true or false.
     *
     * @param int $chanceOfGettingTrue Between 0 (always get false) and 100 (always get true)
     *
     * @example true
     */
    public function boolean(int $chanceOfGettingTrue = 50): bool;
}
