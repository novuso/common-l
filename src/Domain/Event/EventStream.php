<?php

namespace Novuso\Common\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * EventStream is a stream of event messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventStream implements Countable, IteratorAggregate
{
    /**
     * Associated ID
     *
     * @var Identifier
     */
    protected $id;

    /**
     * Associated Type
     *
     * @var Type
     */
    protected $type;

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
     * @param Identifier     $id       The associated ID
     * @param Type           $type     The associated type
     * @param EventMessage[] $messages A list of event messages
     */
    public function __construct(Identifier $id, Type $type, array $messages)
    {
        assert(
            Test::isListOf($messages, EventMessage::class),
            sprintf('Invalid event messages: %s', VarPrinter::toString($messages))
        );

        $this->id = $id;
        $this->type = $type;
        $this->messages = array_values($messages);
        $this->count = count($this->messages);
    }

    /**
     * Retrieves the associated ID
     *
     * @return Identifier
     */
    public function id()
    {
        return $this->id;
    }

    /**
     * Retrieves the associated type
     *
     * @return Type
     */
    public function type()
    {
        return $this->type;
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
