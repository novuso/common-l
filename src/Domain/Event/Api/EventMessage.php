<?php

namespace Novuso\Common\Domain\Event\Api;

use Novuso\Common\Domain\Event\MetaData;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Contract;
use Novuso\System\Type\Equatable;

/**
 * EventMessage is the interface for a domain event message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface EventMessage extends Comparable, Equatable, Serializable
{
    /**
     * Retrieves a unique identifier
     *
     * @return Identifier
     */
    public function eventId();

    /**
     * Retrieves the sequence number
     *
     * @return int
     */
    public function sequenceNumber();

    /**
     * Retrieves the event type
     *
     * @return Contract
     */
    public function eventType();

    /**
     * Retrieves the aggregate ID
     *
     * @return Identifier
     */
    public function aggregateId();

    /**
     * Retrieves the aggregate contract
     *
     * @return Contract
     */
    public function aggregateType();

    /**
     * Retrieves the timestamp
     *
     * @return DateTime
     */
    public function dateTime();

    /**
     * Retrieves the meta data
     *
     * @return MetaData
     */
    public function metaData();

    /**
     * Retrieves the event data
     *
     * @return Event
     */
    public function eventData();

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString();

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString();
}
