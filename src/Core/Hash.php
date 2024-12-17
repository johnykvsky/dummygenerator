<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\HashExtensionInterface;

class Hash implements HashExtensionInterface, RandomizerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;

    public function md5(): string
    {
        return bin2hex($this->randomizer->getBytes(16));
    }

    public function sha1(): string
    {
        return bin2hex($this->randomizer->getBytes(20));
    }

    public function sha256(): string
    {
        return bin2hex($this->randomizer->getBytes(32));
    }
}
