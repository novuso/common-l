<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class CustomerRegisteredEvent implements DomainEvent
{
    protected $customerId;
    protected $email;

    public function __construct(CustomerId $customerId, Email $email)
    {
        $this->customerId = $customerId;
        $this->email = $email;
    }

    public static function deserialize(array $data)
    {
        $customerId = CustomerId::fromString($data['customer_id']);
        $email = Email::fromString($data['email']);

        return new self($customerId, $email);
    }

    public function serialize()
    {
        return [
            'customer_id' => $this->customerId->toString(),
            'email'       => $this->email->toString()
        ];
    }

    public function customerId()
    {
        return $this->customerId;
    }

    public function email()
    {
        return $this->email;
    }
}
