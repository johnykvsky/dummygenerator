<?php

declare(strict_types=1);

namespace DummyGenerator\DefinitionPack;

use DummyGenerator\Definitions\Calculator\CalculatorInterface;
use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface DefinitionPackInterface
{
    /**
     * @return array<string, class-string<ExtensionInterface>>
     */
    public function baseExtensions(): array;
    /**
     * @return array<string, class-string<ExtensionInterface>>
     */
    public function defaultExtensions(): array;
    /**
     * @return array<string, class-string<ExtensionInterface>>
     */
    public function complementaryExtensions(): array;
    /**
     * @return array<string, class-string<CalculatorInterface>>
     */
    public function calculators(): array;
    /**
     * @return array<string, class-string<DefinitionInterface>>
     */
    public function coreDefinitions(): array;
}
