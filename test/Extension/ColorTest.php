<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
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

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(ColorExtensionInterface::class, Color::class);
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
}
