<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Model\DomainEntity;

final class User extends DomainEntity
{
    public static function register(UserId $id)
    {
        $user = new static($id);

        //

        return $user;
    }
}
