<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Common\Domain\Model\Identifier\Uuid;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\UniqueId
 */
class UniqueIdTest extends PHPUnit_Framework_TestCase
{
    public function test_that_generate_returns_expected_instance()
    {
        $thingId = ThingId::generate();
        $this->assertTrue(Uuid::isValid($thingId->toString()));
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame('014ea33a-d902-4025-8b53-01191406579d', $thingId->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame('014ea33a-d902-4025-8b53-01191406579d', (string) $thingId);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame(0, $thingId->compareTo($thingId));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame(0, $thingId1->compareTo($thingId2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $thingId1 = ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $thingId2 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame(1, $thingId1->compareTo($thingId2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $this->assertSame(-1, $thingId1->compareTo($thingId2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertTrue($thingId->equals($thingId));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertTrue($thingId1->equals($thingId2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertFalse($thingId->equals('014ea33a-d902-4025-8b53-01191406579d'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $thingId1 = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $thingId2 = ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $this->assertFalse($thingId1->equals($thingId2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $thingId = ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame('014ea33ad90240258b5301191406579d', $thingId->hashValue());
    }
}
