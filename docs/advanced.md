# Advanced

## Seeding and Reproducibility

Provide a seeded randomizer for repeatable outputs.

```php
use DummyGenerator\Core\Randomizer\XoshiroRandomizer;
use DummyGenerator\Definitions\Randomizer\RandomizerInterface;
use DummyGenerator\Container\DiContainerFactory;
use DummyGenerator\DummyGenerator;
use DummyGenerator\Template\TemplateParser;
use DummyGenerator\Template\TemplateParserInterface;

$randomizer = new XoshiroRandomizer(12345);
$container = DiContainerFactory::base(); // returns DummyContainer
$container->set(RandomizerInterface::class, fn () => $randomizer);
$container->set(TemplateParserInterface::class, TemplateParser::class);

$generator = new DummyGenerator($container);
```

## Performance Monitoring

```php
```

## Testing Tips

- Create a generator with a seeded randomizer.
- Replace extensions with deterministic fakes for assertions.
