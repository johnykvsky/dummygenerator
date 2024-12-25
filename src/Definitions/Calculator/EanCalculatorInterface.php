<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Calculator;

interface EanCalculatorInterface extends CalculatorInterface
{
    /**
     * Computes the checksum of an EAN number.
     *
     * @see https://en.wikipedia.org/wiki/International_Article_Number
     */
    public function checksum(string $digits): int;

    /**
     * Checks whether the provided number is an EAN compliant number and that
     * the checksum is correct.
     */
    public function isValid(string $ean): bool;
}
