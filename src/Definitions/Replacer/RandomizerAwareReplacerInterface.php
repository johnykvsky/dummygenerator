<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Replacer;

use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

interface RandomizerAwareReplacerInterface extends ReplacerInterface
{
    public function withRandomizer(RandomizerInterface $randomizer): ReplacerInterface;
}
