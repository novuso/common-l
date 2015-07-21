<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Test\Common\Doubles\User;
use Novuso\Test\Common\Doubles\UserId;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Model\DomainEntity
 */
class DomainEntityTest extends PHPUnit_Framework_TestCase
{
    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $user = new User(UserId::generate());
        $this->assertSame(0, $user->compareTo($user));
    }

    public function test_that_compare_to_returns_zero_for_same_id()
    {
        $id = UserId::generate();
        $user1 = new User($id);
        $user2 = new User($id);
        $this->assertSame(0, $user1->compareTo($user2));
    }

    public function test_that_compare_to_returns_one_for_greater_id()
    {
        $user1 = new User(UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2'));
        $user2 = new User(UserId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $this->assertSame(1, $user1->compareTo($user2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_id()
    {
        $user1 = new User(UserId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $user2 = new User(UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2'));
        $this->assertSame(-1, $user1->compareTo($user2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $user = new User(UserId::generate());
        $this->assertTrue($user->equals($user));
    }

    public function test_that_equals_returns_true_for_same_id()
    {
        $id = UserId::generate();
        $user1 = new User($id);
        $user2 = new User($id);
        $this->assertTrue($user1->equals($user2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $id = UserId::generate();
        $user = new User($id);
        $this->assertFalse($user->equals($id));
    }

    public function test_that_equals_returns_false_for_unequal_id()
    {
        $user1 = new User(UserId::generate());
        $user2 = new User(UserId::generate());
        $this->assertFalse($user1->equals($user2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $user = new User(UserId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $this->assertSame('014ea33ad90240258b5301191406579d', $user->hashValue());
    }
}
