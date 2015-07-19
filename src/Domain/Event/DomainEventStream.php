<?php

namespace Novuso\Common\Domain\Event;

use ArrayIterator;
use Novuso\Common\Domain\Event\Api\EventMessage;
use Novuso\Common\Domain\Event\Api\EventStream;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * DomainEventStream is a stream of domain event messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class DomainEventStream implements EventStream
{
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
     * Constructs DomainEventStream
     *
     * @param array $messages A list of event messages
     */
    public function __construct(array $messages)
    {
        assert(
            Test::isListOf($messages, EventMessage::class),
            sprintf('Invalid event messages: %s', VarPrinter::toString($messages))
        );

        $this->messages = array_values($messages);
        $this->count = count($this->messages);
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->count === 0;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->count;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->messages);
    }
}
