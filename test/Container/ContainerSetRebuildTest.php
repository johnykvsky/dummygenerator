<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Container;

use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Test\Fixtures\FixedRandomizer;
use DummyGenerator\Test\Fixtures\InjectedRandomizerExtension;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

final class ContainerSetRebuildTest extends TestCase
{
    public function testSetRebuildsContainerAndUpdatesInjectedDependencies(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new FixedRandomizer(1));
        $container->set(InjectedRandomizerExtension::class, InjectedRandomizerExtension::class);

        $generator = new DummyGenerator($container);

        self::assertSame(1, $generator->luckyInt());

        $container->set(RandomizerInterface::class, new FixedRandomizer(7));

        self::assertSame(7, $generator->luckyInt());
    }
}
