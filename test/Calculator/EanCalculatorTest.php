<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

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

    public function testIsInvalid(): void
    {
        $calculator = new EanCalculator;

        self::assertFalse($calculator->isValid('123123'));
    }

    // Enhanced validation tests

    public function testEan8Validation(): void
    {
        $calculator = new EanCalculator();

        // Valid EAN-8
        self::assertTrue($calculator->isValid(self::EAN8));

        // Invalid EAN-8 (wrong checksum)
        self::assertFalse($calculator->isValid('96385075'));
    }

    public function testEan13Validation(): void
    {
        $calculator = new EanCalculator();

        // Valid EAN-13
        self::assertTrue($calculator->isValid(self::EAN13));

        // Invalid EAN-13 (wrong checksum)
        self::assertFalse($calculator->isValid('5901234123458'));
    }

    public function testInvalidLengthRejected(): void
    {
        $calculator = new EanCalculator();

        // Too short
        self::assertFalse($calculator->isValid('123'));

        // Too long
        self::assertFalse($calculator->isValid('12345678901234'));

        // 9 digits (neither 8 nor 13)
        self::assertFalse($calculator->isValid('123456789'));

        // 12 digits (not valid EAN length)
        self::assertFalse($calculator->isValid('123456789012'));
    }

    public function testNonNumericInputRejected(): void
    {
        $calculator = new EanCalculator();

        self::assertFalse($calculator->isValid('abcdefgh'));
        self::assertFalse($calculator->isValid('12345ABC'));
        self::assertFalse($calculator->isValid('123-456-78'));
        self::assertFalse($calculator->isValid('12 34 56 78'));
    }

    public function testEmptyStringRejected(): void
    {
        $calculator = new EanCalculator();

        self::assertFalse($calculator->isValid(''));
    }

    public function testEan8ChecksumCalculation(): void
    {
        $calculator = new EanCalculator();

        // EAN-8: 96385074
        // Checksum for 9638507 should be 4
        self::assertEquals(4, $calculator->checksum('9638507'));

        // Test with different EAN-8
        self::assertEquals(0, $calculator->checksum('0000000'));

        // Calculate actual checksum for 1234567
        $actualChecksum = $calculator->checksum('1234567');
        $fullEan8 = '1234567' . $actualChecksum;
        self::assertTrue($calculator->isValid($fullEan8));
    }

    public function testEan13ChecksumCalculation(): void
    {
        $calculator = new EanCalculator();

        // EAN-13: 5901234123457
        // Checksum for 590123412345 should be 7
        self::assertEquals(7, $calculator->checksum('590123412345'));

        // Test with different EAN-13
        self::assertEquals(0, $calculator->checksum('000000000000'));

        // Calculate actual checksum for 123456789012
        $actualChecksum = $calculator->checksum('123456789012');
        $fullEan13 = '123456789012' . $actualChecksum;
        self::assertTrue($calculator->isValid($fullEan13));
    }

    public function testEan8WithAllZeros(): void
    {
        $calculator = new EanCalculator();

        self::assertTrue($calculator->isValid('00000000'));
    }

    public function testEan13WithAllZeros(): void
    {
        $calculator = new EanCalculator();

        self::assertTrue($calculator->isValid('0000000000000'));
    }

    public function testEan8WithAllNines(): void
    {
        $calculator = new EanCalculator();

        // Calculate correct checksum for 9999999
        $checksum = $calculator->checksum('9999999');
        $validEan8 = '9999999' . $checksum;

        self::assertTrue($calculator->isValid($validEan8));
    }

    public function testEan13WithAllNines(): void
    {
        $calculator = new EanCalculator();

        // Calculate correct checksum for 999999999999
        $checksum = $calculator->checksum('999999999999');
        $validEan13 = '999999999999' . $checksum;

        self::assertTrue($calculator->isValid($validEan13));
    }

    public function testChecksumReturnsDigit(): void
    {
        $calculator = new EanCalculator();

        // Checksum should always be 0-9
        for ($i = 0; $i < 10; $i++) {
            $checksum = $calculator->checksum('123456' . $i);
            self::assertTrue($checksum >= 0 && $checksum <= 9);
        }
    }

    public function testRealWorldEan13Examples(): void
    {
        $calculator = new EanCalculator();

        // Common EAN-13 examples (product barcodes)
        $validEans = [
            '5901234123457', // Example from code
            '4006381333931', // German product
            '8712345678906', // Netherlands product
        ];

        foreach ($validEans as $ean) {
            self::assertTrue($calculator->isValid($ean), "EAN $ean should be valid");
        }
    }

    public function testInvalidChecksumDetected(): void
    {
        $calculator = new EanCalculator();

        // Take valid EAN-8 and change last digit
        $invalidEan8 = substr(self::EAN8, 0, -1) . '0';
        if ($invalidEan8 === self::EAN8) {
            $invalidEan8 = substr(self::EAN8, 0, -1) . '1';
        }
        self::assertFalse($calculator->isValid($invalidEan8));

        // Take valid EAN-13 and change last digit
        $invalidEan13 = substr(self::EAN13, 0, -1) . '0';
        if ($invalidEan13 === self::EAN13) {
            $invalidEan13 = substr(self::EAN13, 0, -1) . '1';
        }
        self::assertFalse($calculator->isValid($invalidEan13));
    }

    public function testChecksumConsistency(): void
    {
        $calculator = new EanCalculator();

        // Same input should always produce same checksum
        $input = '1234567';
        $checksum1 = $calculator->checksum($input);
        $checksum2 = $calculator->checksum($input);

        self::assertEquals($checksum1, $checksum2);
    }

}
