<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface TextExtensionInterface extends ExtensionInterface
{
    /**
     * Generate a text string by the Markov chain algorithm.
     *
     * Depending on the $max, returns a random valid looking text. The algorithm
     * generates a weighted table with the specified number of words as the index and the
     * possible following words as the value.
     *
     * @example 'Alice, swallowing down her flamingo, and began by taking the little golden key'
     *
     * @param int $min Minimum number of characters the text should contain
     * @param int $max Maximum number of characters the text should contain
     * @param int $indexSize  Determines how many words are considered for the generation of the next word.
     *                        The minimum is 1, and it produces a higher level of randomness, although the
     *                        generated text usually doesn't make sense. Higher index sizes (up to 5)
     *                        produce more correct text, at the price of less randomness.
     */
    public function realText(int $min = 0, int $max = 200, int $indexSize = 2): string;
}
