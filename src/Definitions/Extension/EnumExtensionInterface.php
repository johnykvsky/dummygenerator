<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

use UnitEnum;

interface EnumExtensionInterface extends ExtensionInterface
{
    /**
     * Get a random value from passed PHP backed enum
     *
     * @example 'diamonds'
     */
    public function enumValue(string $enumClassname): string|int;

    /**
     * Get a random element from passed PHP enum
     *
     * @example Suit::Diamonds
     */
    public function enumElement(string $enumClassname): UnitEnum;
}
