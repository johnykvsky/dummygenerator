<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Replacer;

use DummyGenerator\Core\Randomizer\Randomizer;
use DummyGenerator\Core\Replacer\Replacer;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertNotSame;

class ReplacerTest extends TestCase
{
    public function testNumerify(): void
    {
        $replacer = (new Replacer())->withRandomizer(new Randomizer());

        $string = 'this#is%test#%';

        $result = $replacer->numerify($string);

        self::assertIsNotNumeric($result[1]);
        self::assertIsNumeric($result[4]);
        self::assertTrue(is_numeric($result[7]) && ((int) $result[7] !== 0));
        self::assertIsNumeric($result[12]);
        self::assertTrue(is_numeric($result[13]) && ((int) $result[13] !== 0));
    }

    public function testLexify(): void
    {
        $replacer = (new Replacer())->withRandomizer(new Randomizer());

        $string = '123?56?89?';

        $result = $replacer->lexify($string);

        self::assertIsNumeric($result[1]);
        self::assertIsNotNumeric($result[3]);
        self::assertIsNotNumeric($result[6]);
        self::assertIsNotNumeric($result[9]);
    }

    public function testBothify(): void
    {
        $replacer = (new Replacer())->withRandomizer(new Randomizer());

        $string = 'this#is1?3te*t';

        $result = $replacer->bothify($string);

        self::assertIsNotNumeric($result[1]);
        self::assertIsNumeric($result[4]);
        self::assertIsNotNumeric($result[8]);
        self::assertNotSame('*', $result[12]);
    }

    public function testShuffleString(): void
    {
        $string = 'this#is%test#%123';

        $replacer = (new Replacer())->withRandomizer(new Randomizer());

        $shuffled = $replacer->shuffleString($string);

        assertNotSame($string, $shuffled);
    }
}
