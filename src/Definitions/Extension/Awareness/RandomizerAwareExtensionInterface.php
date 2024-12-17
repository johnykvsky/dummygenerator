<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

interface RandomizerAwareExtensionInterface extends ExtensionInterface
{
    public function withRandomizer(RandomizerInterface $randomizer): ExtensionInterface;
}
