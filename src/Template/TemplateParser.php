<?php

declare(strict_types = 1);

namespace DummyGenerator\Template;

/**
 * Enhanced template parser with support for method arguments.
 *
 * Supported syntax:
 * - {{ methodName }} - Simple token (backward compatible)
 * - {{ methodName() }} - Method call without args
 * - {{ methodName(arg1, arg2) }} - Method with positional args
 * - {{ methodName(key: value) }} - Method with named args
 *
 * Examples:
 * - {{ firstName }}
 * - {{ numberBetween(1, 100) }}
 * - {{ sentence(wordCount: 10) }}
 * - {{ dateTimeBetween('-1 year', 'now') }}
 */
final class TemplateParser implements TemplateParserInterface
{
    /**
     * Parse template with support for method arguments.
     *
     * @param string $template Template string with {{ tokens }}
     * @param callable(string, array<int|string, mixed>): mixed $generator Generator callback that executes methods
     * @return string Parsed template
     */
    public function parse(string $template, callable $generator): string
    {
        // Match patterns: {{ methodName }} or {{ methodName(args) }}
        $pattern = '/\{\{\s*(\w+)\s*(?:\((.*?)\))?\s*\}\}/u';

        return preg_replace_callback($pattern, function (array $matches) use ($generator) {
            $methodName = $matches[1];
            $argsString = $matches[2] ?? '';

            // Parse arguments if present
            $args = $this->parseArguments($argsString);

            // Call generator method
            try {
                $result = $generator($methodName, $args);

                // Convert result to string
                return $this->convertToString($result);
            } catch (\Throwable $e) {
                // Return original token if method fails
                return $matches[0];
            }
        }, $template) ?? '';
    }

    /**
     * Parse argument string into array.
     *
     * Supports:
     * - Positional: "1, 100, 'test'"
     * - Named: "min: 1, max: 100"
     * - Mixed types: integers, floats, strings, booleans, null
     *
     * @param string $argsString Raw argument string from template
     * @return array<int|string, mixed> Parsed arguments (positional or named)
     */
    private function parseArguments(string $argsString): array
    {
        if (trim($argsString) === '') {
            return [];
        }

        // Split by comma, respecting quoted strings
        $tokens = $this->tokenize($argsString);
        $args = [];

        foreach ($tokens as $token) {
            // Check for named argument: key: value
            if (preg_match('/^(\w+)\s*:\s*(.+)$/u', $token, $matches)) {
                $args[$matches[1]] = $this->parseValue($matches[2]);
            } else {
                $args[] = $this->parseValue($token);
            }
        }

        return $args;
    }

    /**
     * Tokenize argument string, respecting quoted strings.
     *
     * Splits by comma but keeps quoted strings intact.
     *
     * @param string $input Argument string
     * @return array<int, string> Array of argument tokens
     */
    private function tokenize(string $input): array
    {
        $tokens = [];
        $current = '';
        $inQuotes = false;
        $quoteChar = null;
        $length = strlen($input);

        for ($i = 0; $i < $length; $i++) {
            $char = $input[$i];

            // Handle quotes
            if (($char === '"' || $char === "'") && ($i === 0 || $input[$i - 1] !== '\\')) {
                if (!$inQuotes) {
                    $inQuotes = true;
                    $quoteChar = $char;
                    $current .= $char;
                } elseif ($char === $quoteChar) {
                    $inQuotes = false;
                    $quoteChar = null;
                    $current .= $char;
                } else {
                    $current .= $char;
                }
            } elseif ($char === ',' && !$inQuotes) {
                // Comma outside quotes = token separator
                if (trim($current) !== '') {
                    $tokens[] = trim($current);
                }

                $current = '';
            } else {
                $current .= $char;
            }
        }

        // Add final token
        if (trim($current) !== '') {
            $tokens[] = trim($current);
        }

        return $tokens;
    }

    /**
     * Parse value to appropriate type.
     *
     * @param string $value Raw value string
     * @return mixed Parsed value (string, int, float, bool, null)
     */
    private function parseValue(string $value): mixed
    {
        $value = trim($value);

        // String literals (quoted)
        if (preg_match('/^(["\'])(.*)\1$/u', $value, $matches)) {
            return $matches[2]; // Remove quotes
        }

        // Booleans
        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        // Null
        if ($value === 'null') {
            return null;
        }

        // Numbers (int or float)
        if (is_numeric($value)) {
            return str_contains($value, '.') ? (float) $value : (int) $value;
        }

        // Return as string if no other type matches
        return $value;
    }

    /**
     * Convert result to string for template output.
     *
     * @param mixed $result Generator method result
     * @return string String representation
     */
    private function convertToString(mixed $result): string
    {
        if ($result === null) {
            return '';
        }

        if (is_bool($result)) {
            return $result ? 'true' : 'false';
        }

        if (is_array($result)) {
            return implode(', ', $result);
        }

        if ($result instanceof \DateTimeInterface) {
            return $result->format('Y-m-d H:i:s');
        }

        return (string) $result;
    }
}
