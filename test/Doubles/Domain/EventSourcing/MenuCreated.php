<?php

namespace Novuso\Test\Common\Doubles\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\DomainEvent;
use Novuso\Common\Domain\Model\Serialization;

final class MenuCreated implements DomainEvent
{
    use Serialization;

    private $menuId;
    private $menuName;

    public function __construct(MenuId $menuId, MenuName $menuName)
    {
        $this->menuId = $menuId;
        $this->menuName = $menuName;
    }

    public function menuId()
    {
        return $this->menuId;
    }

    public function menuName()
    {
        return $this->menuName;
    }

    public function jsonSerialize()
    {
        return [
            'menu_id'   => $this->menuId,
            'menu_name' => $this->menuName
        ];
    }
}
