<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class EmailChangedEvent implements DomainEvent
{
    protected $customerId;
    protected $oldEmail;
    protected $newEmail;

    public function __construct(CustomerId $customerId, Email $oldEmail, Email $newEmail)
    {
        $this->customerId = $customerId;
        $this->oldEmail = $oldEmail;
        $this->newEmail = $newEmail;
    }

    public static function deserialize(array $data)
    {
        $customerId = CustomerId::fromString($data['customer_id']);
        $oldEmail = Email::fromString($data['old_email']);
        $newEmail = Email::fromString($data['new_email']);

        return new self($customerId, $oldEmail, $newEmail);
    }

    public function serialize()
    {
        return [
            'customer_id' => $this->customerId->toString(),
            'old_email'   => $this->oldEmail->toString(),
            'new_email'   => $this->newEmail->toString()
        ];
    }

    public function customerId()
    {
        return $this->customerId;
    }

    public function oldEmail()
    {
        return $this->oldEmail;
    }

    public function newEmail()
    {
        return $this->newEmail;
    }
}
