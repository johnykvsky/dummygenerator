<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

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

class CalculatorResolveTest extends TestCase
{
    public function testResolveCalculator(): void
    {
        $container = new DefinitionContainer([
            EanCalculatorInterface::class => EanCalculator::class,
        ]);

        $generator = new DummyGenerator($container);

        self::assertIsNumeric($generator->checksum('some_string'));
    }

    public function testResolveExtensionUsingCalculator(): void
    {
        $container = new DefinitionContainer([
            RandomizerInterface::class => Randomizer::class,
            TransliteratorInterface::class => Transliterator::class,
            ReplacerInterface::class => Replacer::class,
            EanCalculatorInterface::class => EanCalculator::class,
            IsbnCalculatorInterface::class => IsbnCalculator::class,
            BarcodeExtensionInterface::class => Barcode::class,
        ]);

        $generator = new DummyGenerator($container);

        self::assertNotEmpty($generator->ean13());
    }
}
