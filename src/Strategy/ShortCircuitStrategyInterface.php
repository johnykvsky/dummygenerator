<?php

declare(strict_types = 1);

namespace DummyGenerator\Strategy;

/**
 * Marker interface for strategies that may return without invoking the callback.
 *
 * Examples: ChanceStrategy (returns default) and ValidStrategy (retries internally).
 */
interface ShortCircuitStrategyInterface extends StrategyInterface
{
}
