<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class CustomerStartedShoppingEvent implements DomainEvent
{
    protected $shoppingCartId;
    protected $customerId;

    public function __construct(ShoppingCartId $shoppingCartId, CustomerId $customerId)
    {
        $this->shoppingCartId = $shoppingCartId;
        $this->customerId = $customerId;
    }

    public static function deserialize(array $data)
    {
        $shoppingCartId = ShoppingCartId::fromString($data['shopping_cart_id']);
        $customerId = CustomerId::fromString($data['customer_id']);

        return new self($shoppingCartId, $customerId);
    }

    public function serialize()
    {
        return [
            'shopping_cart_id' => $this->shoppingCartId->toString(),
            'customer_id'      => $this->customerId->toString()
        ];
    }

    public function shoppingCartId()
    {
        return $this->shoppingCartId;
    }

    public function customerId()
    {
        return $this->customerId;
    }
}
