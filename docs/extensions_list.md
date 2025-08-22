## Address

- `address()`: (string) '41 Smith Street 51-071 OlivierVille'
- `buildingNumber()`: (string) '91'
- `city()`: (string) 'AnnaVille'
- `citySuffix()`: (string) 'Ville'
- `country()`: (string) 'France'
- `postcode()`: (string) '22456'
- `streetAddress()`: (string) '11 White Street'
- `streetName()`: (string) 'White Street'
- `streetSuffix()`: (string) 'Street'

## Barcode

- `ean8()`: (string) '19818818'
- `ean13()`: (string) '9990698505592'
- `isbn10()`: (string) '322779358X'
- `isbn13()`: (string) '9787435227639'

## Biased

- `biasedNumberBetween($min = 0, $max = 100, $function = "sqrt")`: (int) 38
- `linearHigh($number)`: (float) ''
- `linearLow($number)`: (float) ''
- `unbiased()`: (int) 1

## Blood

- `bloodGroup()`: (string) 'AB+'
- `bloodRh()`: (string) '-'
- `bloodType()`: (string) 'B'

## Color

- `colorName()`: (string) 'LawnGreen'
- `hexColor()`: (string) '#7f229b'
- `hslColor()`: (string) '138,49,61'
- `hslColorAsArray()`: (array) ['143', '16', '13']
- `rgbaCssColor()`: (string) 'rgba(233,227,170,0.6)'
- `rgbColor()`: (string) '38,168,12'
- `rgbColorAsArray()`: (array) ['1', '135', '223']
- `rgbCssColor()`: (string) 'rgb(136,57,140)'
- `safeColorName()`: (string) 'white'
- `safeHexColor()`: (string) '#0044dd'

## Company

- `company()`: (string) 'White Ltd'
- `companySuffix()`: (string) 'Ltd'
- `jobTitle()`: (string) 'explicabo'

## Coordinates

- `coordinates()`: (array) ['53.547583', '6.547285']
- `latitude($min = -90, $max = 90)`: (float) 71.884019
- `longitude($min = -180, $max = 180)`: (float) -95.55065

## Country

- `countryISOAlpha2()`: (string) 'EG'
- `countryISOAlpha3()`: (string) 'VIR'

## DateTime

- `amPm($until = "now")`: (string) 'am'
- `century()`: (string) 'VII'
- `date($format = "Y-m-d", $until = "now")`: (string) '2014-04-27'
- `dateTime($until = "now", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('1980-04-26 23:08:44')
- `dateTimeAD($until = "now", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('1451-06-24 15:27:42')
- `dateTimeBetween($from = "-30 years", $until = "now", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('2002-08-23 21:24:35')
- `dateTimeInInterval($from = "-30 years", $interval = "+5 days", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('1995-08-23 06:12:36')
- `dateTimeThisCentury($until = "now", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('2021-03-26 09:25:20')
- `dateTimeThisDecade($until = "now", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('2022-11-01 01:56:32')
- `dateTimeThisMonth($until = "last day of this month", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('2025-08-27 09:04:59')
- `dateTimeThisWeek($until = "sunday this week", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('2025-08-22 04:22:55')
- `dateTimeThisYear($until = "last day of december", $timezone = null)`: (\DateTimeInterface) DateTimeImmutable('2025-03-01 12:30:44')
- `dayOfMonth($until = "now")`: (string) '10'
- `dayOfWeek($until = "now")`: (string) 'Wednesday'
- `iso8601($until = "now")`: (string) '1972-04-08T00:42:52+01:00'
- `month($until = "now")`: (string) '08'
- `monthName($until = "now")`: (string) 'June'
- `time($format = "H:i:s", $until = "now")`: (string) '18:02:47'
- `timezone()`: (string) 'Europe/Isle_of_Man'
- `unixTime($until = "now")`: (int) 173698572
- `year($until = "now")`: (string) '2005'

## Enum

- `enumCase($enum)`: (\UnitEnum) ''
- `enumValue($enum)`: (string|int) ''

## File

- `extension()`: (string) 'wmls'
- `mimeType()`: (string) 'image/x-portable-anymap'

## Hash

- `md5()`: (string) '55b411af40a0b536d87411449da44d40'
- `sha1()`: (string) 'f650cd9b20b03e910df2a9d614253f880b906455'
- `sha256()`: (string) '075bac23afa66806f193d95b0eac05967208d54e370538812d92020b21b10885'

## Internet

- `companyEmail()`: (string) 'john46@smith.biz'
- `domainName()`: (string) 'doe.com'
- `domainWord()`: (string) 'doe'
- `email()`: (string) 'smith.olivier@hotmail.com'
- `freeEmail()`: (string) 'doe.olivier@hotmail.com'
- `freeEmailDomain()`: (string) 'hotmail.com'
- `ipv4()`: (string) '223.205.144.116'
- `ipv6()`: (string) '3617:cc66:104d:ff47:29ef:a922:2be1:15a6'
- `localIpv4()`: (string) '10.105.45.201'
- `macAddress()`: (string) '7E:65:9F:2E:08:51'
- `password($minLength = 6, $maxLength = 20)`: (string) '_@c/$4/IXEW/g'
- `safeEmail()`: (string) 'jane59@example.org'
- `safeEmailDomain()`: (string) 'example.net'
- `slug($nbWords = 6, $variableNbWords = true)`: (string) 'suscipit-rerum-numquam-sequi-nostrum-quis-voluptatem-expedita'
- `tld()`: (string) 'biz'
- `url()`: (string) 'https://www.doe.info/itaque-assumenda-consequatur-maxime-voluptatem-hic'
- `userName()`: (string) 'doe.olivier'

## Language

- `languageCode()`: (string) 'kn'
- `locale()`: (string) 'aa_ER'

## Lorem

- `paragraph($sentenceCount = 3, $variableSentenceCount = true)`: (string) 'Quaerat facilis eveniet officiis natus aperiam assumenda non. Fugit dolores est rerum fugiat qui. Totam sed sequi quibusdam beatae optio.'
- `paragraphs($paragraphCount = 3)`: (array) ['Qui est aut est ut nesciunt. Libero error magni voluptatem nihil et. Aliquam aut iure illum asperiores recusandae. Mollitia rerum quam delectus optio.', 'Dolore ipsum beatae id accusantium. Error vitae commodi similique officia ex inventore. Sapiente accusamus autem est soluta.', 'Ab laboriosam in voluptas molestiae possimus culpa. Asperiores magnam eos quas neque officiis quidem quos fugit. Numquam voluptates aut nemo dolorem autem distinctio assumenda.']
- `sentence($wordCount = 6, $variableWordCount = true)`: (string) 'Illum dicta voluptas non.'
- `sentences($sentenceCount = 3)`: (array) ['Aut molestias neque omnis dicta maxime omnis explicabo.', 'Perspiciatis reiciendis sequi aut laboriosam rerum.', 'Modi illo distinctio et repellat similique non.']
- `text($maxCharacters = 200)`: (string) 'Aut cum ut blanditiis nisi modi voluptas. Aut sit ipsa eos veniam eveniet. Est est voluptate nobis dolores dolorum neque.'
- `word()`: (string) 'facere'
- `words($wordCount = 3)`: (array) ['voluptate', 'eos', 'quia']

## Number

- `boolean($chanceOfGettingTrue = 50)`: (bool) true
- `numberBetween($min = 0, $max = 2147483647)`: (int) 1596780117
- `randomDigit()`: (int) 2
- `randomDigitNot($except = 0, $retries = 1000)`: (int) 3
- `randomDigitNotZero()`: (int) 7
- `randomFloat($nbMaxDecimals = null, $min = 0, $max = null)`: (float) 1.1421191669331433E+308
- `randomNumber($nbDigits = null, $strict = false)`: (int) 4

## Payment

- `creditCardDetails($valid = true)`: (array) ['JCB', '3528409593573363', 'Katy Doe', '05/26']
- `creditCardExpirationDate($inFuture = true)`: (string) '10/25'
- `creditCardNumber($type = null, $formatted = false, $separator = "-")`: (string) '4532568003145716'
- `creditCardType()`: (string) 'Visa'
- `currencyCode()`: (string) 'KRW'
- `iban($alpha2 = null, $prefix = "")`: (string) 'TN9753739538801079877987'
- `swiftBicNumber()`: (string) 'CEHBROZC733'

## Person

- `firstName($gender = null)`: (string) 'John'
- `firstNameFemale()`: (string) 'Katy'
- `firstNameMale()`: (string) 'John'
- `lastName()`: (string) 'White'
- `name($gender = null)`: (string) 'Harry Smith'
- `title($gender = null)`: (string) 'Mrs.'
- `titleFemale()`: (string) 'Dr.'
- `titleMale()`: (string) 'Mr.'

## PhoneNumber

- `e164PhoneNumber()`: (string) '+995902961603'
- `imei()`: (string) '886201399236812'
- `phoneNumber()`: (string) '547-953-916'

## Strings

- `string($min = 3, $max = 8, $pool = null)`: (string) 'nzvg'

## UserAgent

- `androidMobileToken()`: (string) 'Linux; Android 10'
- `chrome()`: (string) 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5360 (KHTML, like Gecko) Chrome/37.0.895.0 Mobile Safari/5360'
- `edge()`: (string) 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/536.0 (KHTML, like Gecko) Chrome/99.0.4303.82 Safari/536.0 EdgA/99.01032.35'
- `firefox()`: (string) 'Mozilla/5.0 (X11; Linux i686; rv:7.0) Gecko/20210722 Firefox/37.0'
- `internetExplorer()`: (string) 'Mozilla/5.0 (compatible; MSIE 11.0; Windows 95; Trident/5.0)'
- `iosMobileToken()`: (string) 'iPhone; CPU iPhone OS 13_2 like Mac OS X'
- `linuxPlatformToken()`: (string) 'X11; Linux i686'
- `macPlatformToken()`: (string) 'Macintosh; U; Intel Mac OS X 10_5_3'
- `opera()`: (string) 'Opera/9.17 (Windows 95; en-US) Presto/2.9.166 Version/11.00'
- `safari()`: (string) 'Mozilla/5.0 (Windows; U; Windows NT 5.0) AppleWebKit/535.37.7 (KHTML, like Gecko) Version/5.0 Safari/535.37.7'
- `userAgent()`: (string) 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/5340 (KHTML, like Gecko) Chrome/38.0.874.0 Mobile Safari/5340'
- `windowsPlatformToken()`: (string) 'Windows CE'

## Uuid

- `uuid4()`: (string) 'dadd9b01-485e-409b-931f-5f2640181160'

## Version

- `semver($preRelease = false, $build = false)`: (string) '7.99.5'

