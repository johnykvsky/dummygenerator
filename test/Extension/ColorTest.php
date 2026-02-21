<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Color;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class ColorTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(ColorExtensionInterface::class, Color::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testHexColor(): void
    {
        $hexColor = $this->generator->hexColor();

        self::assertEquals(7, strlen($hexColor));
        self::assertStringStartsWith('#', $hexColor);
    }

    public function testsafeHexColor(): void
    {
        $hexColor = $this->generator->safeHexColor();

        self::assertEquals(7, strlen($hexColor));
        self::assertStringStartsWith('#', $hexColor);
        self::assertEquals($hexColor[1], $hexColor[2]);
        self::assertEquals($hexColor[3], $hexColor[4]);
        self::assertEquals($hexColor[5], $hexColor[6]);
    }

    public function testRgbColorAsArray(): void
    {
        self::assertCount(3, $this->generator->rgbColorAsArray());
    }

    public function testRgbColor(): void
    {
        self::assertCount(3, explode(',', $this->generator->rgbColor()));
    }

    public function testRgbCssColor(): void
    {
        $rgbColor = $this->generator->rgbCssColor();

        self::assertStringStartsWith('rgb(', $rgbColor);
        self::assertStringEndsWith(')', $rgbColor);
        self::assertCount(3, explode(',', $rgbColor));
    }

    public function testRgbaCssColor(): void
    {
        $rgbColor = $this->generator->rgbaCssColor();

        self::assertStringStartsWith('rgba(', $rgbColor);
        self::assertStringEndsWith(')', $rgbColor);
        self::assertCount(4, explode(',', $rgbColor));
    }

    public function testSafeColorName(): void
    {
        self::assertNotEmpty($this->generator->safeColorName());
    }

    public function testColorName(): void
    {
        self::assertNotEmpty($this->generator->colorName());
    }

    public function testHslColor(): void
    {
        self::assertCount(3, explode(',', $this->generator->hslColor()));
    }

    public function testHslColorAsArray(): void
    {
        self::assertCount(3, $this->generator->hslColorAsArray());
    }

    // Enhanced validation tests

    public function testHexColorContainsOnlyValidHexCharacters(): void
    {
        $hexColor = $this->generator->hexColor();

        // Remove the # and check if remaining chars are hex
        $hexPart = substr($hexColor, 1);
        self::assertMatchesRegularExpression('/^[0-9a-f]{6}$/i', $hexPart);
    }

    public function testRgbColorRanges(): void
    {
        $rgbArray = $this->generator->rgbColorAsArray();

        // Each value should be 0-255
        foreach ($rgbArray as $value) {
            self::assertTrue($value >= 0 && $value <= 255, "RGB value $value should be 0-255");
        }
    }

    public function testRgbCssColorFormat(): void
    {
        $rgbCss = $this->generator->rgbCssColor();

        // Should match rgb(n, n, n) format
        self::assertMatchesRegularExpression('/^rgb\(\d{1,3},\s?\d{1,3},\s?\d{1,3}\)$/', $rgbCss);

        // Extract values and verify range
        preg_match('/rgb\((\d+),\s?(\d+),\s?(\d+)\)/', $rgbCss, $matches);
        for ($i = 1; $i <= 3; $i++) {
            $value = (int) $matches[$i];
            self::assertTrue($value >= 0 && $value <= 255);
        }
    }

    public function testRgbaCssColorFormat(): void
    {
        $rgbaCss = $this->generator->rgbaCssColor();

        // Should match rgba(n, n, n, n.n) format
        self::assertMatchesRegularExpression('/^rgba\(\d{1,3},\s?\d{1,3},\s?\d{1,3},\s?[\d.]+\)$/', $rgbaCss);

        // Extract and verify alpha value
        preg_match('/rgba\(\d+,\s?\d+,\s?\d+,\s?([\d.]+)\)/', $rgbaCss, $matches);
        $alpha = (float) $matches[1];
        self::assertTrue($alpha >= 0.0 && $alpha <= 1.0, "Alpha value $alpha should be 0.0-1.0");
    }

    public function testHslColorRanges(): void
    {
        $hslArray = $this->generator->hslColorAsArray();

        // H: 0-360, S: 0-100, L: 0-100
        self::assertTrue($hslArray[0] >= 0 && $hslArray[0] <= 360, "Hue should be 0-360");
        self::assertTrue($hslArray[1] >= 0 && $hslArray[1] <= 100, "Saturation should be 0-100");
        self::assertTrue($hslArray[2] >= 0 && $hslArray[2] <= 100, "Lightness should be 0-100");
    }

    public function testHslColorFormat(): void
    {
        $hsl = $this->generator->hslColor();

        // Should be in format "H, S, L"
        $parts = explode(',', $hsl);
        self::assertCount(3, $parts);

        $h = (int) trim($parts[0]);
        $s = (int) trim($parts[1]);
        $l = (int) trim($parts[2]);

        self::assertTrue($h >= 0 && $h <= 360);
        self::assertTrue($s >= 0 && $s <= 100);
        self::assertTrue($l >= 0 && $l <= 100);
    }

    public function testSafeHexColorFormat(): void
    {
        $safeHex = $this->generator->safeHexColor();

        // Safe hex colors have repeated characters (e.g., #aabbcc)
        self::assertEquals(7, strlen($safeHex));
        self::assertStringStartsWith('#', $safeHex);

        // Check pattern: each pair should be the same character repeated
        self::assertEquals($safeHex[1], $safeHex[2]);
        self::assertEquals($safeHex[3], $safeHex[4]);
        self::assertEquals($safeHex[5], $safeHex[6]);

        // Should be valid hex
        $hexPart = substr($safeHex, 1);
        self::assertMatchesRegularExpression('/^[0-9a-f]{6}$/i', $hexPart);
    }

    public function testColorNameIsString(): void
    {
        $colorName = $this->generator->colorName();

        self::assertIsString($colorName);
        self::assertNotEmpty($colorName);
        self::assertGreaterThan(2, strlen($colorName));
    }

    public function testSafeColorNameIsString(): void
    {
        $colorName = $this->generator->safeColorName();

        self::assertIsString($colorName);
        self::assertNotEmpty($colorName);
        self::assertGreaterThan(2, strlen($colorName));
    }

    public function testRgbColorStringFormat(): void
    {
        $rgb = $this->generator->rgbColor();

        // Should be "R,G,B" format
        $parts = explode(',', $rgb);
        self::assertCount(3, $parts);

        foreach ($parts as $part) {
            $value = (int) trim($part);
            self::assertTrue($value >= 0 && $value <= 255);
        }
    }

    public function testMultipleHexColorsAreDifferent(): void
    {
        $colors = [];
        for ($i = 0; $i < 20; $i++) {
            $colors[] = $this->generator->hexColor();
        }

        // Should have variety
        $uniqueColors = array_unique($colors);
        self::assertGreaterThan(1, count($uniqueColors), 'Should generate different colors');
    }

    public function testRgbaCssAlphaValueVariety(): void
    {
        $alphas = [];

        for ($i = 0; $i < 20; $i++) {
            $rgbaCss = $this->generator->rgbaCssColor();
            preg_match('/rgba\(\d+,\s?\d+,\s?\d+,\s?([\d.]+)\)/', $rgbaCss, $matches);
            $alphas[] = (float) $matches[1];
        }

        // Should have variety in alpha values
        $uniqueAlphas = array_unique($alphas);
        self::assertGreaterThan(1, count($uniqueAlphas), 'Should generate different alpha values');
    }
}
