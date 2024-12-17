<?php

declare(strict_types=1);

namespace DummyGenerator\Definitions\Extension;

interface LoremExtensionInterface extends ExtensionInterface
{
    /**
     * @example 'Lorem'
     */
    public function word(): string;

    /**
     * Generate an array of random words
     *
     * @example array('Lorem', 'ipsum', 'dolor')
     *
     * @param int $wordCount how many words to return
     *
     * @return string[]
     */
    public function words(int $wordCount = 3): array;

    /**
     * Generate a random sentence
     *
     * @example 'Lorem ipsum dolor sit amet.'
     *
     * @param int $wordCount around how many words the sentence should contain
     * @param bool $variableWordCount set to false if you want exactly $nbWords returned,
     *                              otherwise $nbWords may vary by +/-40% with a minimum of 1
     */
    public function sentence(int $wordCount = 6, bool $variableWordCount = true): string;

    /**
     * Generate an array of sentences
     *
     * @example array('Lorem ipsum dolor sit amet.', 'Consectetur adipisicing eli.')
     *
     * @param int $sentenceCount how many sentences to return
     *
     * @return string[]
     */
    public function sentences(int $sentenceCount = 3): array;

    /**
     * Generate a single paragraph
     *
     * @example 'Sapiente sunt omnis. Ut pariatur ad autem ducimus et. Voluptas rem voluptas sint modi dolorem amet.'
     *
     * @param int $sentenceCount around how many sentences the paragraph should contain
     * @param bool $variableSentenceCount set to false if you want exactly $nbSentences returned,
     *                                  otherwise $nbSentences may vary by +/-40% with a minimum of 1
     */
    public function paragraph(int $sentenceCount = 3, bool $variableSentenceCount = true): string;

    /**
     * Generate an array of paragraphs
     *
     * @example array($paragraph1, $paragraph2, $paragraph3)
     *
     * @param int $paragraphCount how many paragraphs to return
     *
     * @return string[]
     */
    public function paragraphs(int $paragraphCount = 3): array;

    /**
     * Generate a text string.
     * Depending on the $maxNbChars, returns a string made of words, sentences, or paragraphs.
     *
     * @example 'Sapiente sunt omnis. Ut pariatur ad autem ducimus et. Voluptas rem voluptas sint modi dolorem amet.'
     *
     * @param int $maxCharacters Maximum number of characters the text should contain (minimum 5)
     */
    public function text(int $maxCharacters = 200): string;
}
