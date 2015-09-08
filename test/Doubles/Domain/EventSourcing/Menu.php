<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EventSourcedAggregateRoot;
use Novuso\Common\Domain\Messaging\Event\EventStream;

final class Menu extends EventSourcedAggregateRoot
{
    private $id;
    private $name;
    private $items;

    private function __construct(MenuId $id)
    {
        $this->id = $id;
        $this->name = MenuName::fromString('NEW');
        $this->items = [];
    }

    public static function create($name)
    {
        $id = MenuId::generate();
        $name = MenuName::fromString($name);
        $menu = new self($id);
        $menu->raiseEvent(new MenuCreated($id, $name));

        return $menu;
    }

    public static function reconstitute(EventStream $eventStream)
    {
        $id = $eventStream->aggregateId();
        $menu = new self($id);
        $menu->initializeFromEventStream($eventStream);

        return $menu;
    }

    public function id()
    {
        return $this->id;
    }

    public function name()
    {
        return $this->name;
    }

    public function items()
    {
        return array_values($this->items);
    }

    public function addMenuItem($path, $text)
    {
        $menuItemId = MenuItemId::generate();
        $this->raiseEvent(new MenuItemAdded($this->id, $menuItemId, $path, $text));
    }

    public function moveMenuItem($menuItemId, $parentItemId)
    {
        $menuItem = $this->findMenuItem(MenuItemId::fromString($menuItemId));
        if ($parentItemId !== null) {
            $parentItem = $this->findMenuItem(MenuItemId::fromString($parentItemId));
        } else {
            $parentItem = null;
        }
        $this->raiseEvent(new MenuItemMoved($menuItem->id(), $parentItem !== null ? $parentItem->id() : null));
    }

    protected function applyMenuCreated(MenuCreated $domainEvent)
    {
        $this->name = $domainEvent->menuName();
    }

    protected function applyMenuItemAdded(MenuItemAdded $domainEvent)
    {
        $menuItem = MenuItem::create(
            $domainEvent->menuItemId(),
            $domainEvent->menuId(),
            $domainEvent->menuItemPath(),
            $domainEvent->menuItemText()
        );
        $this->items[$menuItem->id()->toString()] = $menuItem;
    }

    protected function childEntities()
    {
        return $this->items;
    }

    private function findMenuItem(MenuItemId $menuItemId)
    {
        foreach ($this->items as $item) {
            if ($item->id()->equals($menuItemId)) {
                return $item;
            }
        }

        return null;
    }
}
