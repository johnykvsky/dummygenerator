<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\Exception\ExtensionOverflowException;
use DummyGenerator\Definitions\Extension\TextExtensionInterface;

abstract class Text implements
    TextExtensionInterface,
    RandomizerAwareExtensionInterface,
    ReplacerAwareExtensionInterface
{
    use RandomizerAwareExtensionTrait;
    use ReplacerAwareExtensionTrait;

    protected string $baseText = '';
    /**
     * @var non-empty-string
     */
    protected string $separator = ' ';
    protected int $separatorLen = 1;
    /**
     * @var array<int, string>
     */
    protected array $explodedText = [];
    /**
     * @var array<int, array<string, array<int, string>>>
     */
    protected array $consecutiveWords = [];
    protected bool $textStartsWithUppercase = true;

    /**
     * Generate a text string by the Markov chain algorithm.
     *
     * Depending on the $maxNbChars, returns a random valid looking text. The algorithm
     * generates a weighted table with the specified number of words as the index and the
     * possible following words as the value.
     *
     * @example 'Alice, swallowing down her flamingo, and began by taking the little golden key'
     *
     * @param int $min Minimum number of characters the text should contain (maximum: 8)
     * @param int $max Maximum number of characters the text should contain (minimum: 10)
     * @param int $indexSize  Determines how many words are considered for the generation of the next word.
     *                        The minimum is 1, and it produces a higher level of randomness, although the
     *                        generated text usually doesn't make sense. Higher index sizes (up to 5)
     *                        produce more correct text, at the price of less randomness.
     *
     * @return string
     */
    public function realText(int $min = 160, int $max = 200, int $indexSize = 2): string
    {
        if ($min < 1) {
            throw new ExtensionArgumentException('min must be at least 1');
        }

        if ($max < 10) {
            throw new ExtensionArgumentException('max must be at least 10');
        }

        if ($indexSize < 1) {
            throw new ExtensionArgumentException('indexSize must be at least 1');
        }

        if ($indexSize > 5) {
            throw new ExtensionArgumentException('indexSize must be at most 5');
        }

        if ($min >= $max) {
            throw new ExtensionArgumentException('min must be smaller than max');
        }

        $words = $this->getConsecutiveWords($indexSize);

        $iterations = 0;

        do {
            ++$iterations;

            if ($iterations >= 100) {
                throw new ExtensionOverflowException(sprintf('Maximum retries of %d reached without finding a valid real text', $iterations));
            }

            $result = $this->generateText($max, $words);
        } while ($this->replacer->strlen($result) <= $min);

        return $result;
    }

    /**
     * @param int $max
     * @param array<string, array<int, string>> $words
     * @return string
     */
    protected function generateText(int $max, array $words): string
    {
        $result = [];
        $resultLength = 0;
        // take a random starting point
        /** @var string $next */
        $next = $this->randomizer->randomKey($words);

        while ($resultLength < $max && isset($words[$next])) {
            // fetch a random word to append
            $word = $this->randomizer->randomElement($words[$next]);

            // calculate next index
            $currentWords = explode($this->separator, $next);
            $currentWords[] = $word;
            array_shift($currentWords);
            $next = implode($this->separator, $currentWords);

            // ensure text starts with an uppercase letter
            if ($resultLength === 0 && !$this->validStart($word)) {
                continue;
            }

            // append the element
            $result[] = $word;
            $resultLength += $this->replacer->strlen($word) + $this->separatorLen;
        }

        // remove the element that caused the text to overflow
        array_pop($result);

        // build result
        $result = implode($this->separator, $result);

        return preg_replace("/([ ,-:;\x{2013}\x{2014}]+$)/us", '', $result) . '.';
    }

    /**
     * @param int $indexSize
     * @return array<string, array<int, string>>
     */
    protected function getConsecutiveWords(int $indexSize): array
    {
        if (!isset($this->consecutiveWords[$indexSize])) {
            $parts = $this->getExplodedText();
            $words = [];
            $index = [];

            for ($i = 0; $i < $indexSize; ++$i) {
                $index[] = array_shift($parts);
            }

            $partsCount = count($parts);
            for ($i = 0; $i < $partsCount; ++$i) {
                $stringIndex = implode($this->separator, $index);

                if (!isset($words[$stringIndex])) {
                    $words[$stringIndex] = [];
                }
                $word = $parts[$i];
                $words[$stringIndex][] = $word;
                array_shift($index);
                $index[] = $word;
            }
            // cache look up words for performance
            /** @var array<string, array<int, string>> $words */
            $this->consecutiveWords[$indexSize] = $words;
        }

        return $this->consecutiveWords[$indexSize];
    }

    /**
     * @return array<int, string>
     */
    protected function getExplodedText(): array
    {
        if (empty($this->explodedText)) {
            $replaced = preg_replace('/\s+/u', ' ', $this->baseText);
            $this->explodedText = explode($this->separator, $replaced ?? '');
        }

        return $this->explodedText;
    }

    protected function validStart(string $word): bool
    {
        $isValid = true;

        if ($this->textStartsWithUppercase) {
            $isValid = preg_match('/^\p{Lu}/u', $word);
        }

        return (bool) $isValid;
    }
}
