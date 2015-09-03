<?php

namespace Novuso\Test\Common\Doubles\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;

final class GetThingQuery implements Query
{
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

    public function serialize()
    {
        return serialize(['id' => $this->id]);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->__construct($data['id']);
    }
}
