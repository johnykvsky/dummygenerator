<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Transliterator;

use DummyGenerator\Core\Transliterator\SimpleTransliterator;
use DummyGenerator\Core\Transliterator\Transliterator;
use PHPUnit\Framework\TestCase;

class TransliteratorTest extends TestCase
{
    public function testTransliterator(): void
    {
        $transliterator = new Transliterator();
        self::assertEquals($transliterator->transliterate('pącz`bęcń/inio'), 'paczbecninio');
    }

    public function testSimpleTransliterator(): void
    {
        $transliterator = new SimpleTransliterator();
        self::assertEquals($transliterator->transliterate('pącz`bęcń/inio'), 'paczbecninio');
    }
}