<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Model\DomainEntity;

final class Thing extends DomainEntity
{
    private $id;

    public function __construct(ThingId $id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }
}
