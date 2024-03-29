<?php

namespace Novuso\Test\Common\Doubles\Domain\Specification;

use Novuso\Common\Domain\Specification\CompositeSpecification;

final class UsernameIsUnique extends CompositeSpecification
{
    protected $usernames = [
        'johnnickell',
        'leeroyjenkins',
        'joesmith',
        'admin123'
    ];

    public function isSatisfiedBy($candidate)
    {
        if (!($candidate instanceof Username)) {
            return false;
        }

        if (in_array($candidate->toString(), $this->usernames)) {
            return false;
        }

        return true;
    }
}
