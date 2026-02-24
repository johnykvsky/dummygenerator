<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use ReflectionEnum;
use ReflectionException;
use UnitEnum;

class Enum implements EnumExtensionInterface
{
    public function __construct(
        protected RandomizerInterface $randomizer
    ) {
    }

    /**
     * @param class-string<UnitEnum> $enum
     * @throws ExtensionArgumentException
     */
    public function enumValue(string $enum): string|int
    {
        try {
            $enumItem = new ReflectionEnum($enum);
        } catch (ReflectionException $e) {
            throw new ExtensionArgumentException('Invalid PHP Enum', $e->getCode(), $e);
        }

        if (!$enumItem->isBacked()) {
            throw new ExtensionArgumentException('Argument should be backed PHP Enum');
        }

        return $this->randomizer->randomElement($enum::cases())->value;
    }

    /**
     * @param class-string<UnitEnum> $enum
     * @throws ExtensionArgumentException
     */
    public function enumCase(string $enum): UnitEnum
    {
        try {
            new ReflectionEnum($enum);
        } catch (ReflectionException $e) {
            throw new ExtensionArgumentException('Invalid PHP Enum', $e->getCode(), $e);
        }

        return $this->randomizer->randomElement($enum::cases());
    }
}
