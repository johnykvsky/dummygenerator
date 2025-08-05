<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

use DummyGenerator\Core\Calculator\IsbnCalculator;
use PHPUnit\Framework\TestCase;

class IsbnCalculatorTest extends TestCase
{
    private const string ISBN10 = '2123456802';
    // private const string ISBN13 = '9782123456803';

    public function testChecksum(): void
    {
        $calculator = new IsbnCalculator();

        self::assertEquals(2, $calculator->checksum('212345680'));
    }

    public function testIsValid(): void
    {
        $calculator = new IsbnCalculator();

        self::assertTrue($calculator->isValid(self::ISBN10));
    }
}
