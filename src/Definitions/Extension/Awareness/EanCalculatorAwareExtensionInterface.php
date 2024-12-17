<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface EanCalculatorAwareExtensionInterface extends ExtensionInterface
{
    public function withEanCalculator(EanCalculatorInterface $calculator): ExtensionInterface;
}
