# Generators and Extensions

This is the authoritative list of generator methods, grouped by extension interface.

## Address

Examples:
```php
$generator->address();
$generator->city();
```

Methods:
- `address(): string`
- `city(): string`
- `cityPrefix(): string`
- `citySuffix(): string`
- `streetSuffix(): string`
- `postcode(): string`
- `streetName(): string`
- `streetAddress(): string`
- `buildingNumber(): string`
- `country(): string`

## AnyDateTime

Examples:
```php
$generator->anyDate();
$generator->anyTimezone();
```

Methods:
- `anyDate(\DateTimeInterface|string|null $date = null, \DateInterval|string $interval = 'P10Y', DatePeriodEnum $period = DatePeriodEnum::ANY_DATE): \DateTimeInterface`
- `anyDateBetween(?\DateTimeInterface $from = null, ?\DateTimeInterface $until = null): \DateTimeInterface`
- `anyTimezone(?string $country = null): string`

## Barcode

Examples:
```php
$generator->ean13();
$generator->isbn13();
```

Methods:
- `ean13(): string`
- `ean8(): string`
- `isbn10(): string`
- `isbn13(): string`

## Biased

Examples:
```php
$generator->biasedNumberBetween(1, 100, 'sqrt');
```

Methods:
- `biasedNumberBetween(int $min = 0, int $max = 100, callable|string $function = 'sqrt'): int`
- `unbiased(): int`
- `linearLow(float $number): float`
- `linearHigh(float $number): float`

## Blood

Examples:
```php
$generator->bloodType();
$generator->bloodGroup();
```

Methods:
- `bloodType(): string`
- `bloodRh(): string`
- `bloodGroup(): string`

## Color

Examples:
```php
$generator->hexColor();
$generator->colorName();
```

Methods:
- `hexColor(): string`
- `safeHexColor(): string`
- `rgbColorAsArray(): array`
- `rgbColor(): string`
- `rgbCssColor(): string`
- `rgbaCssColor(): string`
- `safeColorName(): string`
- `colorName(): string`
- `hslColor(): string`
- `hslColorAsArray(): array`

## Company

Examples:
```php
$generator->company();
$generator->jobTitle();
```

Methods:
- `company(): string`
- `companySuffix(): string`
- `jobTitle(): string`

## Coordinates

Examples:
```php
$generator->latitude();
$generator->coordinates();
```

Methods:
- `latitude(float $min = -90.0, float $max = 90.0): float`
- `longitude(float $min = -180.0, float $max = 180.0): float`
- `coordinates(): array`

## Country

Examples:
```php
$generator->countryISOAlpha2();
$generator->countryISOAlpha3();
```

Methods:
- `countryISOAlpha2(): string`
- `countryISOAlpha3(): string`

## DateTime

Examples:
```php
$generator->dateTime();
$generator->dateTimeBetween('-1 year', 'now');
```

Methods:
- `dateTime(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeAD(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeBetween(DateTimeInterface|string $from = '-30 years', DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeInInterval(DateTimeInterface|string $from = '-30 years', DateInterval|string $interval = '+5 days', ?string $timezone = null): \DateTimeInterface`
- `dateTimeThisWeek(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeThisMonth(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeThisYear(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeThisDecade(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `dateTimeThisCentury(DateTimeInterface|string $until = 'now', ?string $timezone = null): \DateTimeInterface`
- `date(string $format = 'Y-m-d', DateTimeInterface|string $until = 'now'): string`
- `time(string $format = 'H:i:s', DateTimeInterface|string $until = 'now'): string`
- `unixTime(DateTimeInterface|string $until = 'now'): int`
- `iso8601(DateTimeInterface|string $until = 'now'): string`
- `amPm(DateTimeInterface|string $until = 'now'): string`
- `dayOfMonth(DateTimeInterface|string $until = 'now'): string`
- `dayOfWeek(DateTimeInterface|string $until = 'now'): string`
- `month(DateTimeInterface|string $until = 'now'): string`
- `monthName(DateTimeInterface|string $until = 'now'): string`
- `year(DateTimeInterface|string $until = 'now'): string`
- `century(): string`
- `timezone(): string`

## Enum

Examples:
```php
$generator->enumValue(MyEnum::class);
```

Methods:
- `enumValue(string $enum): string|int`
- `enumCase(string $enum): UnitEnum`

## File

Examples:
```php
$generator->mimeType();
$generator->extension();
```

Methods:
- `mimeType(): string`
- `extension(): string`

## Hash

Examples:
```php
$generator->sha256();
$generator->md5();
```

Methods:
- `md5(): string`
- `sha1(): string`
- `sha256(): string`

## Internet

Examples:
```php
$generator->email();
$generator->url();
```

Methods:
- `email(): string`
- `safeEmail(): string`
- `freeEmail(): string`
- `companyEmail(): string`
- `freeEmailDomain(): string`
- `safeEmailDomain(): string`
- `userName(): string`
- `password(int $minLength = 6, int $maxLength = 20): string`
- `domainName(): string`
- `domainWord(): string`
- `tld(): string`
- `url(): string`
- `slug(int $nbWords = 6, bool $variableNbWords = true): string`
- `ipv4(): string`
- `ipv6(): string`
- `localIpv4(): string`
- `macAddress(): string`

## Language

Examples:
```php
$generator->languageCode();
$generator->locale();
```

Methods:
- `languageCode(): string`
- `locale(): string`

## Lorem

Examples:
```php
$generator->sentence();
$generator->paragraph();
```

Methods:
- `word(): string`
- `words(int $wordCount = 3): array`
- `sentence(int $wordCount = 6, bool $variableWordCount = true): string`
- `sentences(int $sentenceCount = 3): array`
- `paragraph(int $sentenceCount = 3, bool $variableSentenceCount = true): string`
- `paragraphs(int $paragraphCount = 3): array`
- `text(int $maxCharacters = 200): string`

## Number

Examples:
```php
$generator->numberBetween(1, 100);
$generator->randomFloat(2, 0, 10);
```

Methods:
- `numberBetween(int $min, int $max): int`
- `randomDigit(): int`
- `randomDigitNot(int $except, int $retries = 1000): int`
- `randomDigitNotZero(): int`
- `randomFloat(?int $nbMaxDecimals, float $min, ?float $max): float`
- `randomNumber(?int $nbDigits, bool $strict = false): int`
- `boolean(int $chanceOfGettingTrue = 50): bool`

## Payment

Examples:
```php
$generator->creditCardNumber();
$generator->iban();
```

Methods:
- `creditCardType(): string`
- `creditCardNumber(?string $type = null, bool $formatted = false, string $separator = '-'): string`
- `creditCardExpirationDate(bool $inFuture = true): string`
- `creditCardDetails(bool $valid = true): array`
- `iban(?string $alpha2 = null, string $prefix = ''): string`
- `swiftBicNumber(): string`
- `currencyCode(): string`

## Person

Examples:
```php
$generator->firstName();
$generator->lastName();
```

Methods:
- `name(?string $gender = null): string`
- `firstName(?string $gender = null): string`
- `firstNameMale(): string`
- `firstNameFemale(): string`
- `lastName(): string`
- `title(?string $gender = null): string`
- `titleMale(): string`
- `titleFemale(): string`

## PhoneNumber

Examples:
```php
$generator->phoneNumber();
$generator->e164PhoneNumber();
```

Methods:
- `phoneNumber(): string`
- `e164PhoneNumber(): string`
- `imei(): string`

## Strings

Examples:
```php
$generator->string(5, 10);
```

Methods:
- `string(int $min = 3, int $max = 8, ?string $pool = null): string`

## UserAgent

Examples:
```php
$generator->userAgent();
$generator->chrome();
```

Methods:
- `userAgent(): string`
- `chrome(): string`
- `edge(): string`
- `firefox(): string`
- `safari(): string`
- `opera(): string`
- `internetExplorer(): string`
- `windowsPlatformToken(): string`
- `macPlatformToken(): string`
- `iosMobileToken(): string`
- `androidMobileToken(): string`
- `linuxPlatformToken(): string`

## Uuid

Examples:
```php
$generator->uuid4();
```

Methods:
- `uuid4(): string`

## Version

Examples:
```php
$generator->semver();
```

Methods:
- `semver(bool $preRelease = false, bool $build = false): string`
