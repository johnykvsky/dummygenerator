<?php

declare(strict_types = 1);

namespace DummyGenerator\Template;

interface TemplateParserInterface
{
    /**
     * Parse template with support for method arguments.
     *
     * @param string $template Template string with {{ tokens }}
     * @param callable(string, array<int|string, mixed>): mixed $generator Generator callback that executes methods
     * @return string Parsed template
     */
    public function parse(string $template, callable $generator): string;
}
