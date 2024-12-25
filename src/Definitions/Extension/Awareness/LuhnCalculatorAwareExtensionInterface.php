<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface LuhnCalculatorAwareExtensionInterface extends ExtensionInterface
{
    public function withLuhnCalculator(LuhnCalculatorInterface $calculator): ExtensionInterface;
}
