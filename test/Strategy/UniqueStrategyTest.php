<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Strategy\UniqueStrategy;
use DummyGenerator\Strategy\ShortCircuitResult;
use PHPUnit\Framework\TestCase;

class UniqueStrategyTest extends TestCase
{
    public function testUniqueStrategyIsUnique(): void
    {
        $strategy = new UniqueStrategy(1000);

        $results = [];

        for ($i = 0; $i < 10; $i++) {
            $results[] = $strategy->generate('some_name', fn() => random_int(1, 10));
        }

        self::assertCount(10, array_unique($results));
    }

    public function testUniqueStrategyHitRetriesLimit(): void
    {
        $this->expectException(\OverflowException::class);
        $strategy = new UniqueStrategy(3);

        for ($i = 0; $i < 100; $i++) {
            $strategy->generate('some_name', fn() => random_int(1, 10));
        }
    }

    public function testUniqueStrategyProducesNoOutput(): void
    {
        $strategy = new UniqueStrategy(100);

        ob_start();
        for ($i = 0; $i < 5; $i++) {
            $strategy->generate('test', fn() => $i);
        }
        $output = ob_get_clean();

        self::assertEmpty($output, 'Strategy should not produce any output');
    }

    public function testUniqueStrategyWorksWithStrings(): void
    {
        $strategy = new UniqueStrategy(100);
        $results = [];

        $words = ['apple', 'banana', 'cherry', 'date', 'elderberry'];
        foreach ($words as $word) {
            $results[] = $strategy->generate('test', fn() => $word);
        }

        self::assertCount(5, array_unique($results));
        self::assertEquals($words, $results);
    }

    public function testUniqueStrategyWorksWithArrays(): void
    {
        $strategy = new UniqueStrategy(100);
        $results = [];

        $arrays = [
            ['id' => 1],
            ['id' => 2],
            ['id' => 3],
        ];

        foreach ($arrays as $arr) {
            $results[] = $strategy->generate('test', fn() => $arr);
        }

        self::assertCount(3, $results);
        self::assertEquals($arrays, $results);
    }

    public function testUniqueStrategyErrorMessage(): void
    {
        $strategy = new UniqueStrategy(5);

        $this->expectException(\OverflowException::class);
        $this->expectExceptionMessage('Maximum retries of 5 reached without finding a unique value');

        for ($i = 0; $i < 100; $i++) {
            $strategy->generate('test', fn() => 'same');
        }
    }

    public function testUniqueStrategyShortCircuits(): void
    {
        $strategy = new UniqueStrategy(1);

        $result = $strategy->generate('test', fn() => new ShortCircuitResult('stop'));

        self::assertInstanceOf(ShortCircuitResult::class, $result);
        self::assertSame('stop', $result->value);
    }

    public function testUniqueStrategyTracksUniquenessPerName(): void
    {
        $strategy = new UniqueStrategy(10);

        $first = $strategy->generate('alpha', fn() => 'same');
        $second = $strategy->generate('beta', fn() => 'same');

        self::assertSame('same', $first);
        self::assertSame('same', $second);
    }
}
