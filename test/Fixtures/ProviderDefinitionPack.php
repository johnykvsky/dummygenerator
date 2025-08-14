<?php

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\ColorExtensionInterface;
use DummyGenerator\ProviderPack\ProviderPackInterface;

readonly class ProviderDefinitionPack implements ProviderPackInterface
{
    /** @var array<string, class-string<DefinitionInterface>> */
    private array $definitions;

    public function __construct()
    {
        $this->definitions = [
            ColorExtensionInterface::class => ProviderColor::class,
        ];
    }

    public function all(): array
    {
        return $this->definitions;
    }
}