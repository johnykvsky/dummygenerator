<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\BloodExtensionInterface;

class Blood implements BloodExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    /** @var string[] */
    protected array $bloodTypes = ['A', 'AB', 'B', 'O'];

    /** @var string[] */
    protected array $bloodRhFactors = ['+', '-'];

    public function bloodType(): string
    {
        return $this->randomizer->randomElement($this->bloodTypes);
    }

    public function bloodRh(): string
    {
        return $this->randomizer->randomElement($this->bloodRhFactors);
    }

    public function bloodGroup(): string
    {
        return sprintf('%s%s', $this->bloodType(), $this->bloodRh());
    }
}
