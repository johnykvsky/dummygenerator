<?php

declare(strict_types=1);

namespace Calculator;

use DummyGenerator\Core\Calculator\EanCalculator;
use PHPUnit\Framework\TestCase;

class EanCalculatorTest extends TestCase
{
    private const string EAN8 = '96385074';
    private const string EAN13 = '5901234123457';

    public function testChecksum(): void
    {
        $calculator = new EanCalculator;

        self::assertEquals(4, $calculator->checksum('9638507'));
        self::assertEquals(7, $calculator->checksum('590123412345'));
    }

    public function testIsValid(): void
    {
        $calculator = new EanCalculator;

        self::assertTrue($calculator->isValid(self::EAN8));
        self::assertTrue($calculator->isValid(self::EAN13));
    }
}
