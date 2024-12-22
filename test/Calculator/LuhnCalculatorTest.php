<?php

declare(strict_types=1);

namespace Calculator;

use DummyGenerator\Core\Calculator\LuhnCalculator;
use PHPUnit\Framework\TestCase;

class LuhnCalculatorTest extends TestCase
{
    private const string LUHN = '17893729974';

    public function testIsValid(): void
    {
        $calculator = new LuhnCalculator();

        self::assertTrue($calculator->isValid(self::LUHN));
    }

    public function testComputeCheckDigit(): void
    {
        $calculator = new LuhnCalculator();

        self::assertEquals('4', $calculator->computeCheckDigit('1789372997'));
    }

    public function testGenerateLuhnNumber(): void
    {
        $calculator = new LuhnCalculator();

        self::assertEquals(self::LUHN, $calculator->generateLuhnNumber('1789372997'));
    }
}
