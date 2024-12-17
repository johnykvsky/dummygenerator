<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Strategy\UniqueStrategy;
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
}