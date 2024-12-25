<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Replacer;

use DummyGenerator\Definitions\DefinitionInterface;

interface ReplacerInterface extends DefinitionInterface
{
    /**
     * Replaces all hash sign ('#') occurrences with a random number
     * Replaces all percentage sign ('%') occurrences with a non-zero number.
     */
    public function numerify(string $string): string;

    /**
     * Replaces all question mark ('?') occurrences with a random letter.
     *
     * @param string $string String that needs to bet parsed
     * @param bool $ascii If true then also ascii chars are used (i.e. for passwords)
     */
    public function lexify(string $string, bool $ascii = false): string;

    /**
     * Replaces hash signs ('#') and question marks ('?') with random numbers and letters
     * An asterisk ('*') is replaced with either a random number or a random letter.
     */
    public function bothify(string $string): string;

    /**
     * Converts string to lowercase. Uses mb_string extension if available.
     */
    public function toLower(string $string): string;

    /**
     * Converts string to uppercase. Uses mb_string extension if available.
     *
     * @param string $string String that should be converted to uppercase
     */
    public function toUpper(string $string = ''): string;

    public function strlen(string $text): int;

    public function transliterate(string $string): string;

    /**
     * Returns a shuffled version of the string.
     *
     * This function does not mutate the original string. It uses the
     * Fisher–Yates algorithm, which is unbiased, together with a Mersenne
     * twister random generator. This function is therefore more random than
     * PHP's shuffle() function, and it is seedable. Additionally, it is
     * UTF8 safe if the mb extension is available.
     *
     * @param string $string   The set to shuffle
     * @return string The shuffled string
     *
     * @see http://en.wikipedia.org/wiki/Fisher%E2%80%93Yates_shuffle
     * @example $generator->shuffleString('hello, world'); // 'rlo,h eold!lw'
     */
    public function shuffleString(string $string = ''): string;
}
