<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Test\Fixtures\InjectedRandomizerExtension;
use PHPUnit\Framework\TestCase;

class AttributeInjectionTest extends TestCase
{
    public function testInjectAttributeResolvesServiceId(): void
    {
        $container = DiContainerFactory::base();
        $container->set(InjectedRandomizerExtension::class, InjectedRandomizerExtension::class);

        $generator = new DummyGenerator($container);

        $value = $generator->luckyInt();

        self::assertIsInt($value);
        self::assertGreaterThanOrEqual(1, $value);
        self::assertLessThanOrEqual(10, $value);
    }
}
