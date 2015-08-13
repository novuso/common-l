<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class LineItemAddedEvent implements DomainEvent
{
    protected $shoppingCartId;
    protected $lineItemId;
    protected $productName;
    protected $quantity;

    public function __construct(ShoppingCartId $shoppingCartId, $lineItemId, $productName, $quantity)
    {
        $this->shoppingCartId = $shoppingCartId;
        $this->lineItemId = $lineItemId;
        $this->productName = $productName;
        $this->quantity = $quantity;
    }

    public static function deserialize(array $data)
    {
        $shoppingCartId = ShoppingCartId::fromString($data['shopping_cart_id']);
        $lineItemId = $data['line_item_id'];
        $productName = $data['product_name'];
        $quantity = $data['quantity'];

        return new self($shoppingCartId, $lineItemId, $productName, $quantity);
    }

    public function serialize()
    {
        return [
            'shopping_cart_id' => $this->shoppingCartId->toString(),
            'line_item_id'     => $this->lineItemId,
            'product_name'     => $this->productName,
            'quantity'         => $this->quantity
        ];
    }

    public function shoppingCartId()
    {
        return $this->shoppingCartId;
    }

    public function lineItemId()
    {
        return $this->lineItemId;
    }

    public function productName()
    {
        return $this->productName;
    }

    public function quantity()
    {
        return $this->quantity;
    }
}
