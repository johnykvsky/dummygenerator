<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
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

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(EanCalculatorInterface::class, EanCalculator::class);
        $container->add(IsbnCalculatorInterface::class, IsbnCalculator::class);
        $container->add(BarcodeExtensionInterface::class, Barcode::class);
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
}