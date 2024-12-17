<?php

declare(strict_types=1);

namespace DummyGenerator\Core;

use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\GeneratorAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\RandomizerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionInterface;
use DummyGenerator\Definitions\Extension\Awareness\ReplacerAwareExtensionTrait;
use DummyGenerator\Definitions\Extension\Exception\ExtensionArgumentException;
use DummyGenerator\Definitions\Extension\LoremExtensionInterface;

class Lorem implements
    LoremExtensionInterface,
    GeneratorAwareExtensionInterface,
    RandomizerAwareExtensionInterface,
    ReplacerAwareExtensionInterface
{
    use GeneratorAwareExtensionTrait;
    use RandomizerAwareExtensionTrait;
    use ReplacerAwareExtensionTrait;

    /**
     * @var string[]
     */
    private array $workList = [
        'alias', 'consequatur', 'aut', 'perferendis', 'sit', 'voluptatem',
        'accusantium', 'doloremque', 'aperiam', 'eaque', 'ipsa', 'quae', 'ab',
        'illo', 'inventore', 'veritatis', 'et', 'quasi', 'architecto',
        'beatae', 'vitae', 'dicta', 'sunt', 'explicabo', 'aspernatur', 'aut',
        'odit', 'aut', 'fugit', 'sed', 'quia', 'consequuntur', 'magni',
        'dolores', 'eos', 'qui', 'ratione', 'voluptatem', 'sequi', 'nesciunt',
        'neque', 'dolorem', 'ipsum', 'quia', 'dolor', 'sit', 'amet',
        'consectetur', 'adipisci', 'velit', 'sed', 'quia', 'non', 'numquam',
        'eius', 'modi', 'tempora', 'incidunt', 'ut', 'labore', 'et', 'dolore',
        'magnam', 'aliquam', 'quaerat', 'voluptatem', 'ut', 'enim', 'ad',
        'minima', 'veniam', 'quis', 'nostrum', 'exercitationem', 'ullam',
        'corporis', 'nemo', 'enim', 'ipsam', 'voluptatem', 'quia', 'voluptas',
        'sit', 'suscipit', 'laboriosam', 'nisi', 'ut', 'aliquid', 'ex', 'ea',
        'commodi', 'consequatur', 'quis', 'autem', 'vel', 'eum', 'iure',
        'reprehenderit', 'qui', 'in', 'ea', 'voluptate', 'velit', 'esse',
        'quam', 'nihil', 'molestiae', 'et', 'iusto', 'odio', 'dignissimos',
        'ducimus', 'qui', 'blanditiis', 'praesentium', 'laudantium', 'totam',
        'rem', 'voluptatum', 'deleniti', 'atque', 'corrupti', 'quos',
        'dolores', 'et', 'quas', 'molestias', 'excepturi', 'sint',
        'occaecati', 'cupiditate', 'non', 'provident', 'sed', 'ut',
        'perspiciatis', 'unde', 'omnis', 'iste', 'natus', 'error',
        'similique', 'sunt', 'in', 'culpa', 'qui', 'officia', 'deserunt',
        'mollitia', 'animi', 'id', 'est', 'laborum', 'et', 'dolorum', 'fuga',
        'et', 'harum', 'quidem', 'rerum', 'facilis', 'est', 'et', 'expedita',
        'distinctio', 'nam', 'libero', 'tempore', 'cum', 'soluta', 'nobis',
        'est', 'eligendi', 'optio', 'cumque', 'nihil', 'impedit', 'quo',
        'porro', 'quisquam', 'est', 'qui', 'minus', 'id', 'quod', 'maxime',
        'placeat', 'facere', 'possimus', 'omnis', 'voluptas', 'assumenda',
        'est', 'omnis', 'dolor', 'repellendus', 'temporibus', 'autem',
        'quibusdam', 'et', 'aut', 'consequatur', 'vel', 'illum', 'qui',
        'dolorem', 'eum', 'fugiat', 'quo', 'voluptas', 'nulla', 'pariatur',
        'at', 'vero', 'eos', 'et', 'accusamus', 'officiis', 'debitis', 'aut',
        'rerum', 'necessitatibus', 'saepe', 'eveniet', 'ut', 'et',
        'voluptates', 'repudiandae', 'sint', 'et', 'molestiae', 'non',
        'recusandae', 'itaque', 'earum', 'rerum', 'hic', 'tenetur', 'a',
        'sapiente', 'delectus', 'ut', 'aut', 'reiciendis', 'voluptatibus',
        'maiores', 'doloribus', 'asperiores', 'repellat',
    ];

    public function word(): string
    {
        return $this->randomizer->randomElement($this->workList);
    }

    public function words(int $wordCount = 3): array
    {
        $words = [];
        for ($i = 0; $i < $wordCount; $i++) {
            $words[] = $this->word();
        }

        return $words;
    }

    public function sentence(int $wordCount = 6, bool $variableWordCount = true): string
    {
        if ($wordCount <= 0) {
            throw new ExtensionArgumentException('$wordCount should be at least 1');
        }

        if ($variableWordCount === true) {
            $wordCount = $this->randomizeNumberOfElements($wordCount);
        }

        $words = $this->words($wordCount);
        $words[0] = ucwords($words[0]);

        return implode(' ', $words) . '.';
    }

    public function sentences(int $sentenceCount = 3): array
    {
        $sentences = [];
        for ($i = 0; $i < $sentenceCount; ++$i) {
            $sentences[] = $this->sentence();
        }

        return $sentences;
    }

    public function paragraph(int $sentenceCount = 3, bool $variableSentenceCount = true): string
    {
        if ($sentenceCount <= 0) {
            throw new ExtensionArgumentException('$sentenceCount should be at least 1');
        }

        if ($variableSentenceCount) {
            $sentenceCount = $this->randomizeNumberOfElements($sentenceCount);
        }

        return implode(' ', $this->sentences($sentenceCount));
    }

    public function paragraphs(int $paragraphCount = 3): array
    {
        $paragraphs = [];
        for ($i = 0; $i < $paragraphCount; ++$i) {
            $paragraphs[] = $this->paragraph();
        }

        return $paragraphs;
    }

    public function text(int $maxCharacters = 200): string
    {
        if ($maxCharacters < 5) {
            throw new ExtensionArgumentException('$maxCharacters can only generate text of at least 5 characters');
        }

        $subtype = ($maxCharacters < 100) ? 'sentence' : 'paragraph';
        $type = ($maxCharacters < 25) ? 'word' : $subtype;

        $text = [];

        do {
            $size = 0;

            do {
                $word = ($size ? ' ' : '') . $this->$type();
                $text[] = $word;

                $size += $this->replacer->strlen($word);
            } while ($size < $maxCharacters);

            array_pop($text);
        } while (empty($text));

        if ($type === 'word') {
            // capitalize first letter
            $text[0] = ucwords($text[0]);

            // end sentence with full stop
            $text[count($text) - 1] .= '.';
        }

        return implode('', $text);
    }

    private function randomizeNumberOfElements(int $number): int
    {
        return (int) ($number * $this->randomizer->getInt(60, 140) / 100) + 1;
    }
}
