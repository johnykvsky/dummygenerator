<?php

declare(strict_types = 1);

namespace DummyGenerator\Definitions\Extension;

interface LoremExtensionInterface extends ExtensionInterface
{
    /** @example 'Lorem' */
    public function word(): string;

    /**
     * Generate an array of random words
     *
     * @param int $wordCount how many words to return
     * @return string[]
     *
     * @example array('Lorem', 'ipsum', 'dolor')
     */
    public function words(int $wordCount = 3): array;

    /**
     * Generate a random sentence
     *
     * @param int $wordCount around how many words the sentence should contain
     * @param bool $variableWordCount set to false if you want exactly $nbWords returned,
     *                              otherwise $nbWords may vary by +/-40% with a minimum of 1
     *
     * @example 'Lorem ipsum dolor sit amet.'
     */
    public function sentence(int $wordCount = 6, bool $variableWordCount = true): string;

    /**
     * Generate an array of sentences
     *
     * @param int $sentenceCount how many sentences to return
     * @return string[]
     *
     * @example array('Lorem ipsum dolor sit amet.', 'Consectetur adipisicing eli.')
     */
    public function sentences(int $sentenceCount = 3): array;

    /**
     * Generate a single paragraph
     *
     * @param int $sentenceCount around how many sentences the paragraph should contain
     * @param bool $variableSentenceCount set to false if you want exactly $nbSentences returned,
     *                                  otherwise $nbSentences may vary by +/-40% with a minimum of 1
     *
     * @example 'Sapiente sunt omnis. Ut pariatur ad autem ducimus et. Voluptas rem voluptas sint modi dolorem amet.'
     */
    public function paragraph(int $sentenceCount = 3, bool $variableSentenceCount = true): string;

    /**
     * Generate an array of paragraphs
     *
     * @param int $paragraphCount how many paragraphs to return
     * @return string[]
     *
     * @example array($paragraph1, $paragraph2, $paragraph3)
     */
    public function paragraphs(int $paragraphCount = 3): array;

    /**
     * Generate a text string.
     * Depending on the $maxNbChars, returns a string made of words, sentences, or paragraphs.
     *
     * @param int $maxCharacters Maximum number of characters the text should contain (minimum 5)
     *
     * @example 'Sapiente sunt omnis. Ut pariatur ad autem ducimus et. Voluptas rem voluptas sint modi dolorem amet.'
     */
    public function text(int $maxCharacters = 200): string;
}
