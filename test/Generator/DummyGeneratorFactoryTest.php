<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Generator;

use DummyGenerator\Core\Color;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\DummyGeneratorFactory;
use DummyGenerator\Test\Fixtures\ProviderColor;
use DummyGenerator\Test\Fixtures\ProviderDefinitionPack;
use PHPUnit\Framework\TestCase;

class DummyGeneratorFactoryTest extends TestCase
{
    public function testCanGetExtensionFromGenerator(): void
    {
        $generator = DummyGeneratorFactory::create();

        self::assertInstanceOf(Color::class, $generator->ext(ColorExtensionInterface::class));
        self::assertInstanceOf(ProviderColor::class, $generator->withProvider(new ProviderDefinitionPack())->ext(ColorExtensionInterface::class));
    }
}
