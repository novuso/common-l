<?php

namespace Novuso\Test\Common\Domain\Specification;

use Novuso\Test\Common\Doubles\Username;
use Novuso\Test\Common\Doubles\UsernameIsAlphaOnly;
use Novuso\Test\Common\Doubles\UsernameIsUnique;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Specification\AndSpecification
 * @covers Novuso\Common\Domain\Specification\CompositeSpecification
 * @covers Novuso\Common\Domain\Specification\NotSpecification
 * @covers Novuso\Common\Domain\Specification\OrSpecification
 */
class SpecificationTest extends PHPUnit_Framework_TestCase
{
    public function test_that_and_spec_returns_true_when_both_valid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $usernameIsValid = $usernameIsUnique->andIf($usernameIsAlphaOnly);

        $username = Username::fromString('georgejones');

        $this->assertTrue($usernameIsValid->isSatisfiedBy($username));
    }

    public function test_that_and_spec_returns_false_when_one_invalid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $usernameIsValid = $usernameIsUnique->andIf($usernameIsAlphaOnly);

        $username = Username::fromString('johnnickell');

        $this->assertFalse($usernameIsValid->isSatisfiedBy($username));
    }

    public function test_that_or_spec_returns_true_when_either_valid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $usernameIsValid = $usernameIsUnique->orIf($usernameIsAlphaOnly);

        $username = Username::fromString('johnnickell');

        $this->assertTrue($usernameIsValid->isSatisfiedBy($username));
    }

    public function test_that_or_spec_returns_false_when_both_invalid()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $usernameIsValid = $usernameIsUnique->orIf($usernameIsAlphaOnly);

        $username = Username::fromString('admin123');

        $this->assertFalse($usernameIsValid->isSatisfiedBy($username));
    }

    public function test_that_not_spec_flips_meaning_of_a_spec()
    {
        $usernameIsUnique = new UsernameIsUnique();
        $usernameIsAlphaOnly = new UsernameIsAlphaOnly();
        $usernameIsValid = $usernameIsUnique->andIf($usernameIsAlphaOnly->not());

        $username = Username::fromString('user2015');

        $this->assertTrue($usernameIsValid->isSatisfiedBy($username));
    }
}
