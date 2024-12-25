<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\DummyGenerator;

/**
 * A helper trait to be used with GeneratorAwareExtension.
 */
trait GeneratorAwareExtensionTrait
{
    protected DummyGenerator $generator;

    public function withGenerator(DummyGenerator $generator): ExtensionInterface
    {
        $instance = clone $this;

        $instance->generator = $generator;

        return $instance;
    }
}
