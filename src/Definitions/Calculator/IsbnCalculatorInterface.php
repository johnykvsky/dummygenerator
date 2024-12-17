<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Calculator;

interface IsbnCalculatorInterface extends CalculatorInterface
{
    /**
     * ISBN-10 check digit
     *
     * @see http://en.wikipedia.org/wiki/International_Standard_Book_Number#ISBN-10_check_digits
     *
     * @param string $input ISBN without check-digit
     *
     * @throws \LengthException When wrong input length passed
     */
    public function checksum(string $input): string;
    /**
     * Checks whether the provided number is a valid ISBN-10 number
     */
    public function isValid(string $isbn): bool;
}
