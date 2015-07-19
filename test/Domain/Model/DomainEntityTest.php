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
    public function test_that_static_contructor_returns_expected_instance()
    {
        $id = UserId::generate();
        $user = User::register($id);
        $this->assertSame($id, $user->id());
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $user = User::register(UserId::generate());
        $this->assertSame(0, $user->compareTo($user));
    }

    public function test_that_compare_to_returns_zero_for_same_id()
    {
        $id = UserId::generate();
        $user1 = User::register($id);
        $user2 = User::register($id);
        $this->assertSame(0, $user1->compareTo($user2));
    }

    public function test_that_compare_to_returns_one_for_greater_id()
    {
        $user1 = User::register(UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2'));
        $user2 = User::register(UserId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $this->assertSame(1, $user1->compareTo($user2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_id()
    {
        $user1 = User::register(UserId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $user2 = User::register(UserId::fromString('014ea33b-65bc-4d53-a9f4-161c8ea937a2'));
        $this->assertSame(-1, $user1->compareTo($user2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $user = User::register(UserId::generate());
        $this->assertTrue($user->equals($user));
    }

    public function test_that_equals_returns_true_for_same_id()
    {
        $id = UserId::generate();
        $user1 = User::register($id);
        $user2 = User::register($id);
        $this->assertTrue($user1->equals($user2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $id = UserId::generate();
        $user = User::register($id);
        $this->assertFalse($user->equals($id));
    }

    public function test_that_equals_returns_false_for_unequal_id()
    {
        $user1 = User::register(UserId::generate());
        $user2 = User::register(UserId::generate());
        $this->assertFalse($user1->equals($user2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $user = User::register(UserId::fromString('014ea33a-d902-4025-8b53-01191406579d'));
        $this->assertSame('014ea33ad90240258b5301191406579d', $user->hashValue());
    }
}
