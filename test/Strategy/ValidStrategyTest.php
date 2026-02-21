<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Strategy\ValidStrategy;
use DummyGenerator\Strategy\ShortCircuitResult;
use PHPUnit\Framework\TestCase;

class ValidStrategyTest extends TestCase
{
    public function testValidStrategyIsValid(): void
    {
        $strategy = new ValidStrategy(fn($x) => $x <= 50);

        $results = [];

        for ($i = 0; $i < 10; $i++) {
            $results[] = $strategy->generate('some_name', fn() => random_int(1, 1000));
        }

        $count = 0;
        foreach ($results as $result) {
            if ($result > 50) {
                $count++;
            }
        }

        self::assertEquals(0, $count);
    }

    public function testValidStrategyHitRetriesLimit(): void
    {
        $this->expectException(\OverflowException::class);

        $strategy = new ValidStrategy(fn($x) => $x <= 50, 3);

        for ($i = 0; $i < 10; $i++) {
            $strategy->generate('some_name', fn() => random_int(1, 1000));
        }
    }

    public function testValidStrategyShortCircuits(): void
    {
        $strategy = new ValidStrategy(fn($x) => false, 1);

        $result = $strategy->generate('some_name', fn() => new ShortCircuitResult('stop'));

        self::assertInstanceOf(ShortCircuitResult::class, $result);
        self::assertSame('stop', $result->value);
    }
}
