<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DI\Attribute\Inject;
use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

final class InjectedRandomizerExtension implements ExtensionInterface
{
    public function __construct(
        #[Inject(RandomizerInterface::class)]
        private RandomizerInterface $randomizer
    ) {}

    public function luckyInt(): int
    {
        return $this->randomizer->getInt(1, 10);
    }
}
