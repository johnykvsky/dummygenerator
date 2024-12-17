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
}