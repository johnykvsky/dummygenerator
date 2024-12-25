<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

/**
 * A helper trait to be used with RandomizerAwareExtension.
 */
trait RandomizerAwareExtensionTrait
{
    protected RandomizerInterface $randomizer;

    public function withRandomizer(RandomizerInterface $randomizer): ExtensionInterface
    {
        $instance = clone $this;

        $instance->randomizer = $randomizer;

        return $instance;
    }
}
