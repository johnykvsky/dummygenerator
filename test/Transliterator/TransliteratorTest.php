<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Transliterator;

use DummyGenerator\Core\Transliterator\SimpleTransliterator;
use DummyGenerator\Core\Transliterator\Transliterator;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use PHPUnit\Framework\TestCase;

class TransliteratorTest extends TestCase
{
    public function testTransliterator(): void
    {
        $transliterator = new Transliterator();
        self::assertEquals('paczbecninio', $transliterator->transliterate('pącz`bęcń/inio'));
    }

    public function testSimpleTransliterator(): void
    {
        $transliterator = new SimpleTransliterator();
        self::assertEquals('paczbecninio', $transliterator->transliterate('pącz`bęcń/inio'));
    }

    public function testSimpleTransliteratorWithSimpleString(): void
    {
        $transliterator = new SimpleTransliterator();
        self::assertEquals('johny', $transliterator->transliterate('johny'));
    }

    public function testTransliteratorWithInvalidPattern(): void
    {
        $transliterator = new Transliterator();

        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Transliterator cannot be created for given pattern');

        $transliterator->transliterate('pącz`bęcń/inio', 'qwe');
    }

    // Test empty string handling
    public function testTransliteratorWithEmptyString(): void
    {
        $transliterator = new Transliterator();
        self::assertEquals('', $transliterator->transliterate(''));
    }

    public function testSimpleTransliteratorWithEmptyString(): void
    {
        $transliterator = new SimpleTransliterator();
        self::assertEquals('', $transliterator->transliterate(''));
    }

    // Test already ASCII strings (passthrough)
    public function testTransliteratorWithAsciiString(): void
    {
        $transliterator = new Transliterator();
        self::assertEquals('Hello123', $transliterator->transliterate('Hello123'));
    }

    public function testSimpleTransliteratorWithAsciiString(): void
    {
        $transliterator = new SimpleTransliterator();
        self::assertEquals('HelloWorld', $transliterator->transliterate('HelloWorld'));
    }

    // Test Cyrillic characters
    public function testSimpleTransliteratorWithCyrillic(): void
    {
        $transliterator = new SimpleTransliterator();
        // Привет = Privet (roughly)
        $result = $transliterator->transliterate('Привет');
        self::assertNotEmpty($result);
        // Check that some characters were transliterated
        self::assertMatchesRegularExpression('/[A-Z]/i', $result);
    }

    // Test Greek characters
    public function testSimpleTransliteratorWithGreek(): void
    {
        $transliterator = new SimpleTransliterator();
        // Αλφα = Alpha
        $result = $transliterator->transliterate('Αλφα');
        self::assertNotEmpty($result);
        self::assertMatchesRegularExpression('/^[A-Za-z]+$/', $result);
    }

    // Test German umlauts
    public function testSimpleTransliteratorWithGerman(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Müller');
        self::assertEquals('Muller', $result);
    }

    // Test French accents
    public function testSimpleTransliteratorWithFrench(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('François');
        self::assertEquals('Francois', $result);
    }

    // Test Spanish special characters
    public function testSimpleTransliteratorWithSpanish(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Señor');
        self::assertEquals('Senor', $result);
    }

    // Test Nordic characters
    public function testSimpleTransliteratorWithNordic(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Øyvind');
        self::assertEquals('Oyvind', $result);
    }

    // Test mixed characters
    public function testTransliteratorWithMixedCharacters(): void
    {
        $transliterator = new Transliterator();
        $result = $transliterator->transliterate('Café123Москва');
        // Should contain only ASCII letters, numbers, dots, underscores
        self::assertMatchesRegularExpression('/^[A-Za-z0-9_.]+$/', $result);
    }

    public function testSimpleTransliteratorWithMixedCharacters(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Café123Москва');
        // Should contain only ASCII letters, numbers, dots, underscores
        self::assertMatchesRegularExpression('/^[A-Za-z0-9_.]+$/', $result);
    }

    // Test special characters are removed
    public function testTransliteratorRemovesSpecialCharacters(): void
    {
        $transliterator = new Transliterator();
        $result = $transliterator->transliterate('hello!@#$%world');
        self::assertEquals('helloworld', $result);
    }

    public function testSimpleTransliteratorRemovesSpecialCharacters(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('hello!@#$%world');
        self::assertEquals('helloworld', $result);
    }

    // Test dots and underscores are preserved
    public function testTransliteratorPreservesDotsAndUnderscores(): void
    {
        $transliterator = new Transliterator();
        $result = $transliterator->transliterate('hello_world.test');
        self::assertEquals('hello_world.test', $result);
    }

    public function testSimpleTransliteratorPreservesDotsAndUnderscores(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('hello_world.test');
        self::assertEquals('hello_world.test', $result);
    }

    // Test Turkish characters
    public function testSimpleTransliteratorWithTurkish(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Şişli');
        self::assertEquals('Sisli', $result);
    }

    // Test Czech characters
    public function testSimpleTransliteratorWithCzech(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Dvořák');
        self::assertEquals('Dvorak', $result);
    }

    // Test numbers are preserved
    public function testTransliteratorPreservesNumbers(): void
    {
        $transliterator = new Transliterator();
        $result = $transliterator->transliterate('test123abc456');
        self::assertEquals('test123abc456', $result);
    }

    public function testSimpleTransliteratorPreservesNumbers(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('test123abc456');
        self::assertEquals('test123abc456', $result);
    }

    // Test whitespace is removed
    public function testTransliteratorRemovesWhitespace(): void
    {
        $transliterator = new Transliterator();
        $result = $transliterator->transliterate('hello world test');
        self::assertEquals('helloworldtest', $result);
    }

    public function testSimpleTransliteratorRemovesWhitespace(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('hello world test');
        self::assertEquals('helloworldtest', $result);
    }

    // Test string with only special characters
    public function testTransliteratorWithOnlySpecialCharacters(): void
    {
        $transliterator = new Transliterator();
        $result = $transliterator->transliterate('!@#$%^&*()');
        self::assertEquals('', $result);
    }

    public function testSimpleTransliteratorWithOnlySpecialCharacters(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('!@#$%^&*()');
        self::assertEquals('', $result);
    }

    // Test Vietnamese characters (with SimpleTransliterator)
    public function testSimpleTransliteratorWithVietnamese(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Việt');
        self::assertNotEmpty($result);
        // Should transliterate some characters
        self::assertMatchesRegularExpression('/[A-Za-z]/', $result);
    }

    // Test Armenian characters (with SimpleTransliterator)
    public function testSimpleTransliteratorWithArmenian(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('Հայերեն');
        self::assertNotEmpty($result);
        // Should transliterate to ASCII
        self::assertMatchesRegularExpression('/[A-Za-z]/', $result);
    }

    // Test Georgian characters (with SimpleTransliterator)
    public function testSimpleTransliteratorWithGeorgian(): void
    {
        $transliterator = new SimpleTransliterator();
        $result = $transliterator->transliterate('ქართული');
        self::assertNotEmpty($result);
        // Should transliterate to ASCII
        self::assertMatchesRegularExpression('/[A-Za-z]/', $result);
    }

    // Test long string with mixed content
    public function testTransliteratorWithLongMixedString(): void
    {
        $transliterator = new Transliterator();
        $input = 'Café_Москва.123-Dvořák!Şişli@Αλφα#test';
        $result = $transliterator->transliterate($input);

        // Should only contain alphanumeric, dots, and underscores
        self::assertMatchesRegularExpression('/^[A-Za-z0-9_.]+$/', $result);
        // Should not be empty
        self::assertNotEmpty($result);
    }

    public function testSimpleTransliteratorWithLongMixedString(): void
    {
        $transliterator = new SimpleTransliterator();
        $input = 'Café_Москва.123-Dvořák!Şişli@test';
        $result = $transliterator->transliterate($input);

        // Should only contain alphanumeric, dots, and underscores
        self::assertMatchesRegularExpression('/^[A-Za-z0-9_.]+$/', $result);
        // Should not be empty
        self::assertNotEmpty($result);
    }
}
