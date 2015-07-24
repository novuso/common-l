<?php

namespace Novuso\Test\Common\Domain\Identifier;

use Novuso\Test\Common\Doubles\Username;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Identifier\StringId
 */
class StringIdTest extends PHPUnit_Framework_TestCase
{
    public function test_that_from_string_returns_expected_instance()
    {
        $username = Username::fromString('johnnickell');
        $this->assertSame('johnnickell', $username->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $username = Username::fromString('johnnickell');
        $this->assertSame('johnnickell', (string) $username);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $username = Username::fromString('johnnickell');
        $this->assertSame(0, $username->compareTo($username));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $username1 = Username::fromString('johnnickell');
        $username2 = Username::fromString('johnnickell');
        $this->assertSame(0, $username1->compareTo($username2));
    }

    public function test_that_compare_to_returns_one_for_greater_value()
    {
        $username1 = Username::fromString('leeroyjenkins');
        $username2 = Username::fromString('johnnickell');
        $this->assertSame(1, $username1->compareTo($username2));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_value()
    {
        $username1 = Username::fromString('johnnickell');
        $username2 = Username::fromString('leeroyjenkins');
        $this->assertSame(-1, $username1->compareTo($username2));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $username = Username::fromString('johnnickell');
        $this->assertTrue($username->equals($username));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $username1 = Username::fromString('johnnickell');
        $username2 = Username::fromString('johnnickell');
        $this->assertTrue($username1->equals($username2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $username = Username::fromString('johnnickell');
        $this->assertFalse($username->equals('johnnickell'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $username1 = Username::fromString('johnnickell');
        $username2 = Username::fromString('leeroyjenkins');
        $this->assertFalse($username1->equals($username2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $username = Username::fromString('johnnickell');
        $this->assertSame('johnnickell', $username->hashValue());
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_from_string_throws_exception_for_invalid_type()
    {
        Username::fromString(null);
    }
}
