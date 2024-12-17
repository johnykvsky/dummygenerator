<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
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

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(LuhnCalculatorInterface::class, LuhnCalculator::class);
        $container->add(PhoneNumberExtensionInterface::class, PhoneNumber::class);
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
}
