<?php declare(strict_types=1);

namespace Tests\App\Models;

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
            $this->assertEquals(22, strlen($uuid));
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
            $this->assertEquals(22, strlen($uuid));
            $this->assertRegExp('/^[0-9]/', $uuid[0], "A normal UUID ($uuid) did not begin with a number.");
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
            $this->assertEquals(22, strlen($uuid));
            $this->assertRegExp('/^[a-zA-Z]/', $uuid[0], "A system-generated UUID ($uuid) did not begin with a letter.");
        }
    }
}
