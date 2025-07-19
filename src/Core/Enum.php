<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use ReflectionException;
use ReflectionEnum;
use UnitEnum;

class Enum implements EnumExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    /**
     * @param class-string<UnitEnum> $enumClassname
     * @throws ReflectionException
     */
    public function value(string $enumClassname): string|int
    {
        $enum = new ReflectionEnum($enumClassname);

        if (!$enum->isEnum()) {
            throw new ExtensionArgumentException('Argument should be PHP Enum class name');
        }

        return $this->randomizer->randomElement($enumClassname::cases())->value;
    }

    /**
     * @param class-string<UnitEnum> $enumClassname
     * @throws ReflectionException
     */
    public function element(string $enumClassname): UnitEnum
    {
        $enum = new \ReflectionEnum($enumClassname);

        if (!$enum->isEnum()) {
            throw new ExtensionArgumentException('Argument should be PHP Enum class name');
        }

        return $this->randomizer->randomElement($enumClassname::cases());
    }
}
