<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Serialization;

final class MenuItemMoved implements DomainEvent
{
    use Serialization;

    private $menuItemId;
    private $parentItemId;

    public function __construct(MenuItemId $menuItemId, MenuItemId $parentItemId = null)
    {
        $this->menuItemId = $menuItemId;
        $this->parentItemId = $parentItemId;
    }

    public function menuItemId()
    {
        return $this->menuItemId;
    }

    public function parentItemId()
    {
        return $this->parentItemId;
    }

    public function jsonSerialize()
    {
        return [
            'menu_item_id'   => $this->menuItemId,
            'parent_item_id' => $this->parentItemId
        ];
    }
}
