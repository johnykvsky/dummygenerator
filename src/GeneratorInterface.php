<?php

declare(strict_types = 1);

namespace DummyGenerator;

use DummyGenerator\Definitions\DefinitionInterface;

interface GeneratorInterface
{
    public function parse(string $string): string;

    /** @param array<int, mixed> $arguments */
    public function __call(string $name, array $arguments): mixed;

    public function withDefinition(string $name, callable|DefinitionInterface|string $value): self;

    public function removeDefinition(string $id): self;

    public function resetExtensionCache(): void;
}
