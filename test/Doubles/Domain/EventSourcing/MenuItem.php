<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\EntityEventSourcing;
use Novuso\Common\Domain\EventSourcing\EventSourcedEntity;
use Novuso\Common\Domain\Model\Identity;

final class MenuItem implements EventSourcedEntity
{
    use EntityEventSourcing;
    use Identity;

    private $id;
    private $menuId;
    private $path;
    private $text;
    private $parent;
    private $children;

    private function __construct(MenuItemId $id, MenuId $menuId, $path, $text)
    {
        $this->id = $id;
        $this->menuId = $menuId;
        $this->path = $path;
        $this->text = $text;
        $this->children = [];
    }

    public static function create(MenuItemId $id, MenuId $menuId, $path, $text)
    {
        $menuItem = new self($id, $menuId, $path, $text);

        return $menuItem;
    }

    public function id()
    {
        return $this->id;
    }

    public function parent()
    {
        return $this->parent;
    }

    private function applyMenuItemMoved(MenuItemMoved $domainEvent)
    {
        $menuItemId = $domainEvent->menuItemId();
        if (!$menuItemId->equals($this->id)) {
            return;
        }
        $parentItemId = $domainEvent->parentItemId();
        $parent = null;
        if ($parentItemId !== null) {
            $items = $this->getAggregateRoot()->items();
            foreach ($items as $item) {
                if ($item->id()->equals($parentItemId)) {
                    $parent = $item;
                    break;
                }
            }
        }
        $this->setParent($parent);
    }

    private function childEntities()
    {
        return $this->children;
    }

    private function setParent(MenuItem $parent = null)
    {
        if ($this->parent !== null) {
            $this->parent->removeChild($this);
        }
        $this->parent = $parent;
        if ($this->parent !== null) {
            $this->parent->addChild($this);
        }
    }

    private function addChild(MenuItem $child)
    {
        if (!in_array($child, $this->children, true)) {
            $this->children[] = $child;
        }
    }

    private function removeChild(MenuItem $child)
    {
        $key = array_search($child, $this->children, true);
        if ($key !== false) {
            unset($this->children[$key]);
        }
    }
}
