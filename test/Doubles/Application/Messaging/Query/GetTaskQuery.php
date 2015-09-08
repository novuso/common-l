<?php

namespace Novuso\Test\Common\Doubles\Application\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;
use Novuso\Common\Domain\Model\Serialization;

class GetTaskQuery implements Query
{
    use Serialization;

    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function id()
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return ['id' => $this->id];
    }
}
