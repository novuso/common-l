<?php

namespace Novuso\Test\Common\Domain\Value\Personal;

use Novuso\Common\Domain\Value\Personal\EmailAddress;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Value\Personal\EmailAddress
 */
class EmailAddressTest extends PHPUnit_Framework_TestCase
{
    public function test_that_from_string_returns_expected_instance()
    {
        $email = EmailAddress::fromString('name@example.com');
        $this->assertSame('name@example.com', $email->toString());
    }

    public function test_that_compare_returns_zero_for_same_instance()
    {
        $email = EmailAddress::fromString('name@example.com');
        $this->assertSame(0, $email->compareTo($email));
    }

    public function test_that_compare_returns_zero_for_same_value()
    {
        $email1 = EmailAddress::fromString('name@example.com');
        $email2 = EmailAddress::fromString('name@example.com');
        $this->assertSame(0, $email1->compareTo($email2));
    }

    public function test_that_compare_returns_one_for_greater_value()
    {
        $email1 = EmailAddress::fromString('person@example.com');
        $email2 = EmailAddress::fromString('name@example.com');
        $this->assertSame(1, $email1->compareTo($email2));
    }

    public function test_that_compare_returns_neg_one_for_lesser_value()
    {
        $email1 = EmailAddress::fromString('name@example.com');
        $email2 = EmailAddress::fromString('person@example.com');
        $this->assertSame(-1, $email1->compareTo($email2));
    }

    /**
     * @expectedException Novuso\System\Exception\TypeException
     */
    public function test_that_from_string_throws_exception_for_invalid_type()
    {
        EmailAddress::fromString(null);
    }

    /**
     * @expectedException Novuso\System\Exception\DomainException
     */
    public function test_that_from_string_throws_exception_for_invalid_value()
    {
        EmailAddress::fromString('badEmail@domain');
    }
}
