<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Replacer;

use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

/**
 * A helper trait to be used with RandomizerAwareReplacer.
 */
trait RandomizerAwareReplacerTrait
{
    protected RandomizerInterface $randomizer;

    public function withRandomizer(RandomizerInterface $randomizer): ReplacerInterface
    {
        $instance = clone $this;

        $instance->randomizer = $randomizer;

        return $instance;
    }
}
