<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Randomizer;

use DummyGenerator\Definitions\DefinitionInterface;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;

interface RandomizerInterface extends DefinitionInterface
{
    public function getInt(int $min, int $max): int;
    public function getFloat(float $min, float $max): float;

    public function getBool(int $chanceOfTrue = 50): bool;

    public function getBytes(int $length = 16): string;

    public function randomLetter(): string;

    /** @param array<int|string, mixed> $array */
    public function randomElement(array $array): mixed;

    /** @param array<int|string, mixed> $array */
    public function randomKey(array $array = []): int|string|null;

    /**
     * @param array<int|string, mixed> $array
     * @return array<int|string, mixed>
     */
    public function shuffleElements(array $array): array;

    /**
     * Returns randomly ordered subsequence of $count elements from a provided array
     *
     * @param array<int|string, mixed> $array Array to take elements from
     * @param int $count Number of elements to take
     * @param bool $unique Allow elements to be picked several times. Defaults to false
     *
     * @throws ExtensionArgumentException When requesting more elements than provided
     *
     * @return array<int, mixed> New array with $count elements from $array
     */
    public function randomElements(array $array, int $count = 1, bool $unique = false): array;
}
