# DummyGenerator

[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-build]][link-build]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]

DummyGenerator is dummy/fake data generator for PHP. It's a fork of Faker heavily rewritten at core, but overall is same easy to use. In example:

```php
        $generator = new DummyGenerator(DefinitionContainerBuilder::all());
        echo $generator->firstName();
```

Faker died ~~for our~~ because of being hard to maintain - more on that [here](https://marmelab.com/blog/2020/10/21/sunsetting-faker.html) and Faker 2.0 seems to be dead because of ["death by committee"](https://github.com/FakerPHP/Faker/discussions/15#discussioncomment-7787434) kind of stuff.

I needed simple dummy data generator for PHP 8.3 and with modern architecture in mind, so DummyGenerator came to life.

# Changes

* required PHP >= 8.3
* PHPStan level 8 friendly
* PHPUnit tests for core and extensions (yep, some just check for not empty, but hey, it's random data)
* all `mt_rand` / `array_rand` replaced with `\Random\Randomizer`
* no static methods, only one magic method (`__call()` in generator)
* interfaces and dependency injection for everything (all core implementations can be replaced with different ones)
* language providers removed from core (that makes it ~9.8Mb smaller)
* removed database providers (core is only for dummy data generation)
* removed `HmlLorem`, `Uuid` (you can use any uuid generator, Symfony, Ramsey...)
* removed `File::filePath()` since it was interacting with system, not only generating dummy data

There are two Randomizer implementations, default `Randomizer` and if someone need it there is `XoshiroRandomizer` that allows to use `seed` for testing purposes (check `BiasedTest`).

Providers are gone, but [here](https://github.com/johnykvsky/dummy-providers) are sample providers `en_US`,`en_GB` and `pl_PL` to show how to make them / convert from old Faker.

# Installation

```shell
composer require johnykvsky/dummygenerator --dev
```

### Quick Start

Everybody like quick start - it's [here](docs/quick_start.md), you're welcome.

### Container and Strategy

How to use them, Quick Start is not enough - just check [this](docs/container.md)

### Interfaces

Once you're done with previous readings you can read more about available [interfaces](docs/overwriting_stuff).
It's important read, because you will get to know how to overwrite stuff with your custom implementation (not just by "extend class").

# Other stuff

There is `script\ExtensionsDocs.php` that can be used to generate list of available extensions and their methods (look at `generate-spec.php`)

Since `--repeat` is still missing in PHPUnit [here](https://github.com/johnykvsky/phpunit-repeat) is Linux shell script for running tests multiple times.

# TODO (ideas, not promises)

* refactor randomizer (DRY) and add test to randomElements

[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/johnykvsky/dummygenerator.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/johnykvsky/dummygenerator.svg?style=flat-square
[ico-build]: https://github.com/johnykvsky/dummygenerator/actions/workflows/php.yml/badge.svg

[link-scrutinizer]: https://scrutinizer-ci.com/g/johnykvsky/dummygenerator/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/johnykvsky/dummygenerator
[link-build]: https://github.com/johnykvsky/dummygenerator/actions/workflows/php.yml
