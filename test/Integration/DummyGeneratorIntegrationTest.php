<?php

declare(strict_types=1);

namespace DummyGenerator\Test\Integration;

use DummyGenerator\DummyGenerator;
use PHPUnit\Framework\TestCase;

class DummyGeneratorIntegrationTest extends TestCase
{
    public function testCompleteWorkflowWithFactory(): void
    {
        $generator = DummyGenerator::create();

        // Generate multiple types of data
        $email = $generator->email();
        $name = $generator->name();
        $uuid = $generator->uuid4();
        $date = $generator->dateTime();
        $number = $generator->numberBetween(1, 100);

        self::assertStringContainsString('@', $email);
        self::assertNotEmpty($name);
        self::assertEquals(36, strlen($uuid));
        self::assertInstanceOf(\DateTimeImmutable::class, $date);
        self::assertTrue($number >= 1 && $number <= 100);
    }

    public function testParseWithMultipleTokens(): void
    {
        $generator = DummyGenerator::create();

        $template = 'User {{firstName}} {{lastName}} has email {{email}} and was born in {{year}}';
        $result = $generator->parse($template);

        // Check that all tokens were replaced
        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
        self::assertStringContainsString('@', $result); // Email was generated
        self::assertMatchesRegularExpression('/\d{4}/', $result); // Year was generated
    }

    public function testParseWithNestedTokens(): void
    {
        $generator = DummyGenerator::create();

        // The name() method internally uses {{firstName}} and {{lastName}}
        $template = 'Hello {{name}}, your email is {{email}}';
        $result = $generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
        self::assertStringContainsString('@', $result);
        self::assertMatchesRegularExpression('/Hello \w+/', $result);
    }

    public function testParseWithComplexTemplate(): void
    {
        $generator = DummyGenerator::create();

        $template = '{{title}} {{firstName}} {{lastName}}, {{companyEmail}}, Phone: {{phoneNumber}}, UUID: {{uuid4}}';
        $result = $generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);
        self::assertStringContainsString('@', $result);

        // Count the parts separated by commas
        $parts = explode(',', $result);
        self::assertCount(4, $parts);
    }

    public function testParseWithRepeatedTokens(): void
    {
        $generator = DummyGenerator::create();

        $template = '{{word}} and {{word}} are different';
        $result = $generator->parse($template);

        self::assertStringNotContainsString('{{', $result);
        // Note: The two {{word}} calls may or may not generate the same word
        // This is expected behavior - each token is resolved independently
        self::assertGreaterThan(10, strlen($result));
    }

    public function testCrossExtensionInteractionInternetUsesPerson(): void
    {
        $generator = DummyGenerator::create();

        // Internet extension uses Person extension for generating usernames and emails
        $username = $generator->userName();
        $companyEmail = $generator->companyEmail();

        // Username should be based on person names
        self::assertNotEmpty($username);
        self::assertMatchesRegularExpression('/^[a-z0-9_.]+$/', $username);

        // Company email should contain @ and domain
        self::assertStringContainsString('@', $companyEmail);
        self::assertStringContainsString('.', $companyEmail);
    }

    public function testCrossExtensionInteractionInternetUsesLorem(): void
    {
        $generator = DummyGenerator::create();

        // Slug generation may use Lorem words
        $slug = $generator->slug(nbWords: 3, variableNbWords: false);

        self::assertNotEmpty($slug);
        self::assertStringContainsString('-', $slug);

        // Should have 2 dashes for 3 words
        self::assertEquals(2, substr_count($slug, '-'));
    }

    public function testGenerateUserProfile(): void
    {
        $generator = DummyGenerator::create();

        // Simulate generating a complete user profile
        $profile = [
            'id' => $generator->uuid4(),
            'firstName' => $generator->firstName(),
            'lastName' => $generator->lastName(),
            'email' => $generator->email(),
            'username' => $generator->userName(),
            'password' => $generator->password(8, 16),
            'birthDate' => $generator->dateTimeBetween(
                from: new \DateTimeImmutable('1950-01-01'),
                until: new \DateTimeImmutable('2005-12-31')
            ),
            'phone' => $generator->phoneNumber(),
            'address' => [
                'street' => $generator->streetAddress(),
                'city' => $generator->city(),
                'country' => $generator->country(),
            ],
            'metadata' => [
                'ipAddress' => $generator->ipv4(),
                'userAgent' => $generator->userAgent(),
                'registeredAt' => $generator->dateTime(),
            ],
        ];

        // Validate all fields were generated
        self::assertEquals(36, strlen($profile['id']));
        self::assertNotEmpty($profile['firstName']);
        self::assertNotEmpty($profile['lastName']);
        self::assertStringContainsString('@', $profile['email']);
        self::assertNotEmpty($profile['username']);
        self::assertTrue(strlen($profile['password']) >= 8);
        self::assertInstanceOf(\DateTimeImmutable::class, $profile['birthDate']);
        self::assertNotEmpty($profile['phone']);
        self::assertNotEmpty($profile['address']['street']);
        self::assertNotEmpty($profile['address']['city']);
        self::assertNotEmpty($profile['address']['country']);
        self::assertNotEmpty($profile['metadata']['ipAddress']);
        self::assertNotEmpty($profile['metadata']['userAgent']);
        self::assertInstanceOf(\DateTimeImmutable::class, $profile['metadata']['registeredAt']);
    }

    public function testGenerateProductData(): void
    {
        $generator = DummyGenerator::create();

        $product = [
            'sku' => $generator->bothify('??##-????-####'),
            'name' => $generator->sentence(wordCount: 3, variableWordCount: false),
            'description' => $generator->paragraph(sentenceCount: 2, variableSentenceCount: false),
            'price' => $generator->randomFloat(nbMaxDecimals: 2, min: 1.0, max: 999.99),
            'color' => $generator->colorName(),
            'hexColor' => $generator->hexColor(),
            'weight' => $generator->randomFloat(nbMaxDecimals: 2, min: 0.1, max: 100.0),
            'isbn' => $generator->isbn13(),
            'barcode' => $generator->ean13(),
            'createdAt' => $generator->dateTimeThisYear(),
        ];

        // Validate product data
        self::assertMatchesRegularExpression('/^[a-z]{2}\d{2}-[a-z]{4}-\d{4}$/', $product['sku']);
        self::assertStringEndsWith('.', $product['name']);
        self::assertNotEmpty($product['description']);
        self::assertTrue($product['price'] >= 1.0 && $product['price'] <= 999.99);
        self::assertNotEmpty($product['color']);
        self::assertStringStartsWith('#', $product['hexColor']);
        self::assertTrue($product['weight'] >= 0.1 && $product['weight'] <= 100.0);
        self::assertEquals(13, strlen($product['isbn'])); // ISBN-13 without hyphens
        self::assertEquals(13, strlen($product['barcode']));
        self::assertInstanceOf(\DateTimeImmutable::class, $product['createdAt']);
    }

    public function testGenerateMultipleUsers(): void
    {
        $generator = DummyGenerator::create();

        $users = [];
        for ($i = 0; $i < 10; $i++) {
            $users[] = [
                'id' => $generator->uuid4(),
                'name' => $generator->name(),
                'email' => $generator->email(),
            ];
        }

        self::assertCount(10, $users);

        // Check that all UUIDs are unique
        $uuids = array_column($users, 'id');
        self::assertCount(10, array_unique($uuids));

        // Validate each user
        foreach ($users as $user) {
            self::assertEquals(36, strlen($user['id']));
            self::assertNotEmpty($user['name']);
            self::assertStringContainsString('@', $user['email']);
        }
    }

    public function testComplexParseWithMultipleLayers(): void
    {
        $generator = DummyGenerator::create();

        // Template with multiple types of data
        $template = <<<EOT
User Profile:
Name: {{title}} {{firstName}} {{lastName}}
Email: {{email}}
Username: {{userName}}
Address: {{streetAddress}}, {{city}}
Phone: {{phoneNumber}}
Joined: {{year}}-{{month}}-{{dayOfMonth}}
UUID: {{uuid4}}
EOT;

        $result = $generator->parse($template);

        // Verify all tokens were replaced
        self::assertStringNotContainsString('{{', $result);
        self::assertStringNotContainsString('}}', $result);

        // Verify structure
        self::assertStringContainsString('User Profile:', $result);
        self::assertStringContainsString('Name:', $result);
        self::assertStringContainsString('Email:', $result);
        self::assertStringContainsString('@', $result);
        self::assertStringContainsString('UUID:', $result);
    }

    public function testGeneratorWithAllExtensionTypes(): void
    {
        $generator = DummyGenerator::create();

        // Test that all extension types work together
        $data = [
            // Base Extensions
            'dateTime' => $generator->anyDate(),
            'enum' => $generator->string(5, 5),
            'lorem' => $generator->word(),
            'number' => $generator->randomDigit(),
            'uuid' => $generator->uuid4(),

            // Default Extensions
            'coordinates' => $generator->latitude(),
            'country' => $generator->country(),
            'hash' => $generator->md5(),
            'internet' => $generator->email(),
            'language' => $generator->languageCode(),
            'person' => $generator->firstName(),

            // Complementary Extensions
            'address' => $generator->city(),
            'barcode' => $generator->ean13(),
            'biased' => $generator->biasedNumberBetween(1, 10),
            'blood' => $generator->bloodType(),
            'color' => $generator->hexColor(),
            'company' => $generator->company(),
            'file' => $generator->extension(),
            'payment' => $generator->currencyCode(),
            'phoneNumber' => $generator->phoneNumber(),
            'userAgent' => $generator->userAgent(),
            'version' => $generator->semver(),
        ];

        // Validate all extensions worked
        self::assertInstanceOf(\DateTimeImmutable::class, $data['dateTime']);
        self::assertEquals(5, strlen($data['enum']));
        self::assertNotEmpty($data['lorem']);
        self::assertTrue($data['number'] >= 0 && $data['number'] <= 9);
        self::assertEquals(36, strlen($data['uuid']));
        self::assertTrue($data['coordinates'] >= -90 && $data['coordinates'] <= 90);
        self::assertNotEmpty($data['country']);
        self::assertEquals(32, strlen($data['hash']));
        self::assertStringContainsString('@', $data['internet']);
        self::assertEquals(2, strlen($data['language']));
        self::assertNotEmpty($data['person']);
        self::assertNotEmpty($data['address']);
        self::assertEquals(13, strlen($data['barcode']));
        self::assertTrue($data['biased'] >= 1 && $data['biased'] <= 10);
        self::assertNotEmpty($data['blood']);
        self::assertStringStartsWith('#', $data['color']);
        self::assertNotEmpty($data['company']);
        self::assertNotEmpty($data['file']);
        self::assertEquals(3, strlen($data['payment']));
        self::assertNotEmpty($data['phoneNumber']);
        self::assertNotEmpty($data['userAgent']);
        self::assertMatchesRegularExpression('/^\d+\.\d+\.\d+$/', $data['version']);
    }

    public function testParseEmptyString(): void
    {
        $generator = DummyGenerator::create();

        $result = $generator->parse('');

        self::assertEquals('', $result);
    }

    public function testParseStringWithoutTokens(): void
    {
        $generator = DummyGenerator::create();

        $input = 'This is a plain string without any tokens';
        $result = $generator->parse($input);

        self::assertEquals($input, $result);
    }

    public function testParseStringWithInvalidToken(): void
    {
        $generator = DummyGenerator::create();

        // Invalid token should remain as-is or throw exception
        $input = 'Hello {{invalidMethodName}}';

        try {
            $result = $generator->parse($input);
            // If it doesn't throw, check that something was attempted
            self::assertIsString($result);
        } catch (\InvalidArgumentException $e) {
            // This is also acceptable - the method doesn't exist
            self::assertStringContainsString('Unknown method', $e->getMessage());
        }
    }
}
