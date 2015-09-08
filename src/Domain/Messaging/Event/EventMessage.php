<?php

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Messaging\Message;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Type\Type;

/**
 * EventMessage is interface for a domain event message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventMessage extends Message
{
    /**
     * Retrieves the payload
     *
     * @return DomainEvent
     */
    public function payload();

    /**
     * Retrieves the aggregate ID
     *
     * @return Identifier
     */
    public function aggregateId();

    /**
     * Retrieves the aggregate type
     *
     * @return Type
     */
    public function aggregateType();

    /**
     * Retrieves the sequence number
     *
     * @return int
     */
    public function sequence();
}
