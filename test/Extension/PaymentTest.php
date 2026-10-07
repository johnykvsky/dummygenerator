<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Test\Fixtures\TestContainerFactory;
use DummyGenerator\Core\Calculator\IbanCalculator;
use DummyGenerator\Core\Calculator\LuhnCalculator;
use DummyGenerator\Core\DateTime;
use DummyGenerator\Core\Payment;
use DummyGenerator\Core\Person;
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
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

        $container = TestContainerFactory::empty();

        $container->set(RandomizerInterface::class, Randomizer::class);
        $container->set(TransliteratorInterface::class, Transliterator::class);
        $container->set(ReplacerInterface::class, Replacer::class);
        $container->set(IbanCalculatorInterface::class, IbanCalculator::class);
        $container->set(LuhnCalculatorInterface::class, LuhnCalculator::class);
        $container->set(DateTimeExtensionInterface::class, DateTime::class);
        $container->set(PersonExtensionInterface::class, Person::class);
        $container->set(PaymentExtensionInterface::class, Payment::class);

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
        $details = $this->generator->creditCardDetails(valid: false);
        self::assertCount(5, $details);
        self::assertArrayHasKey('type', $details);
        self::assertArrayHasKey('number', $details);
        self::assertArrayHasKey('name', $details);
        self::assertArrayHasKey('expirationDate', $details);
        self::assertArrayHasKey('cvv', $details);
        self::assertMatchesRegularExpression('/^[0-9]{3,4}$/', $details['cvv']);
    }

    public function testCreditCardCvvDefault(): void
    {
        $cvv = $this->generator->creditCardCvv();
        self::assertSame(3, strlen($cvv));
        self::assertMatchesRegularExpression('/^[0-9]{3}$/', $cvv);
    }

    public function testCreditCardCvvAmericanExpress(): void
    {
        $cvv = $this->generator->creditCardCvv('American Express');
        self::assertSame(4, strlen($cvv));
        self::assertMatchesRegularExpression('/^[0-9]{4}$/', $cvv);
    }

    public function testCurrencySymbol(): void
    {
        $symbol = $this->generator->currencySymbol();
        self::assertNotEmpty($symbol);
        self::assertIsString($symbol);
    }

    public function testCurrencyName(): void
    {
        $name = $this->generator->currencyName();
        self::assertNotEmpty($name);
        self::assertIsString($name);
    }

    public function testPrice(): void
    {
        for ($i = 0; $i < 20; $i++) {
            $p = $this->generator->price(10.0, 50.0, 2);
            self::assertGreaterThanOrEqual(10.0, $p);
            self::assertLessThanOrEqual(50.0, $p);
        }
    }

    public function testPriceInvalidArgumentsThrow(): void
    {
        $this->expectException(\DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException::class);
        $this->generator->price(100.0, 50.0);
    }


    public function testIbanN(): void
    {
        $iban = $this->generator->iban(alpha2: 'PL', prefix: 'RR');

        self::assertTrue(str_contains($iban, 'RR'));
        self::assertTrue(strlen($iban) > 10);
    }

    public function testSwiftBicNumber(): void
    {
        self::assertEquals(11, strlen($this->generator->swiftBicNumber()));
    }

    public function testIbanC(): void
    {
        $iban = $this->generator->iban(alpha2: 'MD', prefix: 'RR');

        self::assertTrue(str_contains($iban, 'RR'));
        self::assertTrue(strlen($iban) > 10);
    }

    public function testIbanA(): void
    {
        $generator = $this->generator->withDefinition(RandomizerInterface::class, new XoshiroRandomizer(seed: 8));
        $iban = $generator->iban(alpha2: 'AZ', prefix: 'RR');

        self::assertTrue(str_contains($iban, 'RR'));
        self::assertTrue(strlen($iban) > 10);
    }

    public function testDefaultFormat(): void
    {
        $iban = $this->generator->iban(alpha2: 'XX', prefix: 'RR');

        self::assertTrue(str_contains($iban, 'RR'));
        self::assertTrue(strlen($iban) > 24);
    }
}
