<?php

declare(strict_types=1);

namespace DummyGenerator\Core\Transliterator;

use DummyGenerator\Definitions\Extension\Exception\ExtensionRuntimeException;
use DummyGenerator\Definitions\Transliterator\TransliteratorInterface;

class Transliterator implements TransliteratorInterface
{
    public function transliterate(string $string): string
    {
        if (0 === preg_match('/[^A-Za-z0-9_.]/', $string)) {
            return $string;
        }

        $transId = 'Any-Latin; Latin-ASCII; NFD; [:Nonspacing Mark:] Remove; NFC;';

        $transliterator = \Transliterator::create($transId);

        if ($transliterator === null) {
            throw new ExtensionRuntimeException("Transliterator cannot be created for given settings");
        }

        $transString = $transliterator->transliterate($string);

        if ($transString === false) {
            return '';
        }

        $transliterated = preg_replace('/[^A-Za-z0-9_.]/u', '', $transString);

        return (!empty($transliterated)) ? $transliterated : '';
    }
}
