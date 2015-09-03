<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Serialization;

final class PersonRegistered implements DomainEvent
{
    use Serialization;

    private $personId;
    private $personName;

    public function __construct(PersonId $personId, $personName)
    {
        $this->personId = $personId;
        $this->personName = $personName;
    }

    public function personId()
    {
        return $this->personId;
    }

    public function personName()
    {
        return $this->personName;
    }

    public function jsonSerialize()
    {
        return [
            'person_id'   => $this->personId,
            'person_name' => $this->personName
        ];
    }
}
