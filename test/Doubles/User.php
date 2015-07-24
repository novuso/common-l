<?php

namespace Novuso\Test\Common\Doubles;

use Novuso\Common\Domain\Entity\DomainEntity;

final class User extends DomainEntity
{
    protected $id;

    public function __construct(UserId $id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }
}
