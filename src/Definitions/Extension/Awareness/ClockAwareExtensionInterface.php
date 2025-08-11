<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension\Awareness;

use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\Definitions\Extension\ExtensionInterface;

interface ClockAwareExtensionInterface extends ExtensionInterface
{
    public function withClock(SystemClockInterface $clock): ExtensionInterface;
}
