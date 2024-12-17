<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Extension\ExtensionInterface;
use DummyGenerator\DummyGenerator;

interface GeneratorAwareExtensionInterface extends ExtensionInterface
{
    public function withGenerator(DummyGenerator $generator): ExtensionInterface;
}
