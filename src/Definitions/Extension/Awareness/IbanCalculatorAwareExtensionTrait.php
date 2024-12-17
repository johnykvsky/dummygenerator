<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\IbanCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

/**
 * A helper trait to be used with IbanCalculatorAwareExtension.
 */
trait IbanCalculatorAwareExtensionTrait
{
    protected IbanCalculatorInterface $ibanCalculator;

    public function withIbanCalculator(IbanCalculatorInterface $calculator): ExtensionInterface
    {
        $instance = clone $this;

        $instance->ibanCalculator = $calculator;

        return $instance;
    }
}
