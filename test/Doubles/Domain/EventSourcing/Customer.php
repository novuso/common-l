<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;

final class Customer extends EventSourcedAggregateRoot
{
    protected $id;
    protected $email;

    private function __construct(CustomerId $id, Email $email)
    {
        $this->id = $id;
        $this->email = $email;
    }

    public static function register(Email $email)
    {
        $id = CustomerId::generate();
        $customer = new self($id, $email);
        $customer->recordThat(new CustomerRegisteredEvent($id, $email));

        return $customer;
    }

    public function id()
    {
        return $this->id;
    }

    public function email()
    {
        return $this->email;
    }

    public function changeEmail(Email $email)
    {
        $this->recordThat(new EmailChangedEvent($this->id, $this->email, $email));
    }

    protected function applyCustomerRegisteredEvent(CustomerRegisteredEvent $domainEvent)
    {
        $this->id = $domainEvent->customerId();
        $this->email = $domainEvent->email();
    }

    protected function applyEmailChangedEvent(EmailChangedEvent $domainEvent)
    {
        $this->email = $domainEvent->newEmail();
    }
}
