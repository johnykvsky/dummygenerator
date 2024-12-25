<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\Definitions\Replacer\ReplacerInterface;

/**
 * A helper trait to be used with ReplacerAwareExtension.
 */
trait ReplacerAwareExtensionTrait
{
    protected ReplacerInterface $replacer;

    public function withReplacer(ReplacerInterface $replacer): ExtensionInterface
    {
        $instance = clone $this;

        $instance->replacer = $replacer;

        return $instance;
    }
}
