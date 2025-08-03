# Extensions

Here is list of extensions available in the `DummyGenerator`, all those are interfaces are available to be overwritten with your own implementation:


* `AddressExtensionInterface`
* `BarcodeExtensionInterface`
* `BiasedExtensionInterface`
* `BloodExtensionInterface`
* `ColorExtensionInterface`
* `CompanyExtensionInterface`
* `CoordinatesExtensionInterface`
* `CountryExtensionInterface`
* `DateTimeExtensionInterface`
* `EnumExtensionInterface`
* `FileExtensionInterface`
* `HashExtensionInterface`
* `InternetExtensionInterface`
* `LanguageExtensionInterface`
* `LoremExtensionInterface`
* `NumberExtensionInterface`
* `PaymentExtensionInterface`
* `PersonExtensionInterface`
* `PhoneNumberExtensionInterface`
* `StringsExtensionInterface`
* `TextExtensionInterface`
* `UserAgentExtensionInterface`
* `VersionExtensionInterface`

Apart from this you can overwrite calculators:

* `EanCalculatorInterfaceInterface`
* `IbanCalculatorInterfaceInterface`
* `IsbnCalculatorInterfaceInterface`
* `LuhnCalculatorInterfaceInterface`

And 3 internal "helpers":

* `RandomizerInterface`
* `ReplacerInterface`
* `TransliteratorInterface`


## Available methods
If you are wondering what methods have extensions from the core - here is the list of all extensions, their methods and example of their output:

```text
Array
(
    [DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface] => Array
        (
            [latitude()] => 39.549164
            [longitude()] => 176.893252
            [coordinates()] => ['-15.228942', '-61.134229']
        )

    [DummyGenerator\Definitions\Extension\CountryExtensionInterface] => Array
        (
            [countryISOAlpha2()] => 'PA'
            [countryISOAlpha3()] => 'MDV'
        )

    [DummyGenerator\Definitions\Extension\DateTimeExtensionInterface] => Array
        (
            [dateTime()] => DateTimeImmutable('2009-09-10 01:13:32')
            [dateTimeAD()] => DateTimeImmutable('1350-09-22 12:51:11')
            [dateTimeBetween()] => DateTimeImmutable('1997-05-22 13:48:27')
            [dateTimeInInterval()] => DateTimeImmutable('1994-12-27 08:11:54')
            [dateTimeThisWeek()] => DateTimeImmutable('2024-12-27 21:26:42')
            [dateTimeThisMonth()] => DateTimeImmutable('2024-12-26 21:00:36')
            [dateTimeThisYear()] => DateTimeImmutable('2024-12-18 04:06:04')
            [dateTimeThisDecade()] => DateTimeImmutable('2021-02-19 17:38:07')
            [dateTimeThisCentury()] => DateTimeImmutable('2020-09-02 23:22:49')
            [date()] => '1982-07-26'
            [time()] => '08:12:17'
            [unixTime()] => 263352456
            [iso8601()] => '2018-06-24T15:44:29+00:00'
            [amPm()] => 'pm'
            [dayOfMonth()] => '24'
            [dayOfWeek()] => 'Monday'
            [month()] => '05'
            [monthName()] => 'May'
            [year()] => '1982'
            [century()] => 'IV'
            [timezone()] => 'Africa/Tripoli'
        )
        
    [DummyGenerator\Definitions\Extension\EnumExtensionInterface] => Array
        (
            [value()] => ''
            [element()] => ''
        )

    [DummyGenerator\Definitions\Extension\HashExtensionInterface] => Array
        (
            [md5()] => 'f96dc4dbd773f8bcd4f2a0b8c85bd5c4'
            [sha1()] => '7e2b3aae4f3e750bce7f18ea44da2dd0bba2e41a'
            [sha256()] => '9449a575f44841e953664f1bb0c07d02b6dbabbfbd0767b8db2441f044628c02'
        )

    [DummyGenerator\Definitions\Extension\LanguageExtensionInterface] => Array
        (
            [languageCode()] => 'na'
            [locale()] => 'ak_GH'
        )

    [DummyGenerator\Definitions\Extension\LoremExtensionInterface] => Array
        (
            [word()] => 'error'
            [words()] => ['maxime', 'animi', 'maxime']
            [sentence()] => 'Eum minus id quam porro numquam.'
            [sentences()] => ['Velit quia temporibus doloremque voluptate autem totam nulla debitis.', 'Alias eos maxime excepturi laboriosam rerum maxime.', 'Nemo nihil ut dolor ullam ipsa quis.']
            [paragraph()] => 'Pariatur autem dolorem nihil et alias. Illo optio maxime labore sint atque. Non odit qui error nulla molestiae. At fuga officiis nisi ullam sed molestias.'
            [paragraphs()] => ['Vero nihil quis nulla similique nobis. Dolor voluptatem reprehenderit est sint dolores.', 'Maiores eligendi magnam rerum aut fugiat nulla omnis eligendi. Et et distinctio quo quam eum. Veritatis tenetur qui est praesentium at nobis quia. Nihil non impedit eligendi hic sit veritatis velit.', 'Incidunt vitae eos non et possimus doloremque cupiditate. Iure sunt suscipit qui pariatur dolor. Voluptatum tempore maxime quas sed doloremque ut. Voluptatem hic dignissimos temporibus.']
            [text()] => 'Enim sit labore quis adipisci ex ex aliquid ad. Nostrum et quia sed error possimus. Deserunt numquam recusandae ut. Perferendis illo adipisci voluptas.'
        )

    [DummyGenerator\Definitions\Extension\NumberExtensionInterface] => Array
        (
            [numberBetween()] => 1442344376
            [randomDigit()] => 7
            [randomDigitNot()] => 8
            [randomDigitNotZero()] => 7
            [randomFloat()] => 1.389929407704253E+308
            [randomNumber()] => 5605817
            [boolean()] => true
        )

    [DummyGenerator\Definitions\Extension\StringsExtensionInterface] => Array
        (
            [string()] => 'nvxhd'
        )

    [DummyGenerator\Definitions\Extension\PersonExtensionInterface] => Array
        (
            [name()] => 'Olivier White'
            [firstName()] => 'Anna'
            [firstNameMale()] => 'Olivier'
            [firstNameFemale()] => 'Katy'
            [lastName()] => 'White'
            [title()] => 'Dr.'
            [titleMale()] => 'Mr.'
            [titleFemale()] => 'Dr.'
        )

    [DummyGenerator\Definitions\Extension\InternetExtensionInterface] => Array
        (
            [email()] => 'jane.white@yahoo.com'
            [safeEmail()] => 'katy.white@example.net'
            [freeEmail()] => 'doe.anna@gmail.com'
            [companyEmail()] => 'pwhite@smith.com'
            [freeEmailDomain()] => 'hotmail.com'
            [safeEmailDomain()] => 'example.org'
            [userName()] => 'jane93'
            [password()] => '~u;SZ#`0eH8'
            [domainName()] => 'smith.net'
            [domainWord()] => 'smith'
            [tld()] => 'biz'
            [url()] => 'http://doe.com/enim-veniam-dolor-quia-quia-dolores'
            [slug()] => 'a-et-suscipit-doloribus'
            [ipv4()] => '27.155.88.244'
            [ipv6()] => '3a6e:1c79:569c:af66:193b:dd52:f232:f222'
            [localIpv4()] => '172.17.61.224'
            [macAddress()] => 'CB:FE:43:A2:EE:9C'
        )

    [DummyGenerator\Definitions\Extension\AddressExtensionInterface] => Array
        (
            [citySuffix()] => 'Ville'
            [streetSuffix()] => 'Street'
            [buildingNumber()] => '59'
            [city()] => 'JaneVille'
            [streetName()] => 'Smith Street'
            [streetAddress()] => '76 Smith Street'
            [postcode()] => '60-594'
            [address()] => '10 Smith Street 17031 AnnaVille'
            [country()] => 'England'
        )

    [DummyGenerator\Definitions\Extension\BarcodeExtensionInterface] => Array
        (
            [ean13()] => '8948730318830'
            [ean8()] => '82581770'
            [isbn10()] => '8725259970'
            [isbn13()] => '9789912614659'
        )

    [DummyGenerator\Definitions\Extension\BiasedExtensionInterface] => Array
        (
            [biasedNumberBetween()] => 81
            [unbiased()] => 1
            [linearLow()] => ''
            [linearHigh()] => ''
        )

    [DummyGenerator\Definitions\Extension\BloodExtensionInterface] => Array
        (
            [bloodType()] => 'B'
            [bloodRh()] => '+'
            [bloodGroup()] => 'A-'
        )

    [DummyGenerator\Definitions\Extension\ColorExtensionInterface] => Array
        (
            [hexColor()] => '#2f9e3d'
            [safeHexColor()] => '#004488'
            [rgbColorAsArray()] => ['4', '31', '77']
            [rgbColor()] => '167,2,217'
            [rgbCssColor()] => 'rgb(185,45,58)'
            [rgbaCssColor()] => 'rgba(5,233,167,0.1)'
            [safeColorName()] => 'purple'
            [colorName()] => 'Purple'
            [hslColor()] => '260,83,83'
            [hslColorAsArray()] => ['292', '83', '15']
        )

    [DummyGenerator\Definitions\Extension\CompanyExtensionInterface] => Array
        (
            [company()] => 'Smith Ltd'
            [companySuffix()] => 'Ltd'
            [jobTitle()] => 'dolor'
        )

    [DummyGenerator\Definitions\Extension\FileExtensionInterface] => Array
        (
            [mimeType()] => 'image/prs.btif'
            [extension()] => 'xbap'
        )

    [DummyGenerator\Definitions\Extension\PaymentExtensionInterface] => Array
        (
            [creditCardType()] => 'Visa'
            [creditCardNumber()] => '4556559810334164'
            [currencyCode()] => 'GEL'
            [creditCardExpirationDate()] => '11/25'
            [creditCardDetails()] => ['Visa', '4853739518755445', 'Anna Doe', '07/25']
            [iban()] => 'DK2159304878951190'
            [swiftBicNumber()] => 'DDLXEGVU562'
        )

    [DummyGenerator\Definitions\Extension\PhoneNumberExtensionInterface] => Array
        (
            [phoneNumber()] => '998-937-704'
            [e164PhoneNumber()] => '+6776482081'
            [imei()] => '261468980861150'
        )

    [DummyGenerator\Definitions\Extension\TextExtensionInterface] => Array
        (
            [realText()] => 'After hand-cuffing him, they searched his person, but nothing unusual was found about him, excepting a paper parcel, in his coat-pocket, containing what was afterwards ascertained to be seen. That.'
        )

    [DummyGenerator\Definitions\Extension\UserAgentExtensionInterface] => Array
        (
            [userAgent()] => 'Mozilla/5.0 (Windows NT 5.01; en-US; rv:1.9.2.20) Gecko/20150702 Firefox/37.0'
            [chrome()] => 'Mozilla/5.0 (Macintosh; PPC Mac OS X 10_6_4) AppleWebKit/5312 (KHTML, like Gecko) Chrome/40.0.872.0 Mobile Safari/5312'
            [edge()] => 'Mozilla/5.0 (Windows NT 5.2) AppleWebKit/533.1 (KHTML, like Gecko) Chrome/79.0.4678.71 Safari/533.1 Edg/79.01078.14'
            [firefox()] => 'Mozilla/5.0 (X11; Linux x86_64; rv:7.0) Gecko/20200323 Firefox/37.0'
            [safari()] => 'Mozilla/5.0 (Macintosh; U; PPC Mac OS X 10_7_8 rv:5.0; en-US) AppleWebKit/534.45.4 (KHTML, like Gecko) Version/4.1 Safari/534.45.4'
            [opera()] => 'Opera/8.79 (X11; Linux i686; sl-SI) Presto/2.9.297 Version/11.00'
            [internetExplorer()] => 'Mozilla/5.0 (compatible; MSIE 8.0; Windows 98; Win 9x 4.90; Trident/4.0)'
            [windowsPlatformToken()] => 'Windows NT 5.01'
            [macPlatformToken()] => 'Macintosh; U; PPC Mac OS X 10_6_3'
            [iosMobileToken()] => 'iPhone; CPU iPhone OS 14_1 like Mac OS X'
            [androidMobileToken()] => 'Linux; Android 11'
            [linuxPlatformToken()] => 'X11; Linux x86_64'
        )

    [DummyGenerator\Definitions\Extension\VersionExtensionInterface] => Array
        (
            [semver()] => '2.12.42'
        )

)
```