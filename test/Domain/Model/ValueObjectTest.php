<?php

namespace Novuso\Test\Common\Domain\Model;

use Novuso\Test\Common\Doubles\FullName;
use PHPUnit_Framework_TestCase;

class ValueObjectTest extends PHPUnit_Framework_TestCase
{
    public function test_that_static_constructor_returns_expected_instance()
    {
        $fullName = FullName::fromString('John Nickell');
        $this->assertSame('John Nickell', $fullName->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $fullName = FullName::fromParts('John', 'Nickell', 'R');
        $this->assertSame('John R Nickell', (string) $fullName);
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertTrue($fullName->equals($fullName));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $fullName1 = FullName::fromParts('John', 'Nickell');
        $fullName2 = FullName::fromParts('John', 'Nickell');
        $this->assertTrue($fullName1->equals($fullName2));
    }

    public function test_that_equals_returns_false_for_invalid_type()
    {
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertFalse($fullName->equals('John Nickell'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $fullName1 = FullName::fromParts('John', 'Nickell');
        $fullName2 = FullName::fromParts('John', 'Nickell', 'R');
        $this->assertFalse($fullName1->equals($fullName2));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertSame('John Nickell', $fullName->hashValue());
    }
}
