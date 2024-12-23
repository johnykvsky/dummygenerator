# Extensions

If you are wondering what extensions do I have in the core, what methods can I use - here is the list of all extensions, their methods and example of their output:

```text
Array
(
    [DummyGenerator\Definitions\Extension\CoordinatesExtensionInterface] => Array
        (
            [latitude()] => -12.817221
            [longitude()] => 150.065033
            [coordinates()] => ['-30.982385', '80.432536']
        )

    [DummyGenerator\Definitions\Extension\CountryExtensionInterface] => Array
        (
            [countryISOAlpha2()] => 'SI'
            [countryISOAlpha3()] => 'VIR'
        )

    [DummyGenerator\Definitions\Extension\DateTimeExtensionInterface] => Array
        (
            [dateTime()] => DateTimeImmutable('2023-12-09 03:59:23')
            [dateTimeAD()] => DateTimeImmutable('1739-04-18 05:05:17')
            [dateTimeBetween()] => DateTimeImmutable('2001-04-15 07:16:09')
            [dateTimeInInterval()] => DateTimeImmutable('1994-12-25 03:49:08')
            [dateTimeThisWeek()] => DateTimeImmutable('2024-12-26 08:18:16')
            [dateTimeThisMonth()] => DateTimeImmutable('2024-12-20 20:55:48')
            [dateTimeThisYear()] => DateTimeImmutable('2024-03-06 11:24:48')
            [dateTimeThisDecade()] => DateTimeImmutable('2021-03-10 09:59:46')
            [dateTimeThisCentury()] => DateTimeImmutable('2011-11-29 08:45:29')
            [date()] => '1982-03-28'
            [time()] => '08:01:15'
            [unixTime()] => 89575468
            [iso8601()] => '1986-05-31T16:45:39+00:00'
            [amPm()] => 'am'
            [dayOfMonth()] => '19'
            [dayOfWeek()] => 'Thursday'
            [month()] => '07'
            [monthName()] => 'June'
            [year()] => '1982'
            [century()] => 'I'
            [timezone()] => 'America/Scoresbysund'
        )

    [DummyGenerator\Definitions\Extension\HashExtensionInterface] => Array
        (
            [md5()] => 'f52b58d5180bc8e15584905564a8d198'
            [sha1()] => '06dcce7662c88872865318d77fd5a790db4b94cf'
            [sha256()] => 'fa40a54ba2e45a309a29d5d295cb4f1d96ab6863ab863ec1e89351b2c9960307'
        )

    [DummyGenerator\Definitions\Extension\LanguageExtensionInterface] => Array
        (
            [languageCode()] => 'zh'
            [locale()] => 'nds_DE'
        )

    [DummyGenerator\Definitions\Extension\LoremExtensionInterface] => Array
        (
            [word()] => 'possimus'
            [words()] => ['doloremque', 'et', 'minus']
            [sentence()] => 'Aperiam sit saepe earum autem distinctio.'
            [sentences()] => ['Autem vitae dolor repellendus ratione.', 'Quo pariatur voluptatem similique qui.', 'Nemo eos laborum fugit ea aliquid rem nam.']
            [paragraph()] => 'Est tempora sed aut cum. Qui voluptatem numquam vero modi illo repellat. Dolorem repellendus et ut non et enim. Sed corrupti hic earum qui.'
            [paragraphs()] => ['Debitis nesciunt ut et eum. Minima pariatur voluptas qui tenetur. Recusandae in quisquam et omnis sit soluta laborum esse. Quasi nam sit dolores aliquid.', 'Dolorem sed nulla recusandae quisquam. Fugit sequi voluptates est facere voluptatem natus quo.', 'Eaque eveniet iure qui laboriosam corrupti non eius. Laborum illum dolor deleniti. Autem doloremque ad dicta maxime. Non soluta qui quasi quas.']
            [text()] => 'Et non vel natus et. Delectus ut ipsa atque temporibus. Tempore voluptatum ex ut ea et quia.'
        )

    [DummyGenerator\Definitions\Extension\NumberExtensionInterface] => Array
        (
            [numberBetween()] => 1892946919
            [randomDigit()] => 6
            [randomDigitNot()] => 4
            [randomDigitNotZero()] => 3
            [randomFloat()] => 1.4413825721781513E+308
            [randomNumber()] => 3
            [boolean()] => false
        )

    [DummyGenerator\Definitions\Extension\PersonExtensionInterface] => Array
        (
            [name()] => 'John Smith'
            [firstName()] => 'Katy'
            [firstNameMale()] => 'Harry'
            [firstNameFemale()] => 'Jane'
            [lastName()] => 'White'
            [title()] => 'Miss'
            [titleMale()] => 'Prof.'
            [titleFemale()] => 'Prof.'
        )

    [DummyGenerator\Definitions\Extension\InternetExtensionInterface] => Array
        (
            [email()] => 'anna67@white.biz'
            [safeEmail()] => 'doe.katy@example.net'
            [freeEmail()] => 'rwhite@hotmail.com'
            [companyEmail()] => 'olivier75@smith.com'
            [freeEmailDomain()] => 'yahoo.com'
            [safeEmailDomain()] => 'example.net'
            [userName()] => 'rwhite'
            [password()] => '<GC4)E'
            [domainName()] => 'smith.com'
            [domainWord()] => 'smith'
            [tld()] => 'biz'
            [url()] => 'https://smith.com/eligendi-neque-officia-molestiae-vitae-corrupti-eum-illo-et.html'
            [slug()] => 'eos-quis-accusamus-et-totam-iusto-accusamus'
            [ipv4()] => '72.200.162.76'
            [ipv6()] => 'd00:a6bd:d9b2:6084:c822:3899:e7d9:a1a1'
            [localIpv4()] => '10.6.138.32'
            [macAddress()] => '35:B0:07:FB:8E:1C'
        )

    [DummyGenerator\Definitions\Extension\AddressExtensionInterface] => Array
        (
            [citySuffix()] => 'Ville'
            [streetSuffix()] => 'Street'
            [buildingNumber()] => '22'
            [city()] => 'HarryVille'
            [streetName()] => 'White Street'
            [streetAddress()] => '49 Smith Street'
            [postcode()] => '09743'
            [address()] => '72 Doe Street 31-975 JohnVille'
            [country()] => 'England'
        )

    [DummyGenerator\Definitions\Extension\BarcodeExtensionInterface] => Array
        (
            [ean13()] => '4563392474140'
            [ean8()] => '59520702'
            [isbn10()] => '2718679530'
            [isbn13()] => '9781431125746'
        )

    [DummyGenerator\Definitions\Extension\BiasedExtensionInterface] => Array
        (
            [biasedNumberBetween()] => 39
            [unbiased()] => 1
            [linearLow()] => ''
            [linearHigh()] => ''
        )

    [DummyGenerator\Definitions\Extension\BloodExtensionInterface] => Array
        (
            [bloodType()] => 'A'
            [bloodRh()] => '-'
            [bloodGroup()] => 'O+'
        )

    [DummyGenerator\Definitions\Extension\ColorExtensionInterface] => Array
        (
            [hexColor()] => '#a547a5'
            [safeHexColor()] => '#00aa44'
            [rgbColorAsArray()] => ['187', '185', '215']
            [rgbColor()] => '166,151,111'
            [rgbCssColor()] => 'rgb(166,91,20)'
            [rgbaCssColor()] => 'rgba(133,34,148,0.8)'
            [safeColorName()] => 'purple'
            [colorName()] => 'IndianRed'
            [hslColor()] => '110,42,95'
            [hslColorAsArray()] => ['328', '69', '87']
        )

    [DummyGenerator\Definitions\Extension\CompanyExtensionInterface] => Array
        (
            [company()] => 'White Ltd'
            [companySuffix()] => 'Ltd'
            [jobTitle()] => 'ullam'
        )

    [DummyGenerator\Definitions\Extension\FileExtensionInterface] => Array
        (
            [mimeType()] => 'application/zip'
            [extension()] => 'vcx'
        )

    [DummyGenerator\Definitions\Extension\PaymentExtensionInterface] => Array
        (
            [creditCardType()] => 'JCB'
            [creditCardNumber()] => '4024007124256636'
            [currencyCode()] => 'SSP'
            [creditCardExpirationDate()] => '02/27'
            [creditCardDetails()] => ['Visa', '4929271552600204', 'Katy White', '09/26']
            [iban()] => 'BE37319250251788'
            [swiftBicNumber()] => 'DRTJITWA964'
        )

    [DummyGenerator\Definitions\Extension\PhoneNumberExtensionInterface] => Array
        (
            [phoneNumber()] => '985-709-395'
            [e164PhoneNumber()] => '+968124231773'
            [imei()] => '032308138648497'
        )

    [DummyGenerator\Definitions\Extension\UserAgentExtensionInterface] => Array
        (
            [userAgent()] => 'Mozilla/5.0 (Macintosh; U; Intel Mac OS X 10_6_1) AppleWebKit/534.0 (KHTML, like Gecko) Chrome/83.0.4702.29 Safari/534.0 Edg/83.01006.15'
            [chrome()] => 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/5352 (KHTML, like Gecko) Chrome/40.0.877.0 Mobile Safari/5352'
            [edge()] => 'Mozilla/5.0 (iPhone; CPU iPhone OS 14_1 like Mac OS X) AppleWebKit/537.1 (KHTML, like Gecko) Version/15.0 EdgiOS/79.01069.79 Mobile/15E148 Safari/537.1'
            [firefox()] => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_0 rv:6.0) Gecko/20110123 Firefox/36.0'
            [safari()] => 'Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_2 like Mac OS X; nl-NL) AppleWebKit/534.50.7 (KHTML, like Gecko) Version/4.0.5 Mobile/8B118 Safari/6534.50.7'
            [opera()] => 'Opera/8.94 (Windows 98; sl-SI) Presto/2.11.187 Version/12.00'
            [internetExplorer()] => 'Mozilla/5.0 (compatible; MSIE 5.0; Windows NT 4.0; Trident/4.0)'
            [windowsPlatformToken()] => 'Windows NT 5.01'
            [macPlatformToken()] => 'Macintosh; PPC Mac OS X 10_8_0'
            [iosMobileToken()] => 'iPhone; CPU iPhone OS 15_0 like Mac OS X'
            [androidMobileToken()] => 'Linux; Android 12'
            [linuxPlatformToken()] => 'X11; Linux i686'
        )

    [DummyGenerator\Definitions\Extension\VersionExtensionInterface] => Array
        (
            [semver()] => '1.25.22'
        )

)

```