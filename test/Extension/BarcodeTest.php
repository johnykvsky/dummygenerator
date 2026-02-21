<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Extension\BarcodeExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Barcode;
use DummyGenerator\Core\Calculator\EanCalculator;
use DummyGenerator\Core\Calculator\IsbnCalculator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class BarcodeTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(EanCalculatorInterface::class, EanCalculator::class);
        $container->set(IsbnCalculatorInterface::class, IsbnCalculator::class);
        $container->set(BarcodeExtensionInterface::class, Barcode::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testEan8(): void
    {
        self::assertEquals(8, strlen($this->generator->ean8()));
    }

    public function testEan13(): void
    {
        self::assertEquals(13, strlen($this->generator->ean13()));
    }

    public function testIsbn10(): void
    {
        self::assertEquals(10, strlen($this->generator->isbn10()));
    }

    public function testIsbn13(): void
    {
        self::assertEquals(13, strlen($this->generator->isbn13()));
    }

    // Enhanced validation tests

    public function testEan8ContainsOnlyDigits(): void
    {
        $ean8 = $this->generator->ean8();
        self::assertMatchesRegularExpression('/^\d{8}$/', $ean8);
    }

    public function testEan13ContainsOnlyDigits(): void
    {
        $ean13 = $this->generator->ean13();
        self::assertMatchesRegularExpression('/^\d{13}$/', $ean13);
    }

    public function testIsbn10Format(): void
    {
        $isbn10 = $this->generator->isbn10();

        // ISBN-10 can have 9 digits + checksum (which can be X)
        self::assertMatchesRegularExpression('/^\d{9}[\dX]$/', $isbn10);
    }

    public function testIsbn13ContainsOnlyDigits(): void
    {
        $isbn13 = $this->generator->isbn13();
        self::assertMatchesRegularExpression('/^\d{13}$/', $isbn13);
    }

    public function testEan8GeneratesDifferentCodes(): void
    {
        $codes = [];
        for ($i = 0; $i < 20; $i++) {
            $codes[] = $this->generator->ean8();
        }

        $uniqueCodes = array_unique($codes);
        self::assertGreaterThan(1, count($uniqueCodes), 'Should generate different EAN-8 codes');
    }

    public function testEan13GeneratesDifferentCodes(): void
    {
        $codes = [];
        for ($i = 0; $i < 20; $i++) {
            $codes[] = $this->generator->ean13();
        }

        $uniqueCodes = array_unique($codes);
        self::assertGreaterThan(1, count($uniqueCodes), 'Should generate different EAN-13 codes');
    }

    public function testIsbn10GeneratesDifferentCodes(): void
    {
        $codes = [];
        for ($i = 0; $i < 20; $i++) {
            $codes[] = $this->generator->isbn10();
        }

        $uniqueCodes = array_unique($codes);
        self::assertGreaterThan(1, count($uniqueCodes), 'Should generate different ISBN-10 codes');
    }

    public function testIsbn13GeneratesDifferentCodes(): void
    {
        $codes = [];
        for ($i = 0; $i < 20; $i++) {
            $codes[] = $this->generator->isbn13();
        }

        $uniqueCodes = array_unique($codes);
        self::assertGreaterThan(1, count($uniqueCodes), 'Should generate different ISBN-13 codes');
    }

    public function testEan8HasValidChecksum(): void
    {
        $ean8 = $this->generator->ean8();

        // Calculate checksum for EAN-8
        $sum = 0;
        for ($i = 0; $i < 7; $i++) {
            $weight = ($i % 2 === 0) ? 3 : 1;
            $sum += (int)$ean8[$i] * $weight;
        }
        $checksum = (10 - ($sum % 10)) % 10;

        self::assertEquals($checksum, (int)$ean8[7], 'EAN-8 should have valid checksum');
    }

    public function testEan13HasValidChecksum(): void
    {
        $ean13 = $this->generator->ean13();

        // Calculate checksum for EAN-13
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $weight = ($i % 2 === 0) ? 1 : 3;
            $sum += (int)$ean13[$i] * $weight;
        }
        $checksum = (10 - ($sum % 10)) % 10;

        self::assertEquals($checksum, (int)$ean13[12], 'EAN-13 should have valid checksum');
    }

    public function testIsbn13StartsWithCorrectPrefix(): void
    {
        $isbn13 = $this->generator->isbn13();

        // ISBN-13 should start with 978 or 979
        $prefix = substr($isbn13, 0, 3);
        self::assertContains($prefix, ['978', '979'], 'ISBN-13 should start with 978 or 979');
    }

    public function testBarcodeConsistency(): void
    {
        // Test that the same method always returns the correct length
        for ($i = 0; $i < 10; $i++) {
            self::assertEquals(8, strlen($this->generator->ean8()));
            self::assertEquals(13, strlen($this->generator->ean13()));
            self::assertEquals(10, strlen($this->generator->isbn10()));
            self::assertEquals(13, strlen($this->generator->isbn13()));
        }
    }
}