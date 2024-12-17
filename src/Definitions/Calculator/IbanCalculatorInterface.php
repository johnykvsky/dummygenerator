<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Calculator;

interface IbanCalculatorInterface extends CalculatorInterface
{
    /**
     * Generates IBAN Checksum
     */
    public function checksum(string $iban): string;
    /**
     * Checks whether an IBAN has a valid checksum
     */
    public function isValid(string $iban): bool;
}
