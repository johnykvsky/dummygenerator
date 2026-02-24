# Address

- `address()`: (string) '1625 Robinson Lane 64675 Billshire'
- `buildingNumber()`: (string) '59'
- `city()`: (string) 'Lake Hannahton'
- `cityPrefix()`: (string) 'New'
- `citySuffix()`: (string) 'fort'
- `country()`: (string) 'England'
- `postcode()`: (string) '56-097'
- `streetAddress()`: (string) '63828 Ian Street'
- `streetName()`: (string) 'Green Lane'
- `streetSuffix()`: (string) 'Drive'

# AnyDateTime

- `anyDate($date = null, $interval = "P10Y", $period = DatePeriodEnum)`: (\DateTimeInterface) \DateTimeImmutable('2032-10-18 12:24:16')
- `anyDateBetween($from = null, $until = null)`: (\DateTimeInterface) \DateTimeImmutable('2024-09-01 19:37:39')
- `anyTimezone($country = null)`: (string) 'Asia/Ashgabat'

# Barcode

- `ean8()`: (string) '51162092'
- `ean13()`: (string) '3324351074181'
- `isbn10()`: (string) '0278067476'
- `isbn13()`: (string) '9788741527901'

# Biased

- `biasedNumberBetween($min = 0, $max = 100, $function = "sqrt")`: (int) 68
- `linearHigh($number)`: (float) ''
- `linearLow($number)`: (float) ''
- `unbiased()`: (int) 1

# Blood

- `bloodGroup()`: (string) 'AB+'
- `bloodRh()`: (string) '-'
- `bloodType()`: (string) 'O'

# Color

- `colorName()`: (string) 'Peru'
- `hexColor()`: (string) '#e40d51'
- `hslColor()`: (string) '137,9,33'
- `hslColorAsArray()`: (array) ['116', '85', '74']
- `rgbaCssColor()`: (string) 'rgba(110,196,22,0.6)'
- `rgbColor()`: (string) '164,129,131'
- `rgbColorAsArray()`: (array) ['104', '92', '134']
- `rgbCssColor()`: (string) 'rgb(126,13,39)'
- `safeColorName()`: (string) 'aqua'
- `safeHexColor()`: (string) '#00eeaa'

# Company

- `company()`: (string) 'Carter Ltd'
- `companySuffix()`: (string) 'Ltd'
- `jobTitle()`: (string) 'molestiae'

# Coordinates

- `coordinates()`: (array) ['87.926923', '14.136779']
- `latitude($min = -90, $max = 90)`: (float) 41.721595
- `longitude($min = -180, $max = 180)`: (float) 93.788142

# Country

- `countryISOAlpha2()`: (string) 'NG'
- `countryISOAlpha3()`: (string) 'KAZ'

# DateTime

- `amPm($until = "now")`: (string) 'pm'
- `century()`: (string) 'III'
- `date($format = "Y-m-d", $until = "now")`: (string) '2019-06-15'
- `dateTime($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1986-11-18 02:57:33')
- `dateTimeAD($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1972-01-12 17:39:05')
- `dateTimeBetween($from = "-30 years", $until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2025-12-27 03:47:02')
- `dateTimeInInterval($from = "-30 years", $interval = "+5 days", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('1996-02-23 11:04:24')
- `dateTimeThisCentury($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2005-03-26 12:26:11')
- `dateTimeThisDecade($until = "now", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2023-05-25 03:07:22')
- `dateTimeThisMonth($until = "last day of this month", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-02-15 14:20:42')
- `dateTimeThisWeek($until = "sunday this week", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-02-21 06:36:02')
- `dateTimeThisYear($until = "last day of december", $timezone = null)`: (\DateTimeInterface) \DateTimeImmutable('2026-09-11 10:33:25')
- `dayOfMonth($until = "now")`: (string) '27'
- `dayOfWeek($until = "now")`: (string) 'Thursday'
- `iso8601($until = "now")`: (string) '2015-06-27T22:23:58+02:00'
- `month($until = "now")`: (string) '06'
- `monthName($until = "now")`: (string) 'September'
- `time($format = "H:i:s", $until = "now")`: (string) '14:07:15'
- `timezone()`: (string) 'Europe/Gibraltar'
- `unixTime($until = "now")`: (int) 275131021
- `year($until = "now")`: (string) '1994'

# Enum

- `enumCase($enum)`: (\UnitEnum) ''
- `enumValue($enum)`: (string|int) ''

# File

- `extension()`: (string) 'xml'
- `mimeType()`: (string) 'image/x-pict'

# Hash

- `md5()`: (string) 'cc717fc1790470aa247971bb1cd33fa4'
- `sha1()`: (string) '23280a1dc78d7841bbb2c30df85bc460daf0d145'
- `sha256()`: (string) 'ed2f0b596712ad0d7c0b800cbf58d433264b329ce24c6c655f16b07457205e7c'

# Internet

- `companyEmail()`: (string) 'michael.carter@mckenzie.biz'
- `domainName()`: (string) 'carter.org'
- `domainWord()`: (string) 'fisher'
- `email()`: (string) 'anna40@hotmail.com'
- `freeEmail()`: (string) 'mcarter@yahoo.com'
- `freeEmailDomain()`: (string) 'yahoo.com'
- `ipv4()`: (string) '172.186.181.231'
- `ipv6()`: (string) '66a8:16ad:bc38:7a59:9fa0:3a5c:5aa6:153f'
- `localIpv4()`: (string) '10.137.36.139'
- `macAddress()`: (string) 'F1:A9:23:DE:01:90'
- `password($minLength = 6, $maxLength = 20)`: (string) '.E>[4qD\\mpl9W'
- `safeEmail()`: (string) 'ksmith@example.com'
- `safeEmailDomain()`: (string) 'example.org'
- `slug($nbWords = 6, $variableNbWords = true)`: (string) 'saepe-non-eos-quis-quidem-corporis-accusantium-quia'
- `tld()`: (string) 'org'
- `url()`: (string) 'http://www.smith.org/rem-dolores-voluptas-perspiciatis-iusto'
- `userName()`: (string) 'vernon34'

# Language

- `languageCode()`: (string) 'pl'
- `locale()`: (string) 'nn_NO'

# Lorem

- `paragraph($sentenceCount = 3, $variableSentenceCount = true)`: (string) 'Iusto rerum cupiditate non. Sapiente quos nulla atque autem eos. Sit labore tenetur aut numquam in explicabo.'
- `paragraphs($paragraphCount = 3)`: (array) ['Quaerat dolorum qui blanditiis facere ad eum est. Occaecati autem quas qui non qui. Corporis perferendis eius minus amet non id.', 'Recusandae quia animi adipisci aspernatur aspernatur. Quo repellat at architecto qui est velit. Consectetur consequatur laborum eum voluptatem. Quia nihil natus necessitatibus.', 'Sit magnam eius ratione voluptatibus. Sed ab sequi sit aperiam. Libero debitis eum delectus. Earum accusamus laudantium adipisci rem voluptatem culpa hic. Culpa quo est distinctio rerum sit alias et.']
- `sentence($wordCount = 6, $variableWordCount = true)`: (string) 'Voluptas eius nostrum rerum voluptate quo.'
- `sentences($sentenceCount = 3)`: (array) ['Quas est corrupti incidunt nemo ut et hic.', 'Nihil consequatur est qui molestias.', 'Ut necessitatibus sit incidunt enim in quos voluptates.']
- `text($maxCharacters = 200)`: (string) 'Eaque debitis quisquam quisquam ut non rem est. Ipsum in est atque illo. Pariatur voluptatum architecto iste cumque iure et.'
- `word()`: (string) 'iure'
- `words($wordCount = 3)`: (array) ['consequatur', 'sapiente', 'rerum']

# Number

- `boolean($chanceOfGettingTrue = 50)`: (bool) true
- `numberBetween($min = 0, $max = 2147483647)`: (int) 790500338
- `randomDigit()`: (int) 6
- `randomDigitNot($except = 0, $retries = 1000)`: (int) 7
- `randomDigitNotZero()`: (int) 3
- `randomFloat($nbMaxDecimals = null, $min = 0, $max = null)`: (float) 1.5273736367583861E+308
- `randomNumber($nbDigits = null, $strict = false)`: (int) 26188417

# Payment

- `creditCardDetails($valid = true)`: (array) ['Visa', '4539317281203729', 'Michael Spencer', '05/26']
- `creditCardExpirationDate($inFuture = true)`: (string) '04/27'
- `creditCardNumber($type = null, $formatted = false, $separator = "-")`: (string) '2454045815509593'
- `creditCardType()`: (string) 'American Express'
- `currencyCode()`: (string) 'FJD'
- `iban($alpha2 = null, $prefix = "")`: (string) 'MD05WI4PRP696115E89VE7D5'
- `swiftBicNumber()`: (string) 'QEJFFIBV746'

# Person

- `firstName($gender = null)`: (string) 'Patricia'
- `firstNameFemale()`: (string) 'Jane'
- `firstNameMale()`: (string) 'Ian'
- `lastName()`: (string) 'Harris'
- `name($gender = null)`: (string) 'Vincent Robinson'
- `title($gender = null)`: (string) 'Mr.'
- `titleFemale()`: (string) 'Ms.'
- `titleMale()`: (string) 'Mr.'

# PhoneNumber

- `e164PhoneNumber()`: (string) '+237227781855'
- `imei()`: (string) '920621449963560'
- `phoneNumber()`: (string) '826-433-639'

# Strings

- `string($min = 3, $max = 8, $pool = null)`: (string) 'ztnsmniy'

# UserAgent

- `androidMobileToken()`: (string) 'Linux; Android 12'
- `chrome()`: (string) 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_7_8) AppleWebKit/5362 (KHTML, like Gecko) Chrome/38.0.888.0 Mobile Safari/5362'
- `edge()`: (string) 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_5_7) AppleWebKit/532.2 (KHTML, like Gecko) Chrome/95.0.4653.28 Safari/532.2 Edg/95.01145.98'
- `firefox()`: (string) 'Mozilla/5.0 (X11; Linux x86_64; rv:6.0) Gecko/20200604 Firefox/37.0'
- `internetExplorer()`: (string) 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 5.01; Trident/4.1)'
- `iosMobileToken()`: (string) 'iPhone; CPU iPhone OS 14_1 like Mac OS X'
- `linuxPlatformToken()`: (string) 'X11; Linux i686'
- `macPlatformToken()`: (string) 'Macintosh; PPC Mac OS X 10_6_7'
- `opera()`: (string) 'Opera/9.33 (X11; Linux x86_64; en-US) Presto/2.11.257 Version/11.00'
- `safari()`: (string) 'Mozilla/5.0 (iPad; CPU OS 7_2_1 like Mac OS X; sl-SI) AppleWebKit/532.26.3 (KHTML, like Gecko) Version/4.0.5 Mobile/8B119 Safari/6532.26.3'
- `userAgent()`: (string) 'Mozilla/5.0 (Windows NT 5.0) AppleWebKit/5332 (KHTML, like Gecko) Chrome/37.0.863.0 Mobile Safari/5332'
- `windowsPlatformToken()`: (string) 'Windows CE'

# Uuid

- `uuid4()`: (string) '09a3cc17-03f7-402b-ae3d-99e144cc3e0b'

# Version

- `semver($preRelease = false, $build = false)`: (string) '0.81.1'

