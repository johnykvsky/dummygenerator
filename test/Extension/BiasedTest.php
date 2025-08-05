<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Extension;

use DummyGenerator\Container\DefinitionContainer;
use DummyGenerator\Core\Biased;
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Definitions\Extension\BiasedExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use PHPUnit\Framework\TestCase;

class BiasedTest extends TestCase
{
    private const int MAX = 10;
    private const int NUMBERS = 25000;

    /** @var array<int, float> */
    protected array $results = [];
    private DummyGenerator $generator;

    public function setUp(): void
    {
        parent::setUp();

        $container = new DefinitionContainer([]);
        $container->add(RandomizerInterface::class, new XoshiroRandomizer(seed: 1));
        $container->add(BiasedExtensionInterface::class, Biased::class);
        $this->generator = new DummyGenerator($container);
        $this->results = array_fill(1, self::MAX, 0);
    }

    public function testBiasedNumberBetween(): void
    {
        $number = $this->generator->biasedNumberBetween(min: 10, max: 90);

        self::assertTrue($number >= 10 && $number <= 90);
    }

    public function testUnbiased(): void
    {
        /** @var Biased $biasedExtension */
        $biasedExtension = $this->generator->ext(BiasedExtensionInterface::class);
        $this->perform(fn() => $biasedExtension->unbiased());

        // assert that all numbers are near the expected unbiased value
        foreach ($this->results as $number => $amount) {
            // integral
            $assumed = (1 / self::MAX * $number) - (1 / self::MAX * ($number - 1));
            // calculate the fraction of the whole area
            $assumed /= 1;
            self::assertGreaterThan(self::NUMBERS * $assumed * .95, $amount, 'Value was more than 5 percent under the expected value');
            self::assertLessThan(self::NUMBERS * $assumed * 1.05, $amount, 'Value was more than 5 percent over the expected value');
        }
    }

    public function testLinearHigh(): void
    {
        /** @var Biased $biasedExtension */
        $biasedExtension = $this->generator->ext(BiasedExtensionInterface::class);
        $this->perform(fn(float $x): float => $biasedExtension->linearHigh($x));

        foreach ($this->results as $number => $amount) {
            // integral
            $assumed = 0.5 * (1 / self::MAX * $number) ** 2 - 0.5 * (1 / self::MAX * ($number - 1)) ** 2;
            // calculate the fraction of the whole area
            $assumed /= (1 ** 2) * 0.5;

            self::assertGreaterThan((int) (self::NUMBERS * $assumed * 0.95), $amount, 'Value was more than 5 percent under the expected value');
            self::assertLessThan((int) (self::NUMBERS * $assumed * 1.05), $amount, 'Value was more than 5 percent over the expected value');
        }
    }

    public function testLinearLow(): void
    {
        /** @var Biased $biasedExtension */
        $biasedExtension = $this->generator->ext(BiasedExtensionInterface::class);
        $this->perform(fn(float $x): float => $biasedExtension->linearLow($x));

        foreach ($this->results as $number => $amount) {
            // integral
            $assumed = -0.5 * (1 / self::MAX * $number) ** 2 - -0.5 * (1 / self::MAX * ($number - 1)) ** 2;
            // shift the graph up
            $assumed += 1 / self::MAX;
            // calculate the fraction of the whole area
            $assumed /= 1 ** 2 * .5;
            self::assertGreaterThan((int) (self::NUMBERS * $assumed * .9), $amount, 'Value was more than 10 percent under the expected value');
            self::assertLessThan((int) (self::NUMBERS * $assumed * 1.1), $amount, 'Value was more than 10 percent over the expected value');
        }
    }

    public function testBiasedForNonCallalbe()
    {
        self::expectException(ExtensionArgumentException::class);
        self::expectExceptionMessage('Given $function must be a callable');
        $this->generator->biasedNumberBetween(1, self::MAX, 'something_not_callable');

    }

    private function perform(callable|string $function): void
    {
        for ($i = 0; $i < self::NUMBERS; ++$i) {
            ++$this->results[$this->generator->biasedNumberBetween(1, self::MAX, $function)];
        }
    }
}
