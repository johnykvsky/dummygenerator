## Address

- `address()`: (string) '71 Doe Street 29365 HarryVille'
- `buildingNumber()`: (string) '21'
- `city()`: (string) 'JaneVille'
- `citySuffix()`: (string) 'Ville'
- `country()`: (string) 'England'
- `postcode()`: (string) '42-041'
- `streetAddress()`: (string) '66 Doe Street'
- `streetName()`: (string) 'White Street'
- `streetSuffix()`: (string) 'Street'

## Barcode

- `ean8()`: (string) '24617208'
- `ean13()`: (string) '5310406836901'
- `isbn10()`: (string) '1044938919'
- `isbn13()`: (string) '9793857430090'

## Biased

- `biasedNumberBetween($min, $max = 100, $function = sqrt)`: (int) 91
- `linearHigh($number)`: (float) ''
- `linearLow($number)`: (float) ''
- `unbiased()`: (int) 1

## Blood

- `bloodGroup()`: (string) 'A+'
- `bloodRh()`: (string) '+'
- `bloodType()`: (string) 'O'

## Color

- `colorName()`: (string) 'GhostWhite'
- `hexColor()`: (string) '#d8a218'
- `hslColor()`: (string) '160,21,0'
- `hslColorAsArray()`: (array) ['149', '67', '39']
- `rgbaCssColor()`: (string) 'rgba(188,9,193,0)'
- `rgbColor()`: (string) '34,160,203'
- `rgbColorAsArray()`: (array) ['206', '194', '109']
- `rgbCssColor()`: (string) 'rgb(66,240,40)'
- `safeColorName()`: (string) 'green'
- `safeHexColor()`: (string) '#00ddcc'

## Company

- `company()`: (string) 'White Ltd'
- `companySuffix()`: (string) 'Ltd'
- `jobTitle()`: (string) 'veritatis'

## Coordinates

- `coordinates()`: (array) ['-85.031953', '-176.155743']
- `latitude($min = -90, $max = 90)`: (float) 10.23914
- `longitude($min = -180, $max = 180)`: (float) 95.153676

## Country

- `countryISOAlpha2()`: (string) 'GM'
- `countryISOAlpha3()`: (string) 'KNA'

## DateTime

- `amPm($until = now)`: (string) 'am'
- `century()`: (string) 'VII'
- `date($format = Y-m-d, $until = now)`: (string) '1991-12-25'
- `dateTime($until = now, $timezone)`: (DateTimeInterface) DateTimeImmutable('1994-08-28 16:22:50')
- `dateTimeAD($until = now, $timezone)`: (DateTimeInterface) DateTimeImmutable('2003-09-24 00:38:44')
- `dateTimeBetween($from = -30 years, $until = now, $timezone)`: (DateTimeInterface) DateTimeImmutable('2023-01-16 10:11:58')
- `dateTimeInInterval($from = -30 years, $interval = +5 days, $timezone)`: (DateTimeInterface) DateTimeImmutable('1995-08-13 14:23:55')
- `dateTimeThisCentury($until = now, $timezone)`: (DateTimeInterface) DateTimeImmutable('2019-10-30 01:49:12')
- `dateTimeThisDecade($until = now, $timezone)`: (DateTimeInterface) DateTimeImmutable('2020-08-12 02:19:32')
- `dateTimeThisMonth($until = last day of this month, $timezone)`: (DateTimeInterface) DateTimeImmutable('2025-08-14 19:22:40')
- `dateTimeThisWeek($until = sunday this week, $timezone)`: (DateTimeInterface) DateTimeImmutable('2025-08-11 13:42:09')
- `dateTimeThisYear($until = last day of december, $timezone)`: (DateTimeInterface) DateTimeImmutable('2025-07-27 10:31:06')
- `dayOfMonth($until = now)`: (string) '26'
- `dayOfWeek($until = now)`: (string) 'Monday'
- `iso8601($until = now)`: (string) '1972-12-01T18:02:42+01:00'
- `month($until = now)`: (string) '11'
- `monthName($until = now)`: (string) 'September'
- `time($format = H:i:s, $until = now)`: (string) '15:11:49'
- `timezone()`: (string) 'America/Indiana/Indianapolis'
- `unixTime($until = now)`: (int) 1183854869
- `year($until = now)`: (string) '1980'

## Enum

- `enumElement($enum)`: (UnitEnum) ''
- `enumValue($enum)`: (string|int) ''

## File

- `extension()`: (string) 'sxi'
- `mimeType()`: (string) 'application/wspolicy+xml'

## Hash

- `md5()`: (string) 'f1318ff66c1fa1286ab4e845070de81a'
- `sha1()`: (string) 'bf9ba450ed1d07c83f8971873296470f6262472f'
- `sha256()`: (string) '42dea05e3f37e18a906051714b7b2c385eae367ff17eb82331c4a7c8229fc5c3'

## Internet

- `companyEmail()`: (string) 'osmith@white.com'
- `domainName()`: (string) 'white.com'
- `domainWord()`: (string) 'smith'
- `email()`: (string) 'mwhite@yahoo.com'
- `freeEmail()`: (string) 'john26@gmail.com'
- `freeEmailDomain()`: (string) 'gmail.com'
- `ipv4()`: (string) '61.126.122.213'
- `ipv6()`: (string) 'feb8:6571:85ae:21af:61ac:1331:eb71:796a'
- `localIpv4()`: (string) '10.50.126.253'
- `macAddress()`: (string) '62:15:07:99:F1:6D'
- `password($minLength = 6, $maxLength = 20)`: (string) 'Nuf"qDDJu;a4+'
- `safeEmail()`: (string) 'harry.doe@example.net'
- `safeEmailDomain()`: (string) 'example.org'
- `slug($nbWords = 6, $variableNbWords = 1)`: (string) 'quo-et-et-tempora-optio-omnis-aut'
- `tld()`: (string) 'com'
- `url()`: (string) 'http://smith.com/nobis-ullam-et-laudantium-voluptas-distinctio-consequatur-et-doloremque.html'
- `userName()`: (string) 'jane.smith'

## Language

- `languageCode()`: (string) 'hz'
- `locale()`: (string) 'ha_NE'

## Lorem

- `paragraph($sentenceCount = 3, $variableSentenceCount = 1)`: (string) 'Expedita voluptas aut inventore delectus explicabo dolor ea accusamus. Voluptates nemo ipsa ipsam pariatur laborum aut quidem. Optio possimus eius at voluptatibus voluptatem nesciunt soluta.'
- `paragraphs($paragraphCount = 3)`: (array) ['Dolorem est atque id perferendis accusantium dolores nulla. Quaerat enim in illo ut accusamus officia molestiae. Iste aspernatur molestiae porro iste quaerat et.', 'Earum aperiam praesentium cumque. Tempore quaerat occaecati eligendi et veritatis illum dolorem aut. Cupiditate rerum animi soluta rem aut et asperiores. Eos deleniti voluptas harum cum.', 'Qui alias et accusamus nesciunt quo cum magnam. Magni laborum quae aspernatur doloribus. Libero inventore magni tenetur aut. Ea voluptas quaerat aut beatae. Corrupti non voluptatem ut dicta expedita.']
- `sentence($wordCount = 6, $variableWordCount = 1)`: (string) 'Aut fuga quia aut esse quo possimus quo.'
- `sentences($sentenceCount = 3)`: (array) ['Voluptatem labore aspernatur est enim harum repudiandae adipisci.', 'Repudiandae quos delectus et vitae illo.', 'Quo vel occaecati maxime minus doloribus voluptate.']
- `text($maxCharacters = 200)`: (string) 'Placeat tempora velit sit sint explicabo. Magni quasi ipsa porro necessitatibus quo consequuntur. Consequatur eum illo neque.'
- `word()`: (string) 'autem'
- `words($wordCount = 3)`: (array) ['natus', 'quia', 'sed']

## Number

- `boolean($chanceOfGettingTrue = 50)`: (bool) true
- `numberBetween($min, $max = 2147483647)`: (int) 1650300026
- `randomDigit()`: (int) 2
- `randomDigitNot($except, $retries = 1000)`: (int) 3
- `randomDigitNotZero()`: (int) 6
- `randomFloat($nbMaxDecimals, $min, $max)`: (float) 2.524092295214392E+307
- `randomNumber($nbDigits, $strict)`: (int) 23

## Payment

- `creditCardDetails($valid = 1)`: (array) ['American Express', '343860788338161', 'Anna White', '11/26']
- `creditCardExpirationDate($inFuture = 1)`: (string) '08/27'
- `creditCardNumber($type, $formatted, $separator = -)`: (string) '2720645265800943'
- `creditCardType()`: (string) 'Visa'
- `currencyCode()`: (string) 'TZS'
- `iban($alpha2, $prefix)`: (string) 'BG96OZSN403237J6N5SP8N'
- `swiftBicNumber()`: (string) 'SLBCBGLO804'

## Person

- `firstName($gender)`: (string) 'John'
- `firstNameFemale()`: (string) 'Anna'
- `firstNameMale()`: (string) 'John'
- `lastName()`: (string) 'White'
- `name($gender)`: (string) 'Harry White'
- `title($gender)`: (string) 'Dr.'
- `titleFemale()`: (string) 'Miss'
- `titleMale()`: (string) 'Prof.'

## PhoneNumber

- `e164PhoneNumber()`: (string) '+224112837852'
- `imei()`: (string) '031671781126850'
- `phoneNumber()`: (string) '198-573-391'

## Strings

- `string($min = 3, $max = 8, $pool)`: (string) 'fyx'

## UserAgent

- `androidMobileToken()`: (string) 'Linux; Android 9'
- `chrome()`: (string) 'Mozilla/5.0 (X11; Linux i686) AppleWebKit/5311 (KHTML, like Gecko) Chrome/37.0.861.0 Mobile Safari/5311'
- `edge()`: (string) 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_3) AppleWebKit/532.0 (KHTML, like Gecko) Chrome/87.0.4398.29 Safari/532.0 Edg/87.01034.56'
- `firefox()`: (string) 'Mozilla/5.0 (Windows NT 5.1; sl-SI; rv:1.9.2.20) Gecko/20181106 Firefox/36.0'
- `internetExplorer()`: (string) 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)'
- `iosMobileToken()`: (string) 'iPhone; CPU iPhone OS 15_1 like Mac OS X'
- `linuxPlatformToken()`: (string) 'X11; Linux i686'
- `macPlatformToken()`: (string) 'Macintosh; Intel Mac OS X 10_5_1'
- `opera()`: (string) 'Opera/8.88 (X11; Linux x86_64; en-US) Presto/2.11.216 Version/10.00'
- `safari()`: (string) 'Mozilla/5.0 (iPad; CPU OS 7_1_2 like Mac OS X; sl-SI) AppleWebKit/532.36.3 (KHTML, like Gecko) Version/3.0.5 Mobile/8B119 Safari/6532.36.3'
- `userAgent()`: (string) 'Mozilla/5.0 (Windows; U; Windows 95) AppleWebKit/534.5.5 (KHTML, like Gecko) Version/5.1 Safari/534.5.5'
- `windowsPlatformToken()`: (string) 'Windows 98; Win 9x 4.90'

## Uuid

- `uuid4()`: (string) 'ae01d225-badc-443b-9167-f19534707405'

## Version

- `semver($preRelease, $build)`: (string) '1.12.65'

