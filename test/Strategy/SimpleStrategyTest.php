<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Strategy\SimpleStrategy;
use PHPUnit\Framework\TestCase;

class SimpleStrategyTest extends TestCase
{
    public function testSimpleStrategy(): void
    {
        $strategy = new SimpleStrategy();

        $results = [];

        for ($i = 0; $i < 10; $i++) {
            $results[] = $strategy->generate('some_name', fn() => true);
        }

        $count = 0;
        foreach ($results as $result) {
            if ($result === true) {
                $count++;
            }
        }

        self::assertEquals(10, $count);
    }

    public function testSimpleStrategyProducesNoOutput(): void
    {
        $strategy = new SimpleStrategy();

        ob_start();
        $result = $strategy->generate('test', fn() => 'value');
        $output = ob_get_clean();

        self::assertEmpty($output, 'Strategy should not produce any output');
        self::assertEquals('value', $result);
    }

    public function testSimpleStrategyPassesThroughValue(): void
    {
        $strategy = new SimpleStrategy();

        $complexValue = ['key' => 'value', 'nested' => ['data' => 123]];
        $result = $strategy->generate('test', fn() => $complexValue);

        self::assertEquals($complexValue, $result);
    }

    public function testSimpleStrategyCallsCallbackEveryTime(): void
    {
        $strategy = new SimpleStrategy();
        $callCount = 0;

        for ($i = 0; $i < 5; $i++) {
            $strategy->generate('test', function () use (&$callCount) {
                $callCount++;
                return true;
            });
        }

        self::assertEquals(5, $callCount, 'Callback should be called every time');
    }
}