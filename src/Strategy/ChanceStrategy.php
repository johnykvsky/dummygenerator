<?php

declare(strict_types = 1);

namespace DummyGenerator\Strategy;

use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class ChanceStrategy implements StrategyInterface
{
    private RandomizerInterface $randomizer;

    /**
     * Get a value only some percentage of the time.
     *
     * @param float $weight A probability between 0 and 1, 0 means that we always get the default value.
     */
    public function __construct(private readonly float $weight, ?RandomizerInterface $randomizer = null, private readonly mixed $default = null)
    {
        if ($randomizer === null) {
            $this->randomizer = new Randomizer();
        }

        if ($this->weight < 0 || $this->weight > 1) {
            throw new \InvalidArgumentException('Weight should be a float between 0 and 1');
        }
    }

    public function generate(string $name, callable $callback): mixed
    {
        if ($this->randomizer->getInt(1, 100) > (100 * $this->weight)) {
            return $this->default;
        }

        return $callback();
    }
}
