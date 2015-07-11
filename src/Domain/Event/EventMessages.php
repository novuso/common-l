<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\System\Utility\{Test, VarPrinter};
use Traversable;

/**
 * EventMessages is an immutable collection of event messages
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class EventMessages implements Countable, IteratorAggregate
{
    /**
     * Event messages
     *
     * @var EventMessage[]
     */
    protected $messages;

    /**
     * Constructs EventMessages
     *
     * @param EventMessage[] $messages A list of event messages
     */
    public function __construct(array $messages)
    {
        assert(
            Test::listOf($messages, EventMessage::class),
            sprintf('Invalid event messages: %s', VarPrinter::toString($messages))
        );

        $this->messages = array_values($messages);
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->messages);
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count(): int
    {
        return count($this->messages);
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->messages);
    }
}
