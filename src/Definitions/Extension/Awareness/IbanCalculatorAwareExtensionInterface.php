<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface IbanCalculatorAwareExtensionInterface extends ExtensionInterface
{
    public function withIbanCalculator(IbanCalculatorInterface $calculator): ExtensionInterface;
}
