<?php

namespace Novuso\Common\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Type\Contract;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * EventStream is a stream of domain event messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventStream implements Countable, IteratorAggregate
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    protected $aggregateId;

    /**
     * Aggregate Type
     *
     * @var Contract
     */
    protected $aggregateType;

    /**
     * Event Messages
     *
     * @var EventMessage[]
     */
    protected $messages;

    /**
     * Message count
     *
     * @var int
     */
    protected $count;

    /**
     * Constructs EventStream
     *
     * @param Identifier     $aggregateId   The aggregate ID
     * @param Contract       $aggregateType The aggregate type
     * @param EventMessage[] $messages      A list of event messages
     */
    public function __construct(Identifier $aggregateId, Contract $aggregateType, array $messages)
    {
        assert(
            Test::isListOf($messages, EventMessage::class),
            sprintf('Invalid event messages: %s', VarPrinter::toString($messages))
        );

        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->messages = array_values($messages);
        $this->count = count($this->messages);
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
     * @return Contract
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->count === 0;
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->messages);
    }
}
