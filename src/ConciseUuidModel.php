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

use Illuminate\Database\Eloquent\Model;

abstract class ConciseUuidModel extends Model
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
    /**
     * @param array $options
     * @return bool
     */
    public function save(array $options = [])
    {
        if (empty($this->{$this->getKeyName()})) {
            while (true) {
                try {
                    $this->{$this->getKeyName()} = ConciseUuid::generateNewId();
                    break;
                } catch (\Exception $e) {
                    // Work around https://github.com/tuupola/base62/issues/17
                }
            }
        }

        return parent::save($options);
    }
}
