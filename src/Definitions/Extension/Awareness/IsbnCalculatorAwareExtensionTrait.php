<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Definitions\Calculator\IsbnCalculatorInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

/**
 * A helper trait to be used with IsbnCalculatorAwareExtension.
 */
trait IsbnCalculatorAwareExtensionTrait
{
    protected IsbnCalculatorInterface $isbnCalculator;

    public function withIsbnCalculator(IsbnCalculatorInterface $calculator): ExtensionInterface
    {
        $instance = clone $this;

        $instance->isbnCalculator = $calculator;

        return $instance;
    }
}
