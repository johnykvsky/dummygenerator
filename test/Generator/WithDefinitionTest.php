<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Generator;

use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Test\Fixtures\FixedRandomizer;
use DummyGenerator\Test\Fixtures\InjectedRandomizerExtension;
use DummyGenerator\Test\Fixtures\TestContainerFactory;
use PHPUnit\Framework\TestCase;

final class WithDefinitionTest extends TestCase
{
    public function testWithDefinitionReplacesRandomizer(): void
    {
        $container = TestContainerFactory::empty();
        $container->set(RandomizerInterface::class, new FixedRandomizer(2));
        $container->set(InjectedRandomizerExtension::class, InjectedRandomizerExtension::class);

        $generator = new DummyGenerator($container);

        self::assertSame(2, $generator->luckyInt());

        $updated = $generator->withDefinition(RandomizerInterface::class, new FixedRandomizer(9));

        self::assertSame(2, $generator->luckyInt());
        self::assertSame(9, $updated->luckyInt());
    }
}
