<?php

namespace Novuso\Test\Common\Domain\Value;

use Novuso\Common\Domain\Value\ValueSerializer;
use Novuso\Test\Common\Doubles\FullName;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\ValueSerializer
 */
class ValueSerializerTest extends PHPUnit_Framework_TestCase
{
    public function test_that_serialize_returns_expected_string()
    {
        $expected = '[Novuso.Test.Common.Doubles.FullName]John Nickell';
        $fullName = FullName::fromParts('John', 'Nickell');
        $this->assertSame($expected, ValueSerializer::serialize($fullName));
    }

    public function test_that_deserialize_returns_expected_instance()
    {
        $string = '[Novuso.Test.Common.Doubles.FullName]John Nickell';
        $fullName = ValueSerializer::deserialize($string);
        $this->assertSame('Nickell', $fullName->last());
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_deserialize_throws_exception_for_invalid_type()
    {
        ValueSerializer::deserialize(null);
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_deserialize_throws_exception_for_invalid_state()
    {
        ValueSerializer::deserialize('foobar');
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_deserialize_throws_exception_for_invalid_class()
    {
        ValueSerializer::deserialize('[foo]bar');
    }
}
