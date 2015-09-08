<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Test\Common\Doubles\Domain\Model\Thing;
use Novuso\Test\Common\Doubles\Domain\Model\ThingId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\DomainEntity
 * @covers Novuso\Common\Domain\Model\Identity
 */
class DomainEntityTest extends PHPUnit_Framework_TestCase
{
    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $thing = new Thing(ThingId::generate());
        $this->assertSame(0, $thing->compareTo($thing));
    }

    public function test_that_compare_to_returns_zero_for_same_id()
    {
        $id = ThingId::generate();
        $thing1 = new Thing($id);
        $thing2 = new Thing($id);
        $this->assertSame(0, $thing1->compareTo($thing2));
    }

    public function test_that_compare_to_returns_one_for_greater_id()
    {
        $thing1 = new Thing(ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2'));
        $thing2 = new Thing(ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $this->assertSame(1, $thing1->compareTo($thing2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_id()
    {
        $thing1 = new Thing(ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $thing2 = new Thing(ThingId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2'));
        $this->assertSame(-1, $thing1->compareTo($thing2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $thing = new Thing(ThingId::generate());
        $this->assertTrue($thing->equals($thing));
    }

    public function test_that_equals_returns_true_for_same_id()
    {
        $id = ThingId::generate();
        $thing1 = new Thing($id);
        $thing2 = new Thing($id);
        $this->assertTrue($thing1->equals($thing2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $id = ThingId::generate();
        $thing = new Thing($id);
        $this->assertFalse($thing->equals($id));
    }

    public function test_that_equals_returns_false_for_unequal_id()
    {
        $thing1 = new Thing(ThingId::generate());
        $thing2 = new Thing(ThingId::generate());
        $this->assertFalse($thing1->equals($thing2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $thing = new Thing(ThingId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $this->assertSame('014ea33ad90240258b5301191406579d', $thing->hashValue());
    }
}
