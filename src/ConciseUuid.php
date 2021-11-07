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

namespace PHPExperts\ConciseUuid;

use Ramsey\Uuid\Uuid;
use Tuupola\Base62Proxy as Base62;

final class ConciseUuid
{
    private function __construct()
    {
    }

//    Kept for historical reference.
//    public static function fromUUID(string $uuid): string
//    {
//        // 1. Strip the dashes.
//        $uuid = str_replace('-', '', $uuid);
//        // 2. Convert from hex to base62.
//        $uuid = gmp_strval(gmp_init(($uuid), 16), 62);
//        // 3. We pad zeros to the beginning, as the result returned by gmp_strval after base conversion
//        // is not always 22 characters long.
//        $uuid = str_pad($uuid, 22, '0', STR_PAD_LEFT);
//
//        return $uuid;
//    }

    public static function fromUUID(string $uuid): string
    {
        // 1. Convert to Base 62.
        $conciseID = Base62::encode(Uuid::fromString($uuid)->getBytes());

        // 2. We pad zeros to the beginning, as the result returned by gmp_strval after base conversion
        // is not always 22 characters long.
        $conciseID = str_pad($conciseID, 22, '0', STR_PAD_LEFT);

        return $conciseID;
    }

//    public static function toMUID(string $uuid)
//    {
//        if (strlen($uuid) === 22) {
//            $uuid = self::toUUID($uuid, true);
//        }
//
//        $first = substr($uuid, -16);
//        $second = substr($uuid, 0, 16);
//
//        dd([
//            $first,
//            $second,
//            $first => base64_encode(hex2bin($first)),
//            $second => base64_encode(hex2bin($second)),
//            'muid' => str_replace('=', '', base64_encode(pack('H*', $first) . pack('H*', $second))),
//            'cuid' => str_replace('=', '', base64_encode(pack('H*', $uuid))),
//            'cuid' => self::fromUUID($uuid),
//        ]);
//        //                           45774962-e6f7-41f6-b940-72ef63fa1943
//        // 7FhZhUEBNoPil8w3VLl5kb -> ee51d79c-c7d1-4b31-ba63-2f707edf8915 ->
//
//        return str_replace('=', '', base64_encode(pack('H*', $first) . pack('H*', $second)));
//    }

    /**
     * @param string $conciseUuid
     * @param bool   $withoutDashes
     * @return string
     * @throws \InvalidArgumentException
     */
    public static function toUUID(string $conciseUuid, $withoutDashes = false): string
    {
        if (strlen($conciseUuid) === 36 || strlen($conciseUuid) === 32) {
            return $withoutDashes ? str_replace('-', '', $conciseUuid) : $conciseUuid;
        }

        // 1. Convert from base62 to hex.
        //$uuid = gmp_strval(gmp_init(($conciseUuid), 62), 16);
        $uuid = (string) Uuid::fromBytes(Base62::decode($conciseUuid));

        if ($withoutDashes === true) {
            // 3. Strip out the four dashes.
            $uuid = str_replace('-', '', $uuid);
        }

        return $uuid;
    }

    /**
     * Get a new version 4 (random) UUID.
     * Note: System-generated UUIDs will always begin with a letter (either upper or lowercase).
     *       Non-system-generated UUIDs may also begin with a letter, but this is extremely statistically unlikely
     *       based upon the range of numbers permitted in the UUID4 algorithm (See RFC 4122).
     *
     * @param  bool   $systemGenerated Designates whether the app itself created the ID (e.g., via a migration).
     * @return string
     * @throws \Exception if there is not enough system entropy to safely generate an ID.
     */
    public static function generateNewId(bool $systemGenerated = false): string
    {
        // 1. Generate the UUID.
        $uuid = (string) Uuid::uuid4();

        // 2. Convert the UUID to a ConciseUuid:
        $uuid = self::fromUUID($uuid);

        // 3. If it is a system-generated ID, replace the first character with a random letter a-zA-Z.
        if ($systemGenerated) {
            $randomLetter = rand(0, 1) === 1 ? chr(65 + rand(0, 25)) : chr(97 + rand(0, 25));
            $uuid[0] = $randomLetter;
        }

        return $uuid;
    }

    /**
     * Get a new version 4 (random) UUID.
     * Note: System-generated UUIDs will always begin with a letter (either upper or lowercase).
     *       Non-system-generated UUIDs may also begin with a letter, but this is extremely statistically unlikely
     *       based upon the range of numbers permitted in the UUID4 algorithm (See RFC 4122).
     *
     * @return string
     * @throws \Exception if there is not enough system entropy to safely generate an ID.
     */
    public static function generateNewUUID(): string
    {
        $uuid = (string) Uuid::uuid4();

        return $uuid;
    }
}
