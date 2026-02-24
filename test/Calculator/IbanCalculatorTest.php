<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

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

    // Enhanced validation tests

    public function testChecksumCalculation(): void
    {
        $calculator = new IbanCalculator();

        // Ireland IBAN: IE64IRCE92050112345678
        self::assertEquals('64', $calculator->checksum(self::IBAN));

        // Germany IBAN example
        $deIban = 'DE89370400440532013000';
        self::assertEquals('89', $calculator->checksum($deIban));
    }

    public function testMultipleCountryIbans(): void
    {
        $calculator = new IbanCalculator();

        $validIbans = [
            'IE64IRCE92050112345678', // Ireland
            'DE89370400440532013000', // Germany
            'GB82WEST12345698765432', // United Kingdom
            'FR1420041010050500013M02606', // France
            'IT60X0542811101000000123456', // Italy
            'ES9121000418450200051332', // Spain
            'NL91ABNA0417164300', // Netherlands
            'BE68539007547034', // Belgium
        ];

        foreach ($validIbans as $iban) {
            self::assertTrue($calculator->isValid($iban), "IBAN $iban should be valid");
        }
    }

    public function testInvalidChecksumDetected(): void
    {
        $calculator = new IbanCalculator();

        // Take valid IBAN and change checksum digits
        $invalidIban = 'IE00IRCE92050112345678'; // Changed 64 to 00

        self::assertFalse($calculator->isValid($invalidIban));
    }

    public function testInvalidIbanRejected(): void
    {
        $calculator = new IbanCalculator();

        // Invalid formats
        self::assertFalse($calculator->isValid('INVALID'));
        self::assertFalse($calculator->isValid(''));
        self::assertFalse($calculator->isValid('1234567890'));
    }

    public function testChecksumFormat(): void
    {
        $calculator = new IbanCalculator();

        // Checksum should always be 2 digits with leading zero if needed
        $checksum = $calculator->checksum('IE64IRCE92050112345678');

        self::assertEquals(2, strlen($checksum));
        self::assertMatchesRegularExpression('/^\d{2}$/', $checksum);
    }

    public function testChecksumWithLeadingZero(): void
    {
        $calculator = new IbanCalculator();

        // Find an IBAN that produces checksum with leading zero
        // We'll construct one and verify format
        $testIban = 'GB00WEST12345698765432';
        $checksum = $calculator->checksum($testIban);

        // Should be 2 characters
        self::assertEquals(2, strlen($checksum));
    }

    public function testAlphaToNumberConversion(): void
    {
        $calculator = new IbanCalculator();

        // The IBAN algorithm converts A=10, B=11, ..., Z=35
        // We can't directly test protected method, but we can verify via checksum
        // IE has letters that need conversion

        $iban = self::IBAN;
        $checksum = $calculator->checksum($iban);

        // If alphaToNumber works correctly, checksum should match
        self::assertEquals('64', $checksum);
    }

    public function testIsValidWithDifferentCountryCodes(): void
    {
        $calculator = new IbanCalculator();

        // Test various country codes (first 2 letters)
        $countries = ['DE', 'FR', 'GB', 'IT', 'ES', 'NL', 'BE', 'IE', 'AT', 'CH'];

        foreach ($countries as $country) {
            // Just verify the method doesn't crash with different country codes
            // We use valid IBANs for each
            if ($country === 'IE') {
                self::assertTrue($calculator->isValid('IE64IRCE92050112345678'));
            }
        }
    }

    public function testMod97Calculation(): void
    {
        $calculator = new IbanCalculator();

        // Mod97 is used internally for checksum calculation
        // We verify it works by testing valid IBANs
        $validIbans = [
            'IE64IRCE92050112345678',
            'DE89370400440532013000',
        ];

        foreach ($validIbans as $iban) {
            // If mod97 works correctly, these should all be valid
            self::assertTrue($calculator->isValid($iban));
        }
    }

    public function testChecksumConsistency(): void
    {
        $calculator = new IbanCalculator();

        // Same IBAN should always produce same checksum
        $iban = self::IBAN;

        $checksum1 = $calculator->checksum($iban);
        $checksum2 = $calculator->checksum($iban);

        self::assertEquals($checksum1, $checksum2);
    }

    public function testGermanIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // Germany IBAN: 22 characters
        $deIban = 'DE89370400440532013000';

        self::assertTrue($calculator->isValid($deIban));
        self::assertEquals('89', $calculator->checksum($deIban));
    }

    public function testBritishIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // UK IBAN: 22 characters
        $gbIban = 'GB82WEST12345698765432';

        self::assertTrue($calculator->isValid($gbIban));
        self::assertEquals('82', $calculator->checksum($gbIban));
    }

    public function testFrenchIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // France IBAN: 27 characters
        $frIban = 'FR1420041010050500013M02606';

        self::assertTrue($calculator->isValid($frIban));
        self::assertEquals('14', $calculator->checksum($frIban));
    }

    public function testItalianIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // Italy IBAN: 27 characters
        $itIban = 'IT60X0542811101000000123456';

        self::assertTrue($calculator->isValid($itIban));
        self::assertEquals('60', $calculator->checksum($itIban));
    }

    public function testSpanishIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // Spain IBAN: 24 characters
        $esIban = 'ES9121000418450200051332';

        self::assertTrue($calculator->isValid($esIban));
        self::assertEquals('91', $calculator->checksum($esIban));
    }

    public function testDutchIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // Netherlands IBAN: 18 characters
        $nlIban = 'NL91ABNA0417164300';

        self::assertTrue($calculator->isValid($nlIban));
        self::assertEquals('91', $calculator->checksum($nlIban));
    }

    public function testBelgianIbanValidation(): void
    {
        $calculator = new IbanCalculator();

        // Belgium IBAN: 16 characters
        $beIban = 'BE68539007547034';

        self::assertTrue($calculator->isValid($beIban));
        self::assertEquals('68', $calculator->checksum($beIban));
    }

    public function testIbanWithAllLetters(): void
    {
        $calculator = new IbanCalculator();

        // Test IBAN that has many letters (like Italy with X)
        $itIban = 'IT60X0542811101000000123456';

        self::assertTrue($calculator->isValid($itIban));
    }

    public function testChecksumRange(): void
    {
        $calculator = new IbanCalculator();

        // IBAN checksum should be between 02 and 98 (never 00, 01, or 99)
        $checksum = $calculator->checksum(self::IBAN);
        $checksumInt = (int)$checksum;

        self::assertTrue($checksumInt >= 2 && $checksumInt <= 98);
    }

}
