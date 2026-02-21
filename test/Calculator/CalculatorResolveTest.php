<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Calculator;

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
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

class CalculatorResolveTest extends TestCase
{
    public function testResolveCalculator(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(EanCalculatorInterface::class, EanCalculator::class);

        $generator = new DummyGenerator($container);

        self::assertIsNumeric($generator->checksum('some_string'));
    }

    public function testResolveExtensionUsingCalculator(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(EanCalculatorInterface::class, EanCalculator::class);
        $container->set(IsbnCalculatorInterface::class, IsbnCalculator::class);
        $container->set(BarcodeExtensionInterface::class, Barcode::class);

        $generator = new DummyGenerator($container);

        self::assertNotEmpty($generator->ean13());
    }
}
