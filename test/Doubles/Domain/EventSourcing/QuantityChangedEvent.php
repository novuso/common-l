<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;

final class QuantityChangedEvent implements DomainEvent
{
    protected $shoppingCartId;
    protected $lineItemId;
    protected $productName;
    protected $oldQuantity;
    protected $newQuantity;

    public function __construct(ShoppingCartId $shoppingCartId, $lineItemId, $productName, $oldQuantity, $newQuantity)
    {
        $this->shoppingCartId = $shoppingCartId;
        $this->lineItemId = $lineItemId;
        $this->productName = $productName;
        $this->oldQuantity = $oldQuantity;
        $this->newQuantity = $newQuantity;
    }

    public static function deserialize(array $data)
    {
        $shoppingCartId = ShoppingCartId::fromString($data['shopping_cart_id']);
        $lineItemId = $data['line_item_id'];
        $productName = $data['product_name'];
        $oldQuantity = $data['old_quantity'];
        $newQuantity = $data['new_quantity'];

        return new self($shoppingCartId, $lineItemId, $productName, $oldQuantity, $newQuantity);
    }

    public function serialize()
    {
        return [
            'shopping_cart_id' => $this->shoppingCartId->toString(),
            'line_item_id'     => $this->lineItemId,
            'product_name'     => $this->productName,
            'old_quantity'     => $this->oldQuantity,
            'new_quantity'     => $this->newQuantity
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

    public function oldQuantity()
    {
        return $this->oldQuantity;
    }

    public function newQuantity()
    {
        return $this->newQuantity;
    }
}
