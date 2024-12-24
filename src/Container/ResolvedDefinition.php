<?php

declare(strict_types=1);

namespace DummyGenerator\Container;

use DummyGenerator\Definitions\DefinitionInterface;

readonly class ResolvedDefinition
{
    public function __construct(
        public string $method,
        public string $definitionId,
        public DefinitionInterface $service
    ) {
    }

    public function withService(DefinitionInterface $definition): self
    {
        return new self($this->method, $this->definitionId, $definition);
    }
}
