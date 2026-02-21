# Templates

The template parser replaces tokens with generated values.

## Simple Tokens

```php
$template = 'Hello, {{ firstName }} {{ lastName }}!';
$result = $generator->parse($template);
```

## Method Arguments

```php
$template = 'Lucky: {{ numberBetween(1, 100) }}';
$result = $generator->parse($template);
```

## Named Arguments

```php
$template = '{{ sentence(wordCount: 10) }}';
$result = $generator->parse($template);
```

## String Arguments

```php
$template = '{{ dateTimeBetween("-1 year", "now") }}';
$result = $generator->parse($template);
```
