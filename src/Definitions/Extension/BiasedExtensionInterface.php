<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface BiasedExtensionInterface extends ExtensionInterface
{
    /**
     * Returns a biased integer between $min and $max (both inclusive).
     * The distribution depends on $function.
     *
     * The algorithm creates two doubles, x ∈ [0, 1], y ∈ [0, 1) and checks whether the
     * return value of $function for x is greater than or equal to y. If this is
     * the case the number is accepted and x is mapped to the appropriate integer
     * between $min and $max. Otherwise two new doubles are created until the pair
     * is accepted.
     *
     * @param int      $min      Minimum value of the generated integers.
     * @param int      $max      Maximum value of the generated integers.
     * @param callable|string $function A function mapping x ∈ [0, 1] onto a double ∈ [0, 1]
     *
     * @return int An integer between $min and $max.
     *
     * @example 48
     */
    public function biasedNumberBetween(int $min = 0, int $max = 100, callable|string $function = 'sqrt'): int;

    /**
     * 'unbiased' creates an unbiased distribution by giving each value the same value of one.
     *
     * @example 1
     */
    public function unbiased(): int;

    /**
     * 'linearLow' favors lower numbers. The probability decreases in a linear fashion.
     *
     * @example 0.19434
     */
    public function linearLow(float $x): float;

    /**
     * 'linearHigh' favors higher numbers. The probability increases in a linear fashion.
     *
     * @example 0.234344
     */
    public function linearHigh(float $x): float;
}
