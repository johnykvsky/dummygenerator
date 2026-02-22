# Extensions How-To

### Using Enum extension

Enum extension allows you to get random element or value from selected Enum object. It has two methods:

* enumValue(), that will get value from backed enums (which has to be string or int)
* enumCase(), that will get one of `cases()` element from enum (it will be `UnitEnum` object) 

`enumValue()` has to be used on backed enums, but `enumCase()` works for backed and non-backed enums.

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

* `DateTime`, which is port of Faker DateTime extension, with same methods
* `AnyDateTime`, which is build with a bit of different approach

While in `DateTime` you have various:

```php
$generator->dateTimeThisMonth();
$generator->dateTimeThisYear();
$generator->amPm();
// and so on
```

All of them accepting strings and returning strings. In `AnyDateTime` you have two methods:

* `anyDate($date, $interval, $period)` used to get date "around" passed date
* `anyDateBetween($from, $to)` used to generate date between passed dates


`AnyDateTime` we operate on `DateTimeInterface` objects (or strings). For `anyDate` you can pass:

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
