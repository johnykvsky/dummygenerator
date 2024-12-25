<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface IsbnCalculatorAwareExtensionInterface extends ExtensionInterface
{
    public function withIsbnCalculator(IsbnCalculatorInterface $calculator): ExtensionInterface;
}
