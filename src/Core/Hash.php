<?php

declare(strict_types = 1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\HashExtensionInterface;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

class Hash implements HashExtensionInterface
{
    public function __construct(
        protected RandomizerInterface $randomizer
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

    public function sha512(): string
    {
        return bin2hex($this->randomizer->getBytes(64));
    }

    public function base64(int $byteLength = 32): string
    {
        if ($byteLength < 1) {
            throw new ExtensionArgumentException('Byte length must be greater than 0');
        }

        return base64_encode($this->randomizer->getBytes($byteLength));
    }

    public function base64Url(int $byteLength = 32): string
    {
        if ($byteLength < 1) {
            throw new ExtensionArgumentException('Byte length must be greater than 0');
        }

        return rtrim(strtr(base64_encode($this->randomizer->getBytes($byteLength)), '+/', '-_'), '=');
    }
}
