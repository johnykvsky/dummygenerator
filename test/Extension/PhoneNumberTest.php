<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Calculator\LuhnCalculator;
use DummyGenerator\Core\PhoneNumber;
use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\Extension\PhoneNumberExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class PhoneNumberTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(LuhnCalculatorInterface::class, LuhnCalculator::class);
        $container->set(PhoneNumberExtensionInterface::class, PhoneNumber::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testPhoneNumber(): void
    {
        self::assertNotEmpty($this->generator->phoneNumber());
    }

    public function testE164PhoneNumber(): void
    {
        self::assertNotEmpty($this->generator->e164PhoneNumber());
    }

    public function testImei(): void
    {
        self::assertIsNumeric($this->generator->imei());
    }

    // Enhanced validation tests

    public function testPhoneNumberIsString(): void
    {
        $phoneNumber = $this->generator->phoneNumber();

        self::assertIsString($phoneNumber);
        self::assertNotEmpty($phoneNumber);
        self::assertGreaterThan(5, strlen($phoneNumber));
    }

    public function testPhoneNumberContainsDigits(): void
    {
        $phoneNumber = $this->generator->phoneNumber();

        // Should contain at least some digits
        self::assertMatchesRegularExpression('/\d+/', $phoneNumber);
    }

    public function testE164PhoneNumberFormat(): void
    {
        $phoneNumber = $this->generator->e164PhoneNumber();

        // E.164 format starts with + and has only digits
        self::assertStringStartsWith('+', $phoneNumber);

        // Remove the + and check if rest is numeric
        $digits = substr($phoneNumber, 1);
        self::assertMatchesRegularExpression('/^\d+$/', $digits);

        // E.164 can be up to 15 digits (plus the +)
        self::assertLessThanOrEqual(16, strlen($phoneNumber));
    }

    public function testE164PhoneNumberLength(): void
    {
        $phoneNumber = $this->generator->e164PhoneNumber();

        // E.164: + followed by 1-15 digits
        self::assertTrue(strlen($phoneNumber) >= 2 && strlen($phoneNumber) <= 16);
    }

    public function testImeiLength(): void
    {
        $imei = $this->generator->imei();

        // IMEI is 15 digits
        self::assertEquals(15, strlen($imei));
    }

    public function testImeiContainsOnlyDigits(): void
    {
        $imei = $this->generator->imei();

        self::assertMatchesRegularExpression('/^\d{15}$/', $imei);
    }

    public function testImeiHasValidChecksum(): void
    {
        $imei = $this->generator->imei();

        // IMEI uses Luhn algorithm for checksum validation
        // Calculate Luhn checksum
        $sum = 0;
        for ($i = 0; $i < 14; $i++) {
            $digit = (int)$imei[$i];
            if ($i % 2 === 1) {
                $digit *= 2;
                if ($digit > 9) {
                    $digit -= 9;
                }
            }
            $sum += $digit;
        }
        $checksum = (10 - ($sum % 10)) % 10;

        self::assertEquals($checksum, (int)$imei[14], 'IMEI should have valid Luhn checksum');
    }

    public function testPhoneNumberGeneratesDifferentNumbers(): void
    {
        $numbers = [];
        for ($i = 0; $i < 20; $i++) {
            $numbers[] = $this->generator->phoneNumber();
        }

        $uniqueNumbers = array_unique($numbers);
        self::assertGreaterThan(1, count($uniqueNumbers), 'Should generate different phone numbers');
    }

    public function testE164PhoneNumberGeneratesDifferentNumbers(): void
    {
        $numbers = [];
        for ($i = 0; $i < 20; $i++) {
            $numbers[] = $this->generator->e164PhoneNumber();
        }

        $uniqueNumbers = array_unique($numbers);
        self::assertGreaterThan(1, count($uniqueNumbers), 'Should generate different E.164 phone numbers');
    }

    public function testImeiGeneratesDifferentNumbers(): void
    {
        $imeis = [];
        for ($i = 0; $i < 20; $i++) {
            $imeis[] = $this->generator->imei();
        }

        $uniqueImeis = array_unique($imeis);
        self::assertGreaterThan(1, count($uniqueImeis), 'Should generate different IMEI numbers');
    }
}
