<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\Api\EventSourcedRootEntity;
use Novuso\Common\Domain\Model\Identity;

/**
 * EventSourcedAggregateRoot is the base class for an aggregate root entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class EventSourcedAggregateRoot implements EventSourcedRootEntity
{
    use AggregateEventSourcing;
    use Identity;
}
