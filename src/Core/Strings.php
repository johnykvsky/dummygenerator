<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\StringsExtensionInterface;

class Strings implements
    StringsExtensionInterface,
    GeneratorAwareExtensionInterface,
    RandomizerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;

    public const string ALPHA_POOL = 'abcdefghijklmnopqrstuvwxyz';
    public const string ALPHA_CASE_POOL = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public const string ALPHA_NUM_POOL = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    public function string(int $min = 3, int $max = 8, ?string $pool = null): string
    {
        if ($min < 1) {
            throw new ExtensionArgumentException('$min should be at least 1');
        }

        if ($min > $max) {
            throw new ExtensionArgumentException('$min cannot be higher than $max');
        }

        if ($pool === null) {
            $pool = self::ALPHA_POOL;
        }

        $length = $this->randomizer->getInt($min, $max);

        return $this->randomizer->getBytesFromString($pool, $length);
    }
}
