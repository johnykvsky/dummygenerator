# Changelog

---

## v0.3.0

### Added
* **Modern Identifiers (`UuidExtensionInterface` / `Uuid`)**:
  * `uuid7()`: RFC 9562 time-ordered UUIDv7 (ideal for database primary keys).
  * `ulid()`: 26-character sortable Crockford Base32 ULID.
  * `nilUuid()`: RFC 4122 / RFC 9562 Nil UUID.
* **Web, Networking & APIs (`InternetExtensionInterface` / `Internet`)**:
  * `port()`: Random network port number (`1024`–`65535`).
  * `httpMethod()`: Random HTTP verb (`GET`, `POST`, `PUT`, `PATCH`, `DELETE`, `HEAD`, `OPTIONS`).
  * `httpStatusCode()`: Realistic HTTP status codes with optional category filter.
  * `publicIpv4()`: Publicly-routable IPv4 address.
  * `ipv4Cidr()`: Subnet / CIDR mask notation (e.g. `192.168.1.0/24`).
  * `urlPath()`: Path segments (e.g. `/api/users`).
  * `jwt()`: Dummy 3-part JSON Web Token for Authorization headers.
* **Financial & Payments (`PaymentExtensionInterface` / `Payment`)**:
  * `creditCardCvv()`: Credit card CVV/CVC code.
  * `creditCardDetails()`: Updated to include `cvv` in the returned array shape.
  * `currencySymbol()`: Currency symbols (`$`, `€`, `£`, `¥`, `zł`, `CHF`, etc.).
  * `currencyName()`: Full currency names (`US Dollar`, `Euro`, etc.).
  * `price()`: Semantic monetary value helper with min, max, and decimal control.
* **File Metadata (`FileExtensionInterface` / `File`)**:
  * `fileName()`: Random filename with extension (pure string, zero filesystem side effects).
  * `fileSize()`: File size in bytes or human-readable format (`1.5 MB`).
  * `mimeTypeForExtension()`: MIME type lookup by file extension.
* **Security & Cryptography (`HashExtensionInterface` / `Hash`)**:
  * `sha512()`: 128-hexadecimal-character SHA-512 cryptographic hash.
  * `base64()`: Base64-encoded random byte sequence.
  * `base64Url()`: URL-safe unpadded Base64 string (OAuth2/PKCE tokens).
* **Numbers & Bitmasks (`NumberExtensionInterface` / `Number`)**:
  * `boolean()`: Now supports float values from range 0..1 (e.g. `0.001` for 0.1% chance).
  * `percentage()`: Random percentage value (integer or float).
  * `hexadecimal()`: Random hexadecimal string of specified length.
  * `binary()`: Random binary bitmask string of specified length.
* **Dates & Scheduling (`DateTimeExtensionInterface` / `DateTime`)**:
  * `dateTimePast()`: Helper for strictly past dates.
  * `dateTimeFuture()`: Helper for strictly future dates.
  * `cronExpression()`: Standard 5-part cron schedule string (e.g. `'*/15 * * * *'`).
* **Person & Company Fallbacks (`PersonExtensionInterface` / `CompanyExtensionInterface`)**:
  * `gender()`: Returns `'male'` or `'female'`.
  * `initials()`: Generates name initials (e.g. `'J. D.'`).
  * `industry()`: Sector / industry name.
  * `catchPhrase()`: Core fallback business slogan / catchphrase.
* **Coordinates, Barcodes & UserAgent**:
  * `geoJsonPoint()`: RFC 7946 compliant GeoJSON Point structure (`CoordinatesExtensionInterface`).
  * `ismn()`: International Standard Music Number (ISMN) code generation (`BarcodeExtensionInterface`).
  * `gitCommitHash()`: Full 40-character or short 7-character Git commit SHA-1 (`VersionExtensionInterface`).
  * `botUserAgent()`: Web crawler and bot user agents (`UserAgentExtensionInterface`).
  * Modernized browser versions and platform tokens (Chrome 120–135, Firefox 120–135, Safari 16–17, Edge, modern OS platforms).

### Fixed & Hardened
* **`CoreRandomizer::randomKey([])`**: Fixed strict PHP `TypeError` when passing an empty array (now returns `null`).
* **Empty Enums**: `Enum::enumCase()` and `Enum::enumValue()` throw `ExtensionArgumentException('Enum has no cases')` instead of fatal property access errors when passed an empty enum.
* **Graceful `ext-intl` Handling**: `ext-intl` is now optional; falls back to `SimpleTransliterator` and skips ICU transliterator tests cleanly when `ext-intl` is not installed.
* **Deterministic Tests**: Made `ChanceStrategyTest` and `ValidStrategyTest` fully deterministic; cleaned up fragile and tautological assertions across test suites.
* **Input Validation**: Added boundary guards in `Number::randomNumber()` and `Internet::password()`.

## v0.2.0

### New / changed
- Documentation is now in the same repo, see `docs` folder
- Support for Strategy pipelines (chaining): `StrategyChain`, `CompositeStrategy`, and short-circuit strategy support.
- Template parser extracted to own implementation under `src/Template/`.
- Updated tests
- `DummyGenerator::create()` replaced `DummyGeneratorFactory` which is removed
- `PHP-DI` replaced previously used DI Container
- Core extensions refactored to use autowire via constructor injection instead of awareness traits.
- `GeneratorProxy` is used for injecting generator
- Strategy and Clock are now loaded from container
- Template parser is loaded from container

### Removed
- Awareness interfaces and traits
- `DummyGenerator` constructor params for Strategy and Clock
- `DummyGenerator` methods `clock()`, `withStrategy()`, `usedStrategy()`, `withClock()`
- `DummyGeneratorFactory`

### Dependencies
- Dev dependency updates in `composer.json`
- added dependency on PHP-DI

---
## v0.1.0

First "stable" release, with all planned extensions and support for language providers.
