# DummyGenerator

[![Software License][ico-license]](LICENSE)
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

DummyGenerator is dummy/fake data generator for PHP. It's a fork of Faker, heavily rewritten at core, but overall is same easy to use. In example:

```php
$generator = new DummyGenerator(DefinitionContainerBuilder::all());
echo $generator->firstName();
```

Full list of stuff you can generate is available [here](docs/extensions.md).

Faker died ~~for our~~ because of being hard to maintain - more on that [here](https://marmelab.com/blog/2020/10/21/sunsetting-faker.html) and Faker 2.0 seems to be dead because of ["death by committee"](https://github.com/FakerPHP/Faker/discussions/15#discussioncomment-7787434) kind of stuff.

I needed simple dummy data generator for PHP 8.3 and with modern architecture in mind, so DummyGenerator came to life.

# Changes in compare to Faker

* required PHP >= 8.3
* PHPStan level 8 friendly
* PHPUnit tests for core and extensions (yep, some just check for not empty, but hey, it's random data)
* all `mt_rand` / `array_rand` replaced with `\Random\Randomizer`
* no static methods, only one magic method (`__call()` in generator)
* interfaces and dependency injection for everything (all core implementations can be replaced with different ones)
* implementations can be changed on the fly with `addDefinition()`
* language providers removed from core (that makes it ~9.5Mb smaller)
* removed database providers (core is only for dummy data generation)
* removed `HmlLorem`, `Uuid` (you can use any uuid generator like Symfony, Ramsey...)
* removed `File::filePath()` since it was interacting with system, not only generating dummy data
* added `Enum`, to get random values from PHP enums
* added `String`, to generate random string from given pool (see [HowTo](docs/howto.md) for more)

This package also fixes problem with FakerPHP `__destruct()` messing up with `seed`, plus various other issues.

There are two Randomizer implementations, default `Randomizer` and if someone need it there is `XoshiroRandomizer` that allows to use `seed` for testing purposes (check `BiasedTest`).

Providers are gone, but [here](https://github.com/johnykvsky/dummy-providers) are sample providers `en_US`,`en_GB` and `pl_PL` to show how to make them / convert from old Faker.

# What is this fake / dummy data

When writing tests or populating test database you need to came up with various data, like first name, last name, some dates, maybe description, location coordinates and so on. When you deal with multi-language site and want to have it also multilanguage - you need to came up with every language names or address format.

All of that can be done by hand, but it's much easier to do `$generator->firstName()` and just don't care about what name it will be. Load provider and don't care about given locale names or phone formats.

Another use case - imagine you have description with 100 chars limit and want to test if it properly gives error when more is passed - instead of copying some text you can just use `$generator->text(150)` to get ~150 characters long text.

Last but not least - it make sure your tests will get random data on each run, not every single time same value. If your code is good and tests correct - then it should be no problem. If tests start failing from time to time - then what you think, where is the problem:

* with code
* with tests
* with random data, it should not be random

I leave answer to you. And yes, there might be cases when data should not be random, but usually it's not that case ;)

# Installation

```shell
composer require johnykvsky/dummygenerator --dev
```

### Quick Start

Everybody like quick start - it's [here](docs/quick_start.md), you're welcome.

### HowTo

For quick info about how to do various stuff visit [HowTo](docs/howto.md)

# Other stuff

There is `script\ExtensionsDocs.php` that can be used to generate list of available extensions and their methods (look at `generate-spec.php`)

Since `--repeat` is still missing in PHPUnit [here](https://github.com/johnykvsky/phpunit-repeat) is Linux shell script for running tests multiple times.

# TODO (ideas, not promises)

* add more providers?

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/johnykvsky/dummygenerator.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/johnykvsky/dummygenerator.svg?style=flat-square
[ico-build]: https://github.com/johnykvsky/dummygenerator/actions/workflows/php.yml/badge.svg

[link-scrutinizer]: https://scrutinizer-ci.com/g/johnykvsky/dummygenerator/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/johnykvsky/dummygenerator
[link-build]: https://github.com/johnykvsky/dummygenerator/actions/workflows/php.yml
