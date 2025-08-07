<?php

declare(strict_types = 1);

namespace DummyGenerator\Core\Transliterator;

use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;

class Transliterator implements TransliteratorInterface
{
    private const string TRANSLITERATOR_PATTERN = 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';

    public function transliterate(string $string, ?string $pattern = null): string
    {
        if (0 === preg_match('/[^A-Za-z0-9_.]/', $string)) {
            return $string;
        }

        if ($pattern === null) {
            $pattern = self::TRANSLITERATOR_PATTERN;
        }

        $transliterator = \Transliterator::create($pattern);

        if ($transliterator === null) {
            throw new ExtensionArgumentException('Transliterator cannot be created for given pattern');
        }

        $transString = $transliterator->transliterate($string);

        if ($transString === false) {
            return '';
        }

        $transliterated = preg_replace('/[^A-Za-z0-9_.]/u', '', $transString);

        return (!empty($transliterated)) ? $transliterated : '';
    }
}
