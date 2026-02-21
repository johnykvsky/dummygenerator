<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\HashExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class Hash implements HashExtensionInterface
{
    public function __construct(
        private RandomizerInterface $randomizer
    ) {
    }

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
