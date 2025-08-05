<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\EnumExtensionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use ReflectionEnum;
use ReflectionException;
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
        try {
            $enum = new ReflectionEnum($enumClassname);
        } catch (ReflectionException $e) {
            throw new ExtensionArgumentException('Invalid PHP Enum', $e->getCode(), $e);
        }

        if (!$enum->isBacked()) {
            throw new ExtensionArgumentException('Argument should be backed PHP Enum');
        }

        return $this->randomizer->randomElement($enumClassname::cases())->value;
    }

    /**
     * @param class-string<UnitEnum> $enumClassname
     * @throws ReflectionException
     */
    public function element(string $enumClassname): UnitEnum
    {
        try {
            new ReflectionEnum($enumClassname);
        } catch (ReflectionException $e) {
            throw new ExtensionArgumentException('Invalid PHP Enum', $e->getCode(), $e);
        }

        return $this->randomizer->randomElement($enumClassname::cases());
    }
}
