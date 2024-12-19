<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Randomizer;

use DummyGenerator\Core\Randomizer\Randomizer;
use PHPUnit\Framework\TestCase;

class RandomizerTest extends TestCase
{
    public function testRandomElement(): void
    {
        $randomizer = new Randomizer();
        $elements = ['23', 'e', 32, '#'];
        self::assertContains($randomizer->randomElement($elements), $elements);
    }

    public function testRandomKey(): void
    {
        $randomizer = new Randomizer();
        $elements = ['key' => '23', 'flower' => 'e', 'mars' => 32, 'hand' => '#'];
        self::assertContains($randomizer->randomKey($elements), array_keys($elements));
    }

    public function testRandomLetter(): void
    {
        $randomizer = new Randomizer();
        $letter = $randomizer->randomLetter();

        self::assertTrue(ord($letter) >= 97 && ord($letter) <= 122);
    }

    public function testGetInt(): void
    {
        $min = 5;
        $max = 50;
        $randomizer = new Randomizer();
        $number = $randomizer->getInt($min, $max);

        self::assertTrue($number >= $min && $number <= $max);
    }

    public function testGetFloat(): void
    {
        $min = 5.50;
        $max = 150.45;
        $randomizer = new Randomizer();
        $number = $randomizer->getFloat($min, $max);

        self::assertTrue($number >= $min && $number <= $max);
    }

    public function testShuffleElements(): void
    {
        $array = ['23', 'e', 32, '#'];
        $randomizer = new Randomizer();
        $shuffled = $randomizer->shuffleElements($array);

        self::assertCount(count($array), $shuffled);
    }
}