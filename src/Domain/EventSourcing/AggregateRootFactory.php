<?php

namespace Novuso\Common\Domain\EventSourcing;

use Novuso\Common\Domain\Messaging\Event\EventStream;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\Test;

/**
 * AggregateRootFactory is an aggregate root factory
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class AggregateRootFactory
{
    /**
     * Reconstitutes an event sourced aggregate root by type
     *
     * @param Type        $type        The aggregate root type
     * @param EventStream $eventStream The aggregate history
     *
     * @return EventSourcedAggregateRoot
     *
     * @throws TypeException When the type is invalid
     */
    public function reconstitute(Type $type, EventStream $eventStream)
    {
        $class = $type->toClassName();

        if (!Test::isSubclassOf($class, EventSourcedAggregateRoot::class)) {
            $message = sprintf('%s is not an instance of %s', $class, EventSourcedAggregateRoot::class);
            throw TypeException::create($message);
        }

        return $class::reconstitute($eventStream);
    }
}
