<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Fixtures;

use DummyGenerator\Container\DummyContainer;
use DummyGenerator\Clock\SystemClock;
use DummyGenerator\Clock\SystemClockInterface;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\Strategy\SimpleStrategy;
use DummyGenerator\Strategy\StrategyInterface;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

final class TestContainerFactory
{
    /**
     * @param array<string, mixed> $definitions
     */
    public static function withDefinitions(array $definitions, bool $withTemplateParser = true): DummyContainer
    {
        $base = [
            StrategyInterface::class => new SimpleStrategy(),
            SystemClockInterface::class => new SystemClock(),
        ];

        if ($withTemplateParser) {
            $base[TemplateParserInterface::class] = new TemplateParser();
        }

        return DiContainerFactory::custom(array_merge($base, $definitions), $withTemplateParser);
    }

    public static function empty(bool $withTemplateParser = true): DummyContainer
    {
        return self::withDefinitions([], $withTemplateParser);
    }

    public static function withStrategy(StrategyInterface $strategy, bool $withTemplateParser = true): DummyContainer
    {
        return self::withDefinitions([StrategyInterface::class => $strategy], $withTemplateParser);
    }

    public static function withClock(SystemClockInterface $clock, bool $withTemplateParser = true): DummyContainer
    {
        return self::withDefinitions([SystemClockInterface::class => $clock], $withTemplateParser);
    }
}
