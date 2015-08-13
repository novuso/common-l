<?php

namespace Novuso\Common\Domain\Messaging\Event;

use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Model\Identifier;
use Novuso\System\Collection\SortedSet;
use Novuso\System\Type\Type;
use Traversable;

/**
 * EventStream contains event messages for a single aggregate
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class EventStream implements Countable, IteratorAggregate
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    protected $aggregateId;

    /**
     * Aggregate type
     *
     * @var Type
     */
    protected $aggregateType;

    /**
     * Event messages
     *
     * @var SortedSet
     */
    protected $messages;

    /**
     * Committed version
     *
     * @var int|null
     */
    protected $committed;

    /**
     * Version number
     *
     * @var int|null
     */
    protected $version;

    /**
     * Constructs EventStream
     *
     * @param Identifier     $aggregateId   The aggregate ID
     * @param Type           $aggregateType The aggregate type
     * @param int|null       $committed     The committed version
     * @param int|null       $version       The current version
     * @param EventMessage[] $messages      A list of event messages
     */
    public function __construct(Identifier $aggregateId, Type $aggregateType, $committed, $version, array $messages)
    {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->committed = ($committed === null) ? null : (int) $committed;
        $this->version = ($version === null) ? null : (int) $version;
        $this->messages = SortedSet::comparable(EventMessage::class);
        foreach ($messages as $message) {
            $this->messages->add($message);
        }
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->messages->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->messages->count();
    }

    /**
     * Retrieves the aggregate ID
     *
     * @return Identifier
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * Retrieves the aggregate type
     *
     * @return Type
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * Retrieves the committed version
     *
     * @return int|null
     */
    public function committed()
    {
        return $this->committed;
    }

    /**
     * Retrieves the current version
     *
     * @return int|null
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->messages->getIterator();
    }
}
