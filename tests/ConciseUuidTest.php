<?php declare(strict_types=1);

/**
 * This file is part of ConciseUUID, a PHP Experts, Inc., Project.
 *
 * Copyright © 2018-2019 PHP Experts, Inc.
 * Author: Theodore R. Smith <theodore@phpexperts.pro>
 *  GPG Fingerprint: 4BF8 2613 1C34 87AC D28F  2AD8 EB24 A91D D612 5690
 *  https://www.phpexperts.pro/
 *  https://github.com/phpexpertsinc/Zuora-API-Client
 *
 * This file is licensed under the MIT License.
 */

namespace PHPExperts\ConciseUuid\Tests;

use PHPExperts\ConciseUuid\ConciseUuid;
use PHPUnit\Framework\TestCase;

/**
 * Runs tests concerning the operation of the UuidModel for generating UUIDs for child models.
 *
 * @group thorough
 */
class ConciseUuidTest extends TestCase
{
    /**
     * Makes sure that UUIDs are always 22 characters long.
     * Since we convert the UUIDs from Base16 to Base62, sometimes they are 21 and even 20
     * characters long. This test makes sure that our filling logic is operating smoothly.
     */
    public function testGenerateNewIdFunctionWillAlwaysReturn22CharactersLongString()
    {
        for ($counter = 0; $counter < 1000; $counter++) {
            $uuid = ConciseUuid::generateNewId();
            self::assertEquals(22, strlen($uuid));
        }
    }

    /**
     * Because of how the UUIDv4 algorithm is designed, it is well-neigh unlikely (extremely)
     * that a UUIDv4 would ever begin with a letter once converted to Base62. However, it would,
     * indeed, be nice to know if this ever happens over the 1,000,000s of assertions this test
     * will run over the next few years.
     */
    public function testNormalIdsStartWithANumber()
    {
        for ($a = 0; $a < 1000; ++$a) {
            $uuid = ConciseUuid::generateNewId();
            self::assertEquals(22, strlen($uuid));

            // Compat for PHPUnit v8.0.
            if (method_exists(__CLASS__, 'assertMatchesRegularExpression')) {
                self::assertMatchesRegularExpression('/^[0-9]/', $uuid[0], "A normal UUID ($uuid) did not begin with a number.");
            } else {
                self::assertRegExp('/^[0-9]/', $uuid[0], "A normal UUID ($uuid) did not begin with a number.");
            }
        }
    }

    /**
     * We have special logic in place to make the first digit of system-generated UUIDv4s
     * will always be a letter. This will help us quickly identify which content is
     * user-generated vs system-generated.
     */
    public function testSystemGeneratedIdsStartWithALetter()
    {
        for ($a = 0; $a < 1000; ++$a) {
            $uuid = ConciseUuid::generateNewId(true);
            self::assertEquals(22, strlen($uuid));
            // Compat for PHPUnit v8.0.
            if (method_exists(__CLASS__, 'assertMatchesRegularExpression')) {
                self::assertMatchesRegularExpression('/^[a-zA-Z]/', $uuid[0], "A system-generated UUID ($uuid) did not begin with a letter.");
            } else {
                self::assertRegExp('/^[a-zA-Z]/', $uuid[0], "A system-generated UUID ($uuid) did not begin with a letter.");
            }
        }
    }

    public function getUuidPairs(): array
    {
        return [
            '87b8438c-ae58-43e0-939c-8bf1256af427' => '486AisPShyuWzWL8pmsnvb',
            '4c8680c9-468a-4520-bc3a-dccafd4e9e77' => '2KOsreir4T9iXnZX7iuyVb',
            '2ddb18ef-6766-48e9-8114-d2c167782cff' => '1OWmVxQgie8AaQzXEwIDRn',
            '58c7e015-c3a6-4950-af7d-d32f1584a042' => '2hWfQ0f8HDidQv9VZr5UW2',
            '9163996d-ef47-442f-8e01-1920ca6f8b8f' => '4QLP4eNBwZZyqBBnZpk5Dr',
            '495cb4a9-587e-45ea-8afa-0670b902185f' => '2EQo0AvP1EoBDZKanNa3LD',
            '6e54fb14-1a6c-4a79-9765-7e2e28327598' => '3MBzWMqMIoNRgEnmBhBkl6',
            '40707b13-e6f7-4594-a2f4-e32b6d02b869' => '1xauajwPrEekiQMdkaTD7h',
            'd54e7fcf-509c-4e20-8430-f21569f5e1e8' => '6UVEsD109IA8uOTH3tlgj2',
            'c9b66a24-0f96-4a63-977b-ea7eec74fcf4' => '68covb2cEolI4JJ81XY4hQ',
            'f9f0780d-3507-403d-b928-863d97fe7880' => '7bcz0djLeZNSQvBjv6GP6e',
            '8e793b09-7413-4aa3-99c5-751646fa434b' => '4KqJP5ChTSgNMoQ5JMSDmN',
            '511ff512-7577-4927-9f3f-ff1e42531828' => '2T4ywitB7tDLhDu8nfkjhA',
            'd3609845-6468-4d17-812d-367f0825ab81' => '6QrWXt8lgU25ObFYv5JccT',
            '21270250-ffbe-46f5-8f11-d0b0657c1bb4' => '10YZaMREH0Ayjyg7yQxqSK',
            '0023a441-a3a3-4d9e-bd65-de3381c3a226' => '00GHs6XflJ51yCvZ4TwH4g',
            'cfa0ee6b-3c86-49fe-bc5f-47dec2240ad7' => '6JmxMefnIdvO5Vl85grvXT',
            '418af950-4eb6-452d-828e-74242f9f9a88' => '1zg0m22WXxcMXP6zlw7LMO',
            'bed6cdc7-3cce-4e97-9c3b-b97a271d0274' => '5o6hsaDEjcdWD6jXhwKqgO',
            '9515f903-10f7-4cce-8548-37d1a95f8f80' => '4XJtgxvsJeyxQ0vU2kY8Js',
            'aac6baf7-62c0-4597-a8a6-716fac7405f6' => '5CFVxzHMI75DG5YMAsklni',
            'eed3dae9-573e-49c0-a2c3-a5d9b479d3ff' => '7GezUnsgmCiKdJ5btQk1ex',
            'e1b84467-74a4-451b-954d-ab48ee6d4396' => '6rvU0BXpoabkgOMAWk9jiY',
            '1ee9a026-48ef-4592-9d87-88ceea7bc35e' => '0wKXIE87UgfjIvSPLkAHao',
            '0e0aa2a8-1a10-45e4-a67a-c97b9c5a7d19' => '0QUkgNC86JAY1A8JhVZ7iT',
            '525611bf-59cd-41f9-8809-dc2420760613' => '2VMhgV3qVcaSfO00gGGkfj',
            'a2749c28-001b-4f3e-b0d9-30c7af8c6636' => '4wY2y47SkloE5iz5Mjdvug',
            'ca016e35-ed0b-47b2-947b-557b5763f92a' => '69B6QzZvBXgJ7sVriZuhS6',
            'afc6af96-624b-479a-a6b9-ce051a30165e' => '5LgSKQbeQIylTltgDtJAfG',
            '6d241805-0f31-4499-b7bf-a39df5f975ab' => '3JwepWsXDRA7aPHLKAilcx',
            'af72a6b5-e074-4c35-a0f2-cd5275c9aafc' => '5L43Hd1cUUp4NoIFYRFwDs',
            '2cf3fc0f-b8bd-4c89-baa5-63b5d05da435' => '1MpA98gEZRxr9cEueDesWD',
            '5852a4e7-8517-492f-a78d-4a2f0df77ec1' => '2gf5mUZvgoRkenIvxAvv8r',
            'b073400f-ef4d-417f-98d6-3e6b7b374438' => '5MxJmSRuOE3w6I3Vfydn7Q',
            'a851f3dd-916c-4ee0-b70e-9afd9a990037' => '57cA81BSrtBH6FI0j6VvV9',
            '377db4c1-d0fa-4526-9aab-1d1000d57185' => '1gi1bGhJNkbuvgUscZeBdt',
            'ce5a0cd4-87fc-421a-9fdf-e39070da9ce9' => '6HNZUDkOWmetkhoYIzGZFh',
            '28dd8f8b-6546-4712-b40f-2171378e0a9e' => '1F6wgm6lneKS7oIDSUpqeM',
            '6ce81633-06a9-4fe1-85c6-faef77607787' => '3JVEaGL66IGUMmT2oUQEQp',
            'e3c2e185-27dd-4813-bd5e-7e26a60000a3' => '6vmJmTmfd6AREsE6iCi8fr',
            'b7c17258-e589-4a3a-821f-ca9d9d983a6f' => '5ak04fB1iXnWTAk9HHEMYR',
            'db16c7b0-2327-4254-b69e-436bb5a53e63' => '6fPjGYUkCn5UHYoDoMzL2R',
            '1ad0d525-97c9-4c08-ad56-59acd47e3f7c' => '0obEi3noEliUnbTQbhMrLo',
            '8c8f959f-4150-4481-8f60-c1f4d997e734' => '4HEXjMoQdlh16BXFb3idLI',
            '2b010e25-4a2b-4761-a807-1b2f2412d03e' => '1J99RUgk6G4cOwZUVm2kIQ',
            '770457c8-c680-4410-bb67-44c6e9929984' => '3ca4RKO9XQ2DYRgmq7oEzc',
            'c3e5a174-1fc2-482c-8633-b46112e770ef' => '5xeRbdeGZETMFF8Y1tcROJ',
            'b15f5937-97cc-40ed-bf59-730981e2faf8' => '5OhDPK0IFTWSa2yMAaYpui',
            'c5095f2e-917c-4f02-aede-533f724aad51' => '5znlp2cB8Bb3wxcHLB3GvB',
            'd8f082ab-871c-425a-bb7b-a3508d15c033' => '6bMFsqHpZP11oTTA7HH02F',
            '260080aa-524f-4987-9edf-7ef668f06884' => '19hwA6nCOkkRLCl36vLOVw',
            '821e3091-8d8c-472a-a94d-322af0bc63ef' => '3xWnYJUJasGjgfZdlHKnYV',
        ];
    }

    public function testCanConvertToAConciseUuid()
    {
        foreach ($this->getUuidPairs() as $uuid => $conciseUuid) {
            self::assertEquals($conciseUuid, ConciseUuid::fromUUID($uuid));
        }
    }

    public function testCanConvertToAUuid()
    {
        foreach ($this->getUuidPairs() as $uuid => $conciseUuid) {
            self::assertEquals($uuid, ConciseUuid::toUUID($conciseUuid));
        }
    }

    public function testCanConvertToAUuidWithoutDashes()
    {
        foreach ($this->getUuidPairs() as $uuid => $conciseUuid) {
            self::assertEquals(str_replace('-', '', $uuid), $uuid = ConciseUuid::toUUID($conciseUuid, true));
        }
    }

    public function testCanGenerateNewVersion4Uuids()
    {
        $uuid = ConciseUuid::generateNewUUID();
        self::assertEquals(36, strlen($uuid));
        // Compat for PHPUnit v8.0.
        if (method_exists(__CLASS__, 'assertMatchesRegularExpression')) {
            // @see https://stackoverflow.com/a/6640851/430062
            self::assertMatchesRegularExpression('/\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b/', $uuid);
        } else {
            self::assertRegExp('/\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b/', $uuid);
        }
    }

    public function testCanStripSlashesFromUuids()
    {
        $uuid = 'a60888e6-bf62-4d28-9a7e-f6bcd484c5bd';
        $expected = 'a60888e6bf624d289a7ef6bcd484c5bd';
        self::assertEquals($expected, ConciseUuid::toUUID($uuid, true));
    }

    public function testPhpVersion()
    {
        dump('PHP Version: ' . PHP_VERSION);
    }

}
