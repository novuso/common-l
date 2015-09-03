<?php

namespace Novuso\Test\Common\Doubles\Domain\Model;

use Novuso\Common\Domain\Model\Entity;
use Novuso\Common\Domain\Model\Identity;

final class Thing implements Entity
{
    use Identity;

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
