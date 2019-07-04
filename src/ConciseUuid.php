<?php

namespace PHPExperts\ConciseUuid;

use Illuminate\Database\Eloquent\Model;
use Ramsey\Uuid\Uuid;

class ConciseUuid extends Model
{
    /** @var bool Indicates if the IDs are auto-incrementing. */
    public $incrementing = false;

    /** @var string The data-type of primary-key. */
    protected $keyType = 'string';

    /**
     * Add an id to the model and save it in the database.
     *
     * @param  array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (empty($this->{$this->getKeyName()})) {
            $this->{$this->getKeyName()} = $this->generateNewId();
        }

        return parent::save($options);
    }

    public static function fromUUID(string $uuid): string
    {
        // 1. Strip the dashes.
        $uuid = str_replace('-', '', $uuid);
        // 2. Convert from hex to base62.
        $uuid = gmp_strval(gmp_init(($uuid), 16), 62);
        // 3. We pad zeros to the beginning, as the result returned by gmp_strval after base conversion
        // is not always 22 characters long.
        $uuid = str_pad($uuid, 22, '0', STR_PAD_LEFT);

        return $uuid;
    }

    public static function toUUID(string $conciseUuid): string
    {
        // 1. Convert from base62 to hex.
        $uuid = gmp_strval(gmp_init(($conciseUuid), 62), 16);

        // 2. We pad zeros to the beginning, as the result returned by gmp_strval after base conversion
        // is not always 32 characters long.
        $uuid = str_pad($uuid, 32, '0', STR_PAD_LEFT);

        // 3. Add the four dashes.
        $uuid = substr($uuid, 0, 8) . '-' . substr($uuid, 8, 4) . '-' . substr($uuid, 12, 4) . '-' .
            substr($uuid, 16, 4) . '-' . substr($uuid, 20);

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
        $uuid = Uuid::uuid4();

        // 2. Convert the UUID to a ConciseUuid:
        $uuid = self::fromUUID($uuid);

        // 3. If it is a system-generated ID, replace the first character with a random letter a-zA-Z.
        if ($systemGenerated) {
            $randomLetter = rand(0, 1) === 1 ? chr(65 + rand(0, 25)) : chr(97 + rand(0, 25));
            $uuid[0] = $randomLetter;
        }

        return $uuid;
    }
}
