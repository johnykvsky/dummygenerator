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

    // Enhanced validation tests

    public function testIsbn10WithXChecksum(): void
    {
        $calculator = new IsbnCalculator();

        // ISBN-10 with X as checksum (represents 10)
        // Example: 043942089X is valid
        self::assertTrue($calculator->isValid('043942089X'));

        // Verify checksum calculation returns X
        self::assertEquals('X', $calculator->checksum('043942089'));
    }

    public function testIsbn10WithNumericChecksum(): void
    {
        $calculator = new IsbnCalculator();

        // Test with numeric checksum
        self::assertTrue($calculator->isValid(self::ISBN10));
        self::assertEquals('2', $calculator->checksum('212345680'));
    }

    public function testInvalidIsbn10Length(): void
    {
        $calculator = new IsbnCalculator();

        // Too short
        self::assertFalse($calculator->isValid('123456789'));

        // Too long
        self::assertFalse($calculator->isValid('12345678901'));

        // Empty
        self::assertFalse($calculator->isValid(''));
    }

    public function testIsbn10WithNonNumericCharacters(): void
    {
        $calculator = new IsbnCalculator();

        // Letters in wrong positions (only last position can be X)
        self::assertFalse($calculator->isValid('12345678AB'));
        self::assertFalse($calculator->isValid('A123456789'));
        self::assertFalse($calculator->isValid('12345-6789'));
    }

    public function testIsbn10WithXInWrongPosition(): void
    {
        $calculator = new IsbnCalculator();

        // X is only valid as last character
        self::assertFalse($calculator->isValid('X123456789'));
        self::assertFalse($calculator->isValid('12345X6789'));
    }

    public function testIsbn10ChecksumValidation(): void
    {
        $calculator = new IsbnCalculator();

        // Valid ISBN-10: checksum matches
        self::assertTrue($calculator->isValid('2123456802'));

        // Invalid ISBN-10: wrong checksum
        self::assertFalse($calculator->isValid('2123456801'));
        self::assertFalse($calculator->isValid('2123456803'));
    }

    public function testChecksumThrowsExceptionForInvalidLength(): void
    {
        $calculator = new IsbnCalculator();

        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('Input length should be equal to 9');

        $calculator->checksum('12345678'); // 8 digits instead of 9
    }

    public function testChecksumThrowsExceptionForTooLong(): void
    {
        $calculator = new IsbnCalculator();

        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('Input length should be equal to 9');

        $calculator->checksum('1234567890'); // 10 digits instead of 9
    }

    public function testChecksumAllPossibleDigits(): void
    {
        $calculator = new IsbnCalculator();

        // Test checksum returns valid digit or X
        for ($i = 0; $i <= 9; $i++) {
            $input = '12345678' . $i;
            $checksum = $calculator->checksum($input);

            // Checksum should be 0-9 or X
            self::assertTrue(
                is_numeric($checksum) || $checksum === 'X',
                "Checksum should be digit or X, got: $checksum"
            );
        }
    }

    public function testRealWorldIsbn10Examples(): void
    {
        $calculator = new IsbnCalculator();

        $validIsbns = [
            '0306406152', // Numeric checksum
            '043942089X', // X checksum
            '0201530821', // Another valid ISBN-10
            '0596009208', // O'Reilly book
        ];

        foreach ($validIsbns as $isbn) {
            self::assertTrue($calculator->isValid($isbn), "ISBN $isbn should be valid");
        }
    }

    public function testIsbn10WithLeadingZeros(): void
    {
        $calculator = new IsbnCalculator();

        // ISBNs can start with 0
        self::assertTrue($calculator->isValid('0306406152'));

        // Test checksum calculation with leading zero
        self::assertEquals('2', $calculator->checksum('030640615'));
    }

    public function testChecksumConsistency(): void
    {
        $calculator = new IsbnCalculator();

        // Same input should always produce same checksum
        $input = '123456789';
        $checksum1 = $calculator->checksum($input);
        $checksum2 = $calculator->checksum($input);

        self::assertEquals($checksum1, $checksum2);
    }

    public function testIsbn10AllZeros(): void
    {
        $calculator = new IsbnCalculator();

        // Calculate checksum for 000000000
        $checksum = $calculator->checksum('000000000');
        $isbn = '000000000' . $checksum;

        self::assertTrue($calculator->isValid($isbn));
    }

    public function testIsbn10AllNines(): void
    {
        $calculator = new IsbnCalculator();

        // Calculate checksum for 999999999
        $checksum = $calculator->checksum('999999999');
        $isbn = '999999999' . $checksum;

        self::assertTrue($calculator->isValid($isbn));
    }

    public function testInvalidChecksumDetected(): void
    {
        $calculator = new IsbnCalculator();

        // Take valid ISBN and corrupt checksum
        $validIsbn = self::ISBN10;
        $invalidIsbn = substr($validIsbn, 0, -1) . '0';

        if ($invalidIsbn === $validIsbn) {
            $invalidIsbn = substr($validIsbn, 0, -1) . '1';
        }

        self::assertFalse($calculator->isValid($invalidIsbn));
    }

    public function testLowercaseXRejected(): void
    {
        $calculator = new IsbnCalculator();

        // Lowercase 'x' should not be valid (must be uppercase 'X')
        self::assertFalse($calculator->isValid('043942089x'));
    }

    public function testPatternMatchingOnly10Digits(): void
    {
        $calculator = new IsbnCalculator();

        // Pattern requires exactly 10 characters (9 digits + 1 digit or X)
        self::assertFalse($calculator->isValid('12345'));
        self::assertFalse($calculator->isValid('12345678901234'));
    }

}
