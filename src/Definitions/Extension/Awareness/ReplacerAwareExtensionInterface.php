<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;

interface ReplacerAwareExtensionInterface extends ExtensionInterface
{
    public function withReplacer(ReplacerInterface $replacer): ExtensionInterface;
}
