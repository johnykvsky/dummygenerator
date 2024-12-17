<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Randomizer;

use DummyGenerator\Definitions\DefinitionInterface;

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
}
