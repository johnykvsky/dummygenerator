# Getting Started

DummyGenerator is a modern PHP library for generating realistic fake data

## Install

```bash
composer require johnykvsky/dummygenerator --dev
```

## Overview

DummyGenerator is a tool for generating fake data. Let's do a quick overview of internal architecture:

* **DummyGenerator** core library. This is core, it's managing everything: Extensions, Strategies, Clock
* **Extensions**. They contain actual code used to generate various data. You need email? Go to Internet extension. Need a first name? There is Person extension. And so on.
* **Strategy**. Once you know that you need email you might have some requirements. Like: I need 100 unique emails. Or: I need emails that will have no more than 10 chars. You can meet those requirements via Strategies: Simple, Chance, Valid, Unique. And you can combine them.
* **Clock** (PSR-20). When dealing with time you might have special requirements, 
* **Dependency Injection Container** (PSR-11, PHP-DI is used). All definitions are stored in container via their interfaces as keys  - which mean all of them can be replaced with your own implementation. Want to add new extension? New strategy? Replace implementation? You can do that.

You don't need to know in which extension is email generation. DummyGenerator uses magic method `__call()` that will figure it out, so all you have to do is:

```php
$name = $dummy->firstName();
```

As DummyGenerator use PHP-DI we have access to autowire feature - it will try to resolve any dependencies in extensins on its own. But there is one specific case. Some extensions use internally DummyGenerator instance. For that reason, to allow proper autowire there is `GeneratorProxy` class. On resolving items it's substituted for generator (remember: container is created before being injected into generator) and later replaced with proper generator instance. 

## Basic Usage

DummyGenerator uses dependency injection container. There are two ways of creating generator, quick one:

```php
$generator = DummyGenerator::create();
```

which will load all extensions and use simple strategy. More on strategies [here](strategies.md)

But you can tailor this to your own needs. DummyGenerator require one single param on creation: dependency injection container. So you can do this:

```php
$container = DiContainerFactory::all();
$dummy = new DummyGenerator($container);
```

Dependency injection container has a factory builder, to simplify its creation. It has 3 methods: `base()`, `default()` and `all()`. Does it matter? Not that much, here is the list of extensions loaded with every item: 

* `base()`: AnyDateTime, Enum, Lorem, Number, Strings, Uuid 
* `default()`, all from Base plus: Coordinates, Country, Hash, Internet, Language, Person 
* `all()`, all from Default plus: Address, Barcode, Biased, Blood, Color, Company, DateTime, File, Payment, PhoneNumber, UserAgent, Version

There is no difference between above two line code and previous one-liner `DummyGenerator::create()`. But since we have clear `$container` variable we can do more:

```php
$container = DiContainerFactory::all();
$container->set(TemplateParserInterface::class, MyTemplateParser::class);
$container->set(PersonExtensionInterface::class, MyPerson::class);
$container->set(InternetExtensionInterface::class, MyInternet::class);
$dummy = new DummyGenerator($container);
```

What is happening there - we have just replaced 3 implementations with our own. From now `$dummy->firstName()` will run `firstName()` from `MyPerson` class. Same for other two replacements, default classes will not be used anymore.

Other way to add custom extension or replace default one is to use `withDefinition()`, but keep in mind that **generator is immutable**:

```php
$generator = DummyGenerator::create();
$newGenerator = $generator->withDefinition(PersonExtensionInterface::class, PersonX::class);
$newGenerator->firstName();
$generator->firstName()
```

So your extension will be used in `newGenerator` while old `generator` will still run default Person extension.

## Definition Types (Class-String vs Callable vs Instance)

When you add or replace definitions in the container (a `DummyContainerInterface`), you can use:

- **Class-string** (`MyExtension::class`): Preferred. Allows PHP-DI to autowire the class without eager instantiation.
- **Callable factory** (`fn () => new MyExtension(...)`): Use when you need custom construction or runtime configuration. It will be instantiated on first use.
- **Prebuilt instance** (`new MyExtension(...)`): Useful when you already have a configured object, but it will be treated as a fixed singleton.

Recommendation: use class-strings for extensions whenever possible, and reserve callables for cases where you must inject non-container configuration.

## Template parsing

DummyGenerator support one method on its own, it's `parse()`, it can be used to work with strings containing references to other extensions methods:

```php
$template = '{{ firstName }} {{ lastName }} <{{ email }}>';
$result = $generator->parse($template);
//or
$template = 'Lucky: {{ numberBetween(1, 100) }}';
$result = $generator->parse($template);
//or
$template = '{{ dateTimeBetween("-1 year", "now") }}';
$result = $generator->parse($template);
```
