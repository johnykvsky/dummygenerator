<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

/**
 * A helper trait to be used with ClockAwareExtension.
 */
trait ClockAwareExtensionTrait
{
    protected SystemClockInterface $clock;

    public function withClock(SystemClockInterface $clock): ExtensionInterface
    {
        $instance = clone $this;

        $instance->clock = $clock;

        return $instance;
    }
}
