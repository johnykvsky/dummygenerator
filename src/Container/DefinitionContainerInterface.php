<?php

declare(strict_types=1);

namespace DummyGenerator\Container;

use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface DefinitionContainerInterface
{
    public function get(string $id): DefinitionInterface;
    public function has(string $id): bool;
    public function add(string $name, callable|ExtensionInterface|string $value): void;
    public function findProcessor(string $name): null|DefinitionInterface;
}
