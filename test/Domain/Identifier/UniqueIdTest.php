<?php

namespace Novuso\Test\Common\Domain\Identifier;

use Novuso\Common\Domain\Value\Identifier\Uuid;
use Novuso\Test\Common\Doubles\UserId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Identifier\UniqueId
 */
class UniqueIdTest extends PHPUnit_Framework_TestCase
{
    public function test_that_generate_returns_expected_instance()
    {
        $userId = UserId::generate();
        $this->assertTrue(Uuid::isValid($userId->toString()));
    }

    public function test_that_from_string_returns_expected_instance()
    {
        $userId = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame('014ea33a-d902-4025-8b53-01191406579d', $userId->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $userId = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame('014ea33a-d902-4025-8b53-01191406579d', (string) $userId);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $userId = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame(0, $userId->compareTo($userId));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $userId1 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $userId2 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame(0, $userId1->compareTo($userId2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $userId1 = UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $userId2 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame(1, $userId1->compareTo($userId2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $userId1 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $userId2 = UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $this->assertSame(-1, $userId1->compareTo($userId2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $userId = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertTrue($userId->equals($userId));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $userId1 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $userId2 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertTrue($userId1->equals($userId2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $userId = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertFalse($userId->equals('014ea33a-d902-4025-8b53-01191406579d'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $userId1 = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $userId2 = UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2');
        $this->assertFalse($userId1->equals($userId2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $userId = UserId::fromString('014ea33a-d902-4025-8b53-01191406579d');
        $this->assertSame('014ea33ad90240258b5301191406579d', $userId->hashValue());
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_from_string_throws_exception_for_invalid_type()
    {
        UserId::fromString(null);
    }
}
