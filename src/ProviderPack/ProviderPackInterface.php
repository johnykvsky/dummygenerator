<?php

declare(strict_types = 1);

namespace DummyGenerator\ProviderPack;

use DummyGenerator\Definitions\DefinitionInterface;

interface ProviderPackInterface
{
    /** @return array<string, class-string<DefinitionInterface>> */
    public function all(): array;
}
