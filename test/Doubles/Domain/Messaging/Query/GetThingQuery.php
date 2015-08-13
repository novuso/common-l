<?php

namespace Novuso\Test\Common\Doubles\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\Query;

final class GetThingQuery implements Query
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public static function deserialize(array $data)
    {
        return new self($data['id']);
    }

    public function serialize()
    {
        return ['id' => $this->id];
    }

    public function id()
    {
        return $this->id;
    }
}
