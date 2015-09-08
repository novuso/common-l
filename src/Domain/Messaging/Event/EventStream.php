<?php

namespace Novuso\Common\Domain\Messaging\Event;

use Countable;
use IteratorAggregate;
use JsonSerializable;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Type\Type;
use Traversable;

/**
 * EventStream is the interface for a domain event stream
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface EventStream extends Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count();

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
     * Retrieves the committed version
     *
     * @return int|null
     */
    public function committed();

    /**
     * Retrieves the current version
     *
     * @return int|null
     */
    public function version();

    /**
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize();

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator();
}
