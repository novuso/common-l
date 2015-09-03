<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Serialization;

final class MenuItemAdded implements DomainEvent
{
    use Serialization;

    private $menuId;
    private $menuItemId;
    private $menuItemPath;
    private $menuItemText;

    public function __construct(MenuId $menuId, MenuItemId $menuItemId, $menuItemPath, $menuItemText)
    {
        $this->menuId = $menuId;
        $this->menuItemId = $menuItemId;
        $this->menuItemPath = $menuItemPath;
        $this->menuItemText = $menuItemText;
    }

    public function menuId()
    {
        return $this->menuId;
    }

    public function menuItemId()
    {
        return $this->menuItemId;
    }

    public function menuItemPath()
    {
        return $this->menuItemPath;
    }

    public function menuItemText()
    {
        return $this->menuItemText;
    }

    public function jsonSerialize()
    {
        return [
            'menu_id'        => $this->menuId,
            'menu_item_id'   => $this->menuItemId,
            'menu_item_path' => $this->menuItemPath,
            'menu_item_text' => $this->menuItemText
        ];
    }
}
