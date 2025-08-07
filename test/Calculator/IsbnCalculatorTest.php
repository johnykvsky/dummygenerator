<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

use DummyGenerator\Core\Calculator\IsbnCalculator;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use PHPUnit\Framework\TestCase;

class IsbnCalculatorTest extends TestCase
{
    private const string ISBN10 = '2123456802';

    public function testChecksum(): void
    {
        $calculator = new IsbnCalculator();

        self::assertEquals(2, $calculator->checksum('212345680'));
    }

    public function testChecksumInvalid(): void
    {
        $calculator = new IsbnCalculator();

        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Input length should be equal to 9');

        $calculator->checksum('2123456801');
    }

    public function testIsValid(): void
    {
        $calculator = new IsbnCalculator();

        self::assertTrue($calculator->isValid(self::ISBN10));
    }

    public function testIsValidInvalid(): void
    {
        $calculator = new IsbnCalculator();

        self::assertFalse($calculator->isValid('invalid_number'));
    }
}
