<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\EventSourcing\Api\EventSourcedEntity;
use Novuso\Common\Domain\Model\Identity;

/**
 * EventSourcedDomainEntity is the base class for an event sourced entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class EventSourcedDomainEntity implements EventSourcedEntity
{
    use EntityEventSourcing;
    use Identity;
}
