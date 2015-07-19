<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Specification\CompositeSpecification;

final class UsernameIsAlphaOnly extends CompositeSpecification
{
    /**
     * {@inheritdoc}
     */
    public function isSatisfiedBy($candidate)
    {
        if (!($candidate instanceof Username)) {
            return false;
        }

        if (!preg_match('/\A[a-z]+\z/ui', $candidate->toString())) {
            return false;
        }

        return true;
    }
}
