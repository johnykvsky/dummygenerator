<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;

final class FixedRandomizer implements RandomizerInterface
{
    public function __construct(
        private int $int,
    ) {
    }

    public function getInt(int $min, int $max): int
    {
        return $this->int;
    }

    public function getFloat(float $min, float $max): float
    {
        return (float) $this->int;
    }

    public function getBool(int|float $chanceOfTrue = 50): bool
    {
        return $this->int % 2 === 0;
    }

    public function getBytes(int $length = 16): string
    {
        return str_repeat('0', $length);
    }

    public function getBytesFromString(string $string, int $length = 8): string
    {
        if ($string === '') {
            return str_repeat('0', $length);
        }

        $repeat = (int) ceil($length / strlen($string));
        return substr(str_repeat($string, $repeat), 0, $length);
    }

    public function randomLetter(): string
    {
        return 'a';
    }

    /** @param array<int|string, mixed> $array */
    public function randomElement(array $array): mixed
    {
        foreach ($array as $value) {
            return $value;
        }

        return null;
    }

    /** @param array<int|string, mixed> $array */
    public function randomKey(array $array = []): int|string|null
    {
        foreach ($array as $key => $_value) {
            return $key;
        }

        return null;
    }

    /**
     * @param array<int|string, mixed> $array
     * @return array<int|string, mixed>
     */
    public function shuffleElements(array $array): array
    {
        return $array;
    }

    /**
     * @param array<int|string, mixed> $array
     * @return array<int, mixed>
     * @throws ExtensionArgumentException
     */
    public function randomElements(array $array, int $count = 1, bool $unique = false): array
    {
        $values = array_values($array);

        if ($count > count($values) && $unique) {
            throw new ExtensionArgumentException('Not enough elements in array.');
        }

        return array_slice($values, 0, $count);
    }
}
