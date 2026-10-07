# Generators and Extensions

This is the list of all available items to generate, grouped by extension interface.

# Address

- `address()`: (string) '68 Robinson Lane 93472 Robinsonside'
- `buildingNumber()`: (string) '41570'
- `city()`: (string) 'East James'
- `cityPrefix()`: (string) 'Lake'
- `citySuffix()`: (string) 'port'
- `country()`: (string) 'England'
- `postcode()`: (string) '63422'
- `streetAddress()`: (string) '230 Paul Street'
- `streetName()`: (string) 'Green Lane'
- `streetSuffix()`: (string) 'Square'

# AnyDateTime

- `anyDate($date = null, $interval = "P10Y", $period = DatePeriodEnum)`: (\DateTimeInterface) \DateTimeImmutable('2034-08-10 18:46:28')
- `anyDateBetween($from = null, $until = null)`: (\DateTimeInterface) \DateTimeImmutable('2021-10-09 02:25:31')
- `anyTimezone($country = null)`: (string) 'Asia/Jayapura'

# Barcode

- `ean8()`: (string) '48455541'
- `ean13()`: (string) '7051542409694'
- `isbn10()`: (string) '2283083656'
- `isbn13()`: (string) '9792368326403'
- `ismn()`: (string) '9790197164913'

# Biased

- `biasedNumberBetween($min = 0, $max = 100, $function = "sqrt")`: (int) 59
- `linearHigh($number)`: (float) ''
- `linearLow($number)`: (float) ''
- `unbiased()`: (int) 1

# Blood

- `bloodGroup()`: (string) 'B+'
- `bloodRh()`: (string) '-'
- `bloodType()`: (string) 'O'

# Color

- `colorName()`: (string) 'LightBlue'
- `hexColor()`: (string) '#3a8435'
- `hslColor()`: (string) '103,79,98'
- `hslColorAsArray()`: (array) [77, 95, 69]
- `rgbaCssColor()`: (string) 'rgba(44,78,39,0.1)'
- `rgbColor()`: (string) '56,210,166'
- `rgbColorAsArray()`: (array) [69, 125, 59]
- `rgbCssColor()`: (string) 'rgb(180,236,108)'
- `safeColorName()`: (string) 'gray'
- `safeHexColor()`: (string) '#000022'

# Company

- `catchPhrase()`: (string) 'Networked explicit leverage'
- `company()`: (string) 'Smith Ltd'
- `companySuffix()`: (string) 'Ltd'
- `industry()`: (string) 'Technology'
- `jobTitle()`: (string) 'voluptatem'

# Coordinates

- `coordinates()`: (array) ['latitude' => -67.837682, 'longitude' => 143.98432]
- `geoJsonPoint()`: (array) ['type' => 'Point', 'coordinates' => [37.456016, -62.635876]]
- `latitude($min = -90, $max = 90)`: (float) 50.606067
- `longitude($min = -180, $max = 180)`: (float) 12.099141

# Country

- `countryISOAlpha2()`: (string) 'CN'
- `countryISOAlpha3()`: (string) 'CHL'

# DateTime

- `amPm($until = "now")`: (string) 'pm'
- `century()`: (string) 'XXI'
- `cronExpression()`: (string) '0 0 * * *'
- `date($format = "Y-m-d", $until = "now")`: (string) '1997-01-16'
- `dateTime($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1976-08-13 15:47:50')
- `dateTimeAD($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1618-01-15 11:06:52')
- `dateTimeBetween($from = "-30 years", $until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1997-08-14 19:50:10')
- `dateTimeFuture($until = "+30 days", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-10-16 16:48:46')
- `dateTimeInInterval($from = "-30 years", $interval = "+5 days", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1996-10-05 20:11:37')
- `dateTimePast($from = "-30 days", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-09-19 06:49:25')
- `dateTimeThisCentury($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2012-11-19 02:37:26')
- `dateTimeThisDecade($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2024-09-05 19:22:05')
- `dateTimeThisMonth($until = "last day of this month", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-10-06 18:28:37')
- `dateTimeThisWeek($until = "sunday this week", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-10-09 16:37:15')
- `dateTimeThisYear($until = "last day of december", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-11-12 20:24:48')
- `dayOfMonth($until = "now")`: (string) '17'
- `dayOfWeek($until = "now")`: (string) 'Thursday'
- `iso8601($until = "now")`: (string) '2024-05-02T14:33:04+00:00'
- `month($until = "now")`: (string) '05'
- `monthName($until = "now")`: (string) 'September'
- `time($format = "H:i:s", $until = "now")`: (string) '17:23:16'
- `timezone()`: (string) 'Pacific/Majuro'
- `unixTime($until = "now")`: (int) 1609784474
- `year($until = "now")`: (string) '1993'

# Enum

- `enumCase($enum)`: (\UnitEnum) ''
- `enumValue($enum)`: (string|int) ''

# File

- `extension()`: (string) 'tra'
- `fileName($extension = null)`: (string) 'config.uvs'
- `fileSize($minBytes = 1024, $maxBytes = 10485760, $formatted = false)`: (string|int) 8819064
- `mimeType()`: (string) 'application/x-dgc-compressed'
- `mimeTypeForExtension($extension = "pdf")`: (string) 'application/pdf'

# Hash

- `base64($byteLength = 32)`: (string) '9ye9+rf5TxF0qJymQhQFSOUKUAwpunp6L7FHxOkkQuY='
- `base64Url($byteLength = 32)`: (string) 'DxLMIeYkMComdNkr86DG3M-ayC6NcOXRjB565Ny0Vfg'
- `md5()`: (string) '0a5cbb84558284f965b3e260500861d8'
- `sha1()`: (string) '3caa54eace103a6f1c60b3e784ead99d676f76e8'
- `sha256()`: (string) '551c5d44e340c4ed6e9ec8b84428aa6e2bbe5ce897be7c1b9600c749d940e0d9'
- `sha512()`: (string) '8184552095d02ef3cc3629961f71681152f9381d5b93437d84faf30975d3366bc9486f20599490521d621b4d0cb747426a29c263c8696b51a75ffaf3585f1b42'

# Internet

- `companyEmail()`: (string) 'mckenzie.james@mckenzie.com'
- `domainName()`: (string) 'morgan.org'
- `domainWord()`: (string) 'harris'
- `email()`: (string) 'doe.katy@gmail.com'
- `freeEmail()`: (string) 'paul56@yahoo.com'
- `freeEmailDomain()`: (string) 'gmail.com'
- `httpMethod()`: (string) 'POST'
- `httpStatusCode($category = null)`: (int) 501
- `ipv4()`: (string) '89.205.60.19'
- `ipv4Cidr()`: (string) '65.177.9.232/25'
- `ipv6()`: (string) 'f7eb:e93f:b67d:416e:ae7:4799:398c:78d2'
- `jwt()`: (string) 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiI2NDMzOSIsIm5hbWUiOiJqYW1lczI2IiwiaWF0IjoxNzkxMjE5Nzk2LCJleHAiOjE3OTEyMjMzOTZ9.Ja8DD2TVKcWmNpmdRISy-wu39B8nULN663wChPnsSSk'
- `localIpv4()`: (string) '172.25.231.194'
- `macAddress()`: (string) '55:0B:FD:22:12:4B'
- `password($minLength = 6, $maxLength = 20)`: (string) '18t9Ed'
- `port($min = 1024, $max = 65535)`: (int) 17926
- `publicIpv4()`: (string) '29.62.181.172'
- `safeEmail()`: (string) 'vernon47@example.com'
- `safeEmailDomain()`: (string) 'example.net'
- `slug($nbWords = 6, $variableNbWords = true)`: (string) 'officia-enim-quas-id-aut-quia-accusamus'
- `tld()`: (string) 'info'
- `url()`: (string) 'http://walker.com/'
- `urlPath($segments = 2)`: (string) '/morgan/adams'
- `userName()`: (string) 'garett81'

# Language

- `languageCode()`: (string) 'ru'
- `locale()`: (string) 'da_DK'

# Lorem

- `paragraph($sentenceCount = 3, $variableSentenceCount = true)`: (string) 'Accusantium reiciendis debitis qui modi nesciunt est. Nemo minima aperiam esse fuga. Provident deserunt eveniet labore a illum suscipit tenetur. Vel voluptatem eveniet vel et est.'
- `paragraphs($paragraphCount = 3)`: (array) ['Aut vel officiis molestias. Voluptates qui vero explicabo et tempore ut. Quo et mollitia cum at doloremque provident. Est distinctio sint omnis aut.', 'Occaecati necessitatibus dolores qui voluptatem eos est. Non dolore id consectetur consequatur facilis sed. Facere magnam harum id tempora quia illum.', 'Ex sit assumenda sapiente iusto. Maiores rerum officia sapiente ut. Atque id et iure ut et. Eveniet qui reprehenderit exercitationem ducimus. Qui sit atque neque commodi impedit.']
- `sentence($wordCount = 6, $variableWordCount = true)`: (string) 'Minus fuga qui et et.'
- `sentences($sentenceCount = 3)`: (array) ['Explicabo voluptatem natus eius doloremque nihil fugiat possimus.', 'Aut ullam fuga est dicta ducimus.', 'Accusamus inventore est aut quia natus voluptatem est.']
- `text($maxCharacters = 200)`: (string) 'Ullam ut impedit magnam sunt rerum dicta sint. Voluptatem a maiores minus fugit. Non delectus accusamus et et.'
- `word()`: (string) 'fugit'
- `words($wordCount = 3)`: (array) ['repellendus', 'molestias', 'itaque']

# Number

- `binary($length = 8)`: (string) '00010110'
- `boolean($chanceOfGettingTrue = 50)`: (bool) false
- `hexadecimal($nbDigits = 6)`: (string) '289c2b'
- `numberBetween($min = 0, $max = 2147483647)`: (int) 2048449156
- `percentage($decimals = 0, $min = 0, $max = 100)`: (int|float) 8
- `randomDigit()`: (int) 9
- `randomDigitNot($except = 0, $retries = 1000)`: (int) 7
- `randomDigitNotZero()`: (int) 3
- `randomFloat($nbMaxDecimals = null, $min = 0, $max = null)`: (float) 1.309722507840581E+308
- `randomNumber($nbDigits = null, $strict = false)`: (int) 60

# Payment

- `creditCardCvv($cardType = null)`: (string) '666'
- `creditCardDetails($valid = true)`: (array) ['type' => 'Visa', 'number' => '4556342849751215', 'name' => 'Kevin White', 'expirationDate' => '04/28', 'cvv' => '955']
- `creditCardExpirationDate($inFuture = true)`: (string) '11/26'
- `creditCardNumber($type = null, $formatted = false, $separator = "-")`: (string) '3528234709430468'
- `creditCardType()`: (string) 'MasterCard'
- `currencyCode()`: (string) 'CNY'
- `currencyName()`: (string) 'Euro'
- `currencySymbol()`: (string) '₺'
- `iban($alpha2 = null, $prefix = "")`: (string) 'GE06MJ8231375090309716'
- `price($min = 0, $max = 1000, $decimals = 2)`: (float) 92.23
- `swiftBicNumber()`: (string) 'KYBUPKRP672'

# Person

- `firstName($gender = null)`: (string) 'Mary'
- `firstNameFemale()`: (string) 'Daisy'
- `firstNameMale()`: (string) 'Bill'
- `gender()`: (string) 'male'
- `initials($length = 2)`: (string) 'U. I.'
- `lastName()`: (string) 'Fisher'
- `name($gender = null)`: (string) 'Vernon Adams'
- `title($gender = null)`: (string) 'Dr.'
- `titleFemale()`: (string) 'Miss'
- `titleMale()`: (string) 'Prof.'

# PhoneNumber

- `e164PhoneNumber()`: (string) '+34422795073'
- `imei()`: (string) '412380351741440'
- `phoneNumber()`: (string) '781-343-153'

# Strings

- `string($min = 3, $max = 8, $pool = null)`: (string) 'eth'

# UserAgent

- `androidMobileToken()`: (string) 'Linux; Android 15'
- `botUserAgent()`: (string) 'Twitterbot/1.0'
- `chrome()`: (string) 'Mozilla/5.0 (Macintosh; Intel; Mac OS X 14_4_1 Mac OS X 10_8_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/135.0.6402.16 Safari/537.36'
- `edge()`: (string) 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/128.0.6021.86 Safari/537.36 Edg/128.02216.68'
- `firefox()`: (string) 'Mozilla/5.0 (X11; Linux i686; rv:120.0) Gecko/20100101 Firefox/120.0'
- `internetExplorer()`: (string) 'Mozilla/5.0 (compatible; MSIE 6.0; Windows NT 10.0; Win64; x64; Trident/5.1)'
- `iosMobileToken()`: (string) 'iPhone; CPU iPhone OS 14_1 like Mac OS X'
- `linuxPlatformToken()`: (string) 'X11; Linux i686'
- `macPlatformToken()`: (string) 'Macintosh; Intel Mac OS X 10_5_1'
- `opera()`: (string) 'Opera/8.66 (X11; Linux i686; sl-SI) Presto/2.10.231 Version/10.00'
- `safari()`: (string) 'Mozilla/5.0 (iPad; CPU OS 17_5 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.3 Mobile/15E148 Safari/604.1'
- `userAgent()`: (string) 'Mozilla/5.0 (iPhone; CPU iPhone OS 13_2 like Mac OS X) AppleWebKit/537.36 (KHTML, like Gecko) Version/17.0 EdgiOS/135.02588.57 Mobile/15E148 Safari/537.36'
- `windowsPlatformToken()`: (string) 'Windows NT 10.0; Win64; x64'

# Uuid

- `nilUuid()`: (string) '00000000-0000-0000-0000-000000000000'
- `ulid($dateTime = null)`: (string) '01M46GA297K1YDHR39S1YNTS6V'
- `uuid4()`: (string) '98075de4-0171-4efe-82d3-6615da53421f'
- `uuid7($dateTime = null)`: (string) '01a10d05-0927-70d5-9060-f01b95a40a79'

# Version

- `gitCommitHash($short = false)`: (string) '265693a38870506ad5526179ecc54fc9128163ea'
- `semver($preRelease = false, $build = false)`: (string) '1.96.52'

