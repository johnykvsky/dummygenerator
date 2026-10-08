# Extensions How-To

### Using Enum extension

Enum extension allows you to get random element or value from selected Enum object. It has two methods:

* enumValue(), that will get value from backed enums (which has to be string or int)
* enumCase(), that will get one of `cases()` element from enum (it will be `UnitEnum` object) 

`enumValue()` has to be used on backed enums, but `enumCase()` works for backed and non-backed enums. If passed an enum with no cases, both methods throw an `ExtensionArgumentException`.

For following enum:

```php
enum SuitBackedIntEnum: string
{
    case Hearts = 'Hearts';
    case Diamonds = 'Diamonds';
    case Clubs = 'Clubs';
    case Spades = 'Spades';
}
```

You can do following:

```php
$generator = DummyGenerator::create();
$generator->enumCase(SuitBackedIntEnum::class); // it will get random element, i.e. SuitBackedIntEnum::Diamonds
$generator->enumValue(SuitBackedIntEnum::class); // it will get random value, i.e. "Spades"
```

### Using Strings extension

With `LoremExtension` you can generate `words()` or `text()`. You can generate single word too - with `word()`, it will give you random words from Lorem Ipsum sample.

In [dummyproviders](https://github.com/johnykvsky/dummyproviders) there is also `TextExtension` that allows you to generate random text with given length with `realText()`.

But sometimes you want just a simple random string, with given length or given structure: only letters, with some numbers, with capital letters. This is where `StringsExtension` can help you:

```php
$generator = DummyGenerator::create();
$string1 = $generator->string(); // it will give you random string, lowercase, with length between 3 and 8
$string2 = $generator->string(3, 3); // it will give you random string, lowercase, with length equal to 3
$string4 = $generator->string(3, 10, 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'); // it will give you random string, mixed case, with length from 3 to 10
```

As you can see you can pass any chars pool for generation. `StringsExtension` comes with 3 predefined pools:

 * `Strings::ALPHA_POOL` equals to `abcdefghijklmnopqrstuvwxyz`;
 * `Strings::ALPHA_CASE_POOL` equals to `abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`;
 * `Strings::ALPHA_NUM_POOL` equals to `0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ`;

If you want to have string with possible spaces - just create your own pool, i.e. `abcdefghijkl mnopqrstuvwxyz`

At core, it uses `\Random\Randomizer::getBytesFromString()` to generate random string.


### AnyDateTime versus DateTime

`DummyGenerator` comes with two extensions for date-time related data generation:

* `DateTime`, which provides comprehensive date/time generation methods (supporting both `DateTimeInterface` and date strings for parameters)
* `AnyDateTime`, which is built with a different interval-based approach

In `DateTime` you have various helpers returning `\DateTimeInterface` objects:

```php
$generator->dateTimeThisMonth();
$generator->dateTimeThisYear();
$generator->dateTimePast('-60 days');   // strictly past date
$generator->dateTimeFuture('+90 days'); // strictly future date
$generator->cronExpression();          // cron schedule string like '*/15 * * * *'
$generator->amPm();
// and so on
```

In `AnyDateTime` you have methods centered around date intervals:

* `anyDate($date, $interval, $period)` used to get date "around" passed date
* `anyDateBetween($from, $to)` used to generate date between passed dates
* `anyTimezone($country)` used to get timezone for a country or random timezone

In `AnyDateTime` we operate on `DateTimeInterface` objects (or strings). For `anyDate` you can pass:

* date, which is "starting point" (by default it's "now"), you can pass DateTimeInterface object or just string recognized by it, like '2025-08-30'
* interval, as PHP \DateInterval() or string recognized by it (like 'P5D'), so it can be year, month, 3 days, 5 hours... (by default it's 10 years)
* period, that has only 3 available cases: PAST_DATE, FUTURE_DATE or ANY_DATE (by default it's ANY_DATE)

How it works, it all depends on period:

* PAST_DATE means: take passed date and subtract passed interval from it. Passed date is date to, calculated is date from.
* FUTURE_DATE means: take passed date and add passed interval to it. Passed date is date from, calculated is date to.
* ANY_DATE means: take passed date, calculate date from by subtracting passed interval and calculate date to by adding passed interval.

So, in example, for passed date 2025-08-01 and interval 30 days it will:

* PAST_DATE, date from is 2025-08-01 minus 30 days, date to is 2025-08-01
* FUTURE_DATE, date from is 2025-08-01 and date to is 2025-08-01 plus 30 days
* ANY_DATE, date from is 2025-08-01 minus 30 days, date to is 2025-08-01 plus 30 days

Generated date will be within this date ranges. Since it's returning `DateTimeInterface` object, you can use format() to get desired string value.

### Using Uuid and Modern Identifiers

`DummyGenerator` provides modern, standards-compliant unique identifier generators:

```php
$generator = DummyGenerator::create();

// Standard random UUIDv4:
$uuid4 = $generator->uuid4(); // '09a3cc17-03f7-402b-ae3d-99e144cc3e0b'

// RFC 9562 time-ordered UUIDv7 (ideal for database primary keys):
$uuid7 = $generator->uuid7(); // '018ec25e-7a1b-7888-825b-2d7c5885e7a9'
// Or generated from a specific timestamp:
$uuid7FromDate = $generator->uuid7(new \DateTimeImmutable('2024-01-01 00:00:00'));

// 26-character sortable ULID (Universally Unique Lexicographically Sortable Identifier):
$ulid = $generator->ulid(); // '01ARZ3NDEKTSV4RRFFQ69G5FAV'

// RFC 4122 / RFC 9562 Nil UUID:
$nil = $generator->nilUuid(); // '00000000-0000-0000-0000-000000000000'
```
