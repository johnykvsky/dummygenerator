<?php

declare(strict_types=1);

namespace Calculator;

use DummyGenerator\Core\Calculator\IbanCalculator;
use PHPUnit\Framework\TestCase;

class IbanCalculatorTest extends TestCase
{
    private const string IBAN = 'IE64IRCE92050112345678';

    public function testChecksum(): void
    {
        $calculator = new IbanCalculator();

        self::assertEquals(64, $calculator->checksum('IE64IRCE92050112345678'));
    }

    public function testIsValid(): void
    {
        $calculator = new IbanCalculator();

        self::assertTrue($calculator->isValid(self::IBAN));
    }
}
