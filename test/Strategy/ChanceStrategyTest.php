<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Strategy\ChanceStrategy;
use PHPUnit\Framework\TestCase;

class ChanceStrategyTest extends TestCase
{
    public function testChanceStrategyAlwaysGeneratesWhenWeightIsOne(): void
    {
        $strategy = new ChanceStrategy(1.0);

        for ($i = 0; $i < 10; $i++) {
            $called = false;
            $result = $strategy->generate('some_name', function () use (&$called): string {
                $called = true;
                return 'generated';
            });

            self::assertTrue($called);
            self::assertSame('generated', $result);
        }
    }

    public function testChanceStrategyAlwaysReturnsDefaultWhenWeightIsZero(): void
    {
        $strategy = new ChanceStrategy(0.0, default: 'fallback');

        for ($i = 0; $i < 10; $i++) {
            $called = false;
            $result = $strategy->generate('some_name', function () use (&$called): string {
                $called = true;
                return 'generated';
            });

            self::assertFalse($called);
            self::assertSame('fallback', $result);
        }
    }

    public function testChanceStrategyReturnsCallbackWhenRandomizerAtOrBelowThreshold(): void
    {
        $randomizer = $this->createStub(RandomizerInterface::class);
        $randomizer->method('getInt')->willReturn(50);

        $strategy = new ChanceStrategy(0.5, $randomizer, 'fallback');
        $called = false;
        $result = $strategy->generate('some_name', function () use (&$called): string {
            $called = true;
            return 'generated';
        });

        self::assertTrue($called);
        self::assertSame('generated', $result);
    }

    public function testChanceStrategyReturnsDefaultWhenRandomizerAboveThreshold(): void
    {
        $randomizer = $this->createStub(RandomizerInterface::class);
        $randomizer->method('getInt')->willReturn(51);

        $strategy = new ChanceStrategy(0.5, $randomizer, 'fallback');
        $called = false;
        $result = $strategy->generate('some_name', function () use (&$called): string {
            $called = true;
            return 'generated';
        });

        self::assertFalse($called);
        self::assertSame('fallback', $result);
    }

    public function testChanceStrategyWithInvalidWeight(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ChanceStrategy(1.3);
    }

    public function testChanceStrategyWithNegativeWeight(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new ChanceStrategy(-0.1);
    }
}