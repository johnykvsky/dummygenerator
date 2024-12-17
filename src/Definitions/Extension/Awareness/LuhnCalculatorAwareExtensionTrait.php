<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\LuhnCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

/**
 * A helper trait to be used with LuhnCalculatorAwareExtension.
 */
trait LuhnCalculatorAwareExtensionTrait
{
    protected LuhnCalculatorInterface $luhnCalculator;

    public function withLuhnCalculator(LuhnCalculatorInterface $calculator): ExtensionInterface
    {
        $instance = clone $this;

        $instance->luhnCalculator = $calculator;

        return $instance;
    }
}
