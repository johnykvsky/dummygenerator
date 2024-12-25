<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\EanCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

/**
 * A helper trait to be used with EanCalculatorAwareExtension.
 */
trait EanCalculatorAwareExtensionTrait
{
    protected EanCalculatorInterface $eanCalculator;

    public function withEanCalculator(EanCalculatorInterface $calculator): ExtensionInterface
    {
        $instance = clone $this;

        $instance->eanCalculator = $calculator;

        return $instance;
    }
}
