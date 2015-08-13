<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;

final class ShoppingCart extends EventSourcedAggregateRoot
{
    protected $id;
    protected $customerId;
    protected $lineItems = [];
    protected $lineItemId = 0;

    private function __construct(ShoppingCartId $id, CustomerId $customerId)
    {
        $this->id = $id;
        $this->customerId = $customerId;
    }

    public static function forCustomer(Customer $customer)
    {
        $id = ShoppingCartId::generate();
        $customerId = $customer->id();
        $shoppingCart = new self($id, $customerId);
        $shoppingCart->recordThat(new CustomerStartedShoppingEvent($id, $customerId));

        return $shoppingCart;
    }

    public function id()
    {
        return $this->id;
    }

    public function customerId()
    {
        return $this->customerId;
    }

    public function lineItems()
    {
        return array_values($this->lineItems);
    }

    public function addItem($productName, $quantity = 1)
    {
        $lineItem = $this->findLineItemByProductName($productName);
        if ($lineItem !== null) {
            $total = $lineItem->quantity() + $quantity;
            $lineItem->updateQuantity($total);
        } else {
            $lineItemId = $this->lineItemId + 1;
            $lineItemAdded = new LineItemAddedEvent(
                $this->id,
                $lineItemId,
                $productName,
                $quantity
            );
            $this->recordThat($lineItemAdded);
        }
    }

    public function removeItem($productName)
    {
        $lineItem = $this->findLineItemByProductName($productName);

        if ($lineItem === null) {
            return;
        }

        $lineItemRemoved = new LineItemRemovedEvent(
            $this->id,
            $lineItem->id(),
            $lineItem->productName(),
            $lineItem->quantity()
        );
        $this->recordThat($lineItemRemoved);
    }

    protected function childEntities()
    {
        return $this->lineItems;
    }

    protected function findLineItemByProductName($productName)
    {
        foreach ($this->lineItems as $lineItem) {
            if ($lineItem->productName() === $productName) {
                return $lineItem;
            }
        }

        return null;
    }

    protected function applyCustomerStartedShoppingEvent(CustomerStartedShoppingEvent $domainEvent)
    {
        $this->id = $domainEvent->shoppingCartId();
        $this->customerId = $domainEvent->customerId();
    }

    protected function applyLineItemAddedEvent(LineItemAddedEvent $domainEvent)
    {
        $this->lineItemId = $domainEvent->lineItemId();
        $lineItem = new LineItem(
            $this->lineItemId,
            $domainEvent->productName(),
            $domainEvent->quantity()
        );
        $this->lineItems[$this->lineItemId] = $lineItem;
    }

    protected function applyLineItemRemovedEvent(LineItemRemovedEvent $domainEvent)
    {
        $lineItemId = $domainEvent->lineItemId();
        if (!isset($this->lineItems[$lineItemId])) {
            return;
        }
        unset($this->lineItems[$lineItemId]);
    }
}
