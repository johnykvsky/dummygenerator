<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Calculator\IbanCalculator;
use DummyGenerator\Core\Calculator\LuhnCalculator;
use DummyGenerator\Core\DateTime;
use DummyGenerator\Core\Payment;
use DummyGenerator\Core\Person;
use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;
use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\Extension\DateTimeExtensionInterface;
use DummyGenerator\Definitions\Extension\PaymentExtensionInterface;
use DummyGenerator\Definitions\Extension\PersonExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class PaymentTest extends TestCase
{
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, Randomizer::class);
        $container->add(TransliteratorInterface::class, Transliterator::class);
        $container->add(ReplacerInterface::class, Replacer::class);
        $container->add(IbanCalculatorInterface::class, IbanCalculator::class);
        $container->add(LuhnCalculatorInterface::class, LuhnCalculator::class);
        $container->add(DateTimeExtensionInterface::class, DateTime::class);
        $container->add(PersonExtensionInterface::class, Person::class);
        $container->add(PaymentExtensionInterface::class, Payment::class);
        $this->generator = new DummyGenerator($container);
    }

    public function testCreditCardNumber(): void
    {
        $ccNumber = $this->generator->creditCardNumber();
        self::assertNotEmpty($ccNumber);

        $ccNumber = $this->generator->creditCardNumber(type: null, formatted: true, separator: '.');
        self::assertCount(4, explode('.', $ccNumber));
    }

    public function testCurrencyCode(): void
    {
        self::assertNotEmpty($this->generator->currencyCode());
    }

    public function testCreditCardExpirationDate(): void
    {
        self::assertEquals(5, strlen($this->generator->creditCardExpirationDate()));
    }

    public function testCreditCardDetails(): void
    {
        self::assertCount(4, $this->generator->creditCardDetails(valid: false));
    }

    public function testIban(): void
    {
        $iban = $this->generator->iban(alpha2: 'PL', prefix: 'RR');

        self::assertTrue(str_contains($iban, 'RR'));
        self::assertTrue(strlen($iban) > 10);
    }

    public function testSwiftBicNumber(): void
    {
        self::assertEquals(11, strlen($this->generator->swiftBicNumber()));
    }
}
