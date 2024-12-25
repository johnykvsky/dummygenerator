<?php

declare(strict_types = 1);

namespace DummyGenerator\Core\Replacer;

use DummyGenerator\Definitions\Replacer\RandomizerAwareReplacerInterface;
use DummyGenerator\Definitions\Replacer\RandomizerAwareReplacerTrait;
use DummyGenerator\Definitions\Transliterator\TransliteratorAwareReplacerInterface;
use DummyGenerator\Definitions\Transliterator\TransliteratorAwareReplacerTrait;

class Replacer implements RandomizerAwareReplacerInterface, TransliteratorAwareReplacerInterface
{
    use RandomizerAwareReplacerTrait;
    use TransliteratorAwareReplacerTrait;

    public const string ENCODING = 'UTF-8';

    public function numerify(string $string): string
    {
        // instead of using randomDigit() several times, which is slow,
        // count the number of hashes and generate once a large number
        $toReplace = [];

        if (($pos = strpos($string, '#')) !== false) {
            for ($i = $pos, $last = strrpos($string, '#', $pos) + 1; $i < $last; ++$i) {
                if ($string[$i] === '#') {
                    $toReplace[] = $i;
                }
            }
        }

        if ($nbReplacements = count($toReplace)) {
            $maxAtOnce = $this->strlen((string) PHP_INT_MAX) - 1;
            $numbers = '';
            $i = 0;

            while ($i < $nbReplacements) {
                $size = min($nbReplacements - $i, $maxAtOnce);
                $numbers .= str_pad((string) $this->randomizer->getInt(0, 10 ** $size - 1), $size, '0', STR_PAD_LEFT);
                $i += $size;
            }

            for ($i = 0; $i < $nbReplacements; ++$i) {
                $string[$toReplace[$i]] = $numbers[$i];
            }
        }

        return $this->replaceWildcard($string, '%', fn () => $this->randomizer->getInt(1, 9));
    }

    public function lexify(string $string, bool $ascii = false): string
    {
        return $this->replaceWildcard($string, '?', function () use ($ascii): string {
            if ($ascii) {
                return chr($this->randomizer->getInt(33, 126)); //ascii and letters
            }

            return chr($this->randomizer->getInt(97, 122)); // only letters (a-z)
        });
    }

    public function bothify(string $string): string
    {
        $string = $this->replaceWildcard($string, '*', fn () => $this->randomizer->getInt(0, 1) ? '#' : '?');

        return $this->lexify($this->numerify($string));
    }

    public function toLower(string $string): string
    {
        return extension_loaded('mbstring') ? mb_strtolower($string, static::ENCODING) : strtolower($string);
    }

    public function toUpper(string $string = ''): string
    {
        return extension_loaded('mbstring') ? mb_strtoupper($string, static::ENCODING) : strtoupper($string);
    }

    public function strlen(string $text): int
    {
        return extension_loaded('mbstring') ? mb_strlen($text, static::ENCODING) : strlen($text);
    }

    public function transliterate(string $string): string
    {
        return $this->transliterator->transliterate($string);
    }

    public function shuffleString(string $string = ''): string
    {
        if (extension_loaded('mbstring')) {
            // UTF8-safe str_split()
            $array = [];
            $strlen = mb_strlen($string, static::ENCODING);

            for ($i = 0; $i < $strlen; ++$i) {
                $array[] = mb_substr($string, $i, 1, static::ENCODING);
            }
        } else {
            $array = str_split($string);
        }

        return implode('', $this->randomizer->shuffleElements($array));
    }

    /**
     * Replace wildcard with given callback result
     */
    private function replaceWildcard(string $string, string $wildcard, callable $callback): string
    {
        if (($pos = strpos($string, $wildcard)) === false) {
            return $string;
        }

        for ($i = $pos, $last = strrpos($string, $wildcard, $pos) + 1; $i < $last; ++$i) {
            if ($string[$i] === $wildcard) {
                $string[$i] = $callback();
            }
        }

        return $string;
    }
}
