<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

use DummyGenerator\Core\Calculator\LuhnCalculator;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
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

    public function testComputeCheckDigitZero(): void
    {
        $calculator = new LuhnCalculator();

        self::assertEquals('0', $calculator->computeCheckDigit('0'));
    }

    public function testGenerateLuhnNumber(): void
    {
        $calculator = new LuhnCalculator();

        self::assertEquals(self::LUHN, $calculator->generateLuhnNumber('1789372997'));
    }

    public function testGenerateLuhnNumberInvalid(): void
    {
        $calculator = new LuhnCalculator();

        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Argument should be an integer.');

        $calculator->generateLuhnNumber('12ag');
    }

    // Enhanced validation tests

    public function testCreditCardNumbers(): void
    {
        $calculator = new LuhnCalculator();

        // Valid credit card numbers (Luhn algorithm)
        $validCards = [
            '4532015112830366', // Visa
            '6011111111111117', // Discover
            '5425233430109903', // Mastercard
            '378282246310005',  // American Express
        ];

        foreach ($validCards as $card) {
            self::assertTrue($calculator->isValid($card), "Card $card should be valid");
        }
    }

    public function testInvalidCreditCardNumbers(): void
    {
        $calculator = new LuhnCalculator();

        // Invalid credit card numbers (wrong checksums)
        $invalidCards = [
            '4532015112830367', // Last digit wrong
            '1234567812345671', // Last digit wrong
            '5425233430109904', // Last digit wrong
        ];

        foreach ($invalidCards as $card) {
            self::assertFalse($calculator->isValid($card), "Card $card should be invalid");
        }
    }

    public function testComputeCheckDigitForCreditCards(): void
    {
        $calculator = new LuhnCalculator();

        // Visa: 4532015112830366
        self::assertEquals('6', $calculator->computeCheckDigit('453201511283036'));

        // Discover: 6011111111111117
        self::assertEquals('7', $calculator->computeCheckDigit('601111111111111'));
    }

    public function testComputeCheckDigitSingleDigit(): void
    {
        $calculator = new LuhnCalculator();

        // Single digit inputs
        for ($i = 0; $i <= 9; $i++) {
            $checkDigit = $calculator->computeCheckDigit((string)$i);
            self::assertTrue(is_numeric($checkDigit) && strlen($checkDigit) === 1);
        }
    }

    public function testComputeCheckDigitAllZeros(): void
    {
        $calculator = new LuhnCalculator();

        self::assertEquals('0', $calculator->computeCheckDigit('0'));
        self::assertEquals('0', $calculator->computeCheckDigit('00'));
        self::assertEquals('0', $calculator->computeCheckDigit('000'));
    }

    public function testComputeCheckDigitAllNines(): void
    {
        $calculator = new LuhnCalculator();

        $checkDigit = $calculator->computeCheckDigit('9999999999');
        self::assertIsNumeric($checkDigit);
        self::assertTrue((int)$checkDigit >= 0 && (int)$checkDigit <= 9);
    }

    public function testGenerateLuhnNumberValid(): void
    {
        $calculator = new LuhnCalculator();

        // Generate and verify
        $partial = '123456789';
        $complete = $calculator->generateLuhnNumber($partial);

        self::assertEquals(10, strlen($complete));
        self::assertTrue($calculator->isValid($complete));
        self::assertStringStartsWith($partial, $complete);
    }

    public function testGenerateLuhnNumberConsistency(): void
    {
        $calculator = new LuhnCalculator();

        // Same input should always produce same result
        $partial = '123456789';
        $result1 = $calculator->generateLuhnNumber($partial);
        $result2 = $calculator->generateLuhnNumber($partial);

        self::assertEquals($result1, $result2);
    }

    public function testGenerateLuhnNumberWithSingleDigit(): void
    {
        $calculator = new LuhnCalculator();

        $complete = $calculator->generateLuhnNumber('5');
        self::assertEquals(2, strlen($complete));
        self::assertTrue($calculator->isValid($complete));
    }

    public function testGenerateLuhnNumberWithLongInput(): void
    {
        $calculator = new LuhnCalculator();

        $partial = '12345678901234567890';
        $complete = $calculator->generateLuhnNumber($partial);

        self::assertEquals(21, strlen($complete));
        self::assertTrue($calculator->isValid($complete));
    }

    public function testGenerateLuhnNumberThrowsExceptionForNonNumeric(): void
    {
        $calculator = new LuhnCalculator();

        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('Argument should be an integer.');

        $calculator->generateLuhnNumber('abc');
    }

    public function testGenerateLuhnNumberThrowsExceptionForMixedContent(): void
    {
        $calculator = new LuhnCalculator();

        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('Argument should be an integer.');

        $calculator->generateLuhnNumber('123abc456');
    }

    public function testGenerateLuhnNumberThrowsExceptionForSpaces(): void
    {
        $calculator = new LuhnCalculator();

        $this->expectException(ExtensionArgumentException::class);
        $this->expectExceptionMessage('Argument should be an integer.');

        $calculator->generateLuhnNumber('123 456');
    }

    public function testIsValidWithVariousLengths(): void
    {
        $calculator = new LuhnCalculator();

        // Luhn works with any length, let's test various valid numbers
        $lengths = [5, 10, 15, 16, 19]; // Common card lengths

        foreach ($lengths as $length) {
            $partial = str_repeat('1', $length - 1);
            $complete = $calculator->generateLuhnNumber($partial);

            self::assertEquals($length, strlen($complete));
            self::assertTrue($calculator->isValid($complete));
        }
    }

    public function testIsValidWithLeadingZeros(): void
    {
        $calculator = new LuhnCalculator();

        // Numbers can start with zeros
        $number = '0000000000';
        $complete = $calculator->generateLuhnNumber($number);

        self::assertTrue($calculator->isValid($complete));
    }

    public function testCheckDigitRange(): void
    {
        $calculator = new LuhnCalculator();

        // Check digit should always be 0-9
        for ($i = 0; $i < 100; $i++) {
            $partial = (string)$i;
            $checkDigit = $calculator->computeCheckDigit($partial);

            self::assertTrue((int)$checkDigit >= 0 && (int)$checkDigit <= 9);
        }
    }

    public function testImeiValidation(): void
    {
        $calculator = new LuhnCalculator();

        // IMEI numbers use Luhn algorithm (15 digits)
        $validImeis = [
            '490154203237518', // Valid IMEI
            '356938035643809', // Valid IMEI
        ];

        foreach ($validImeis as $imei) {
            self::assertTrue($calculator->isValid($imei), "IMEI $imei should be valid");
        }
    }

    public function testGenerateLuhnNumberForImei(): void
    {
        $calculator = new LuhnCalculator();

        // Generate 15-digit IMEI
        $partial = '49015420323751'; // 14 digits
        $complete = $calculator->generateLuhnNumber($partial);

        self::assertEquals(15, strlen($complete));
        self::assertTrue($calculator->isValid($complete));
    }

    public function testDoubleDigitHandling(): void
    {
        $calculator = new LuhnCalculator();

        // The Luhn algorithm doubles every second digit and sums the digits
        // Test with number that produces double-digit when doubled (5*2=10)
        $partial = '5555555555';
        $complete = $calculator->generateLuhnNumber($partial);

        self::assertTrue($calculator->isValid($complete));
    }

    public function testExistingValidLuhnNumbers(): void
    {
        $calculator = new LuhnCalculator();

        // From the test constant
        self::assertTrue($calculator->isValid(self::LUHN));
        self::assertEquals(self::LUHN, $calculator->generateLuhnNumber('1789372997'));
    }

    public function testInvalidLuhnNumberDetected(): void
    {
        $calculator = new LuhnCalculator();

        // Take valid and change last digit
        $invalid = substr(self::LUHN, 0, -1) . '0';
        if ($invalid === self::LUHN) {
            $invalid = substr(self::LUHN, 0, -1) . '1';
        }

        self::assertFalse($calculator->isValid($invalid));
    }

}
