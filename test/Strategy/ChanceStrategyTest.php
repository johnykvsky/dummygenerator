<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Strategy;

use DummyGenerator\Strategy\ChanceStrategy;
use PHPUnit\Framework\TestCase;

class ChanceStrategyTest extends TestCase
{
    public function testChanceStrategyUseChance(): void
    {
        $strategy = new ChanceStrategy(0.5);

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

        self::assertTrue($count < 10);
    }

    public function testChanceStrategyWithInvalidWeight(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        new ChanceStrategy(1.3);
    }
}