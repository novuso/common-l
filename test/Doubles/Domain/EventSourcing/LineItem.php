<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedDomainEntity;

final class LineItem extends EventSourcedDomainEntity
{
    protected $id;
    protected $productName;
    protected $quantity;

    public function __construct($id, $productName, $quantity)
    {
        $this->id = (string) $id;
        $this->productName = (string) $productName;
        $this->quantity = (int) $quantity;
    }

    public function id()
    {
        return $this->id;
    }

    public function productName()
    {
        return $this->productName;
    }

    public function quantity()
    {
        return $this->quantity;
    }

    public function updateQuantity($quantity)
    {
        $aggregateRoot = $this->getAggregateRoot();
        $quantityChanged = new QuantityChangedEvent(
            $aggregateRoot->id(),
            $this->id,
            $this->productName,
            $this->quantity,
            $quantity
        );
        $aggregateRoot->recordThat($quantityChanged);
    }

    protected function applyQuantityChangedEvent(QuantityChangedEvent $domainEvent)
    {
        if ($this->id !== $domainEvent->lineItemId()) {
            return;
        }

        $this->quantity = $domainEvent->newQuantity();
    }
}
