<?php

namespace Novuso\Common\Domain\Event;

use Novuso\Common\Domain\Event\Api\Event;
use Novuso\System\Exception\DomainException;
use ReflectionClass;

/**
 * DomainEvent is the base class for a domain event
 *
 * Implementations must adhere to event characteristics:
 *
 * * It describes something that has happened in the past
 * * It is maintained as immutable
 * * It may hold references to value objects, primitives, and identifiers
 * * It is encodable for communication with other systems
 *
 * Note: The default serialization methods assume child classes use public
 *       constructors, and that argument names match property names. If that
 *       is not the case, you must override deserialize() and serialize().
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class DomainEvent implements Event
{
    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $class = new ReflectionClass(static::class);
        $constructor = $class->getConstructor();
        $parameters = $constructor->getParameters();
        $arguments = [];
        foreach ($parameters as $param) {
            if (array_key_exists($param->name, $data)) {
                $arguments[] = $data[$param->name];
            } elseif ($param->isDefaultValueAvailable()) {
                $arguments[] = $param->getDefaultValue();
            } else {
                $message = sprintf('Unable to deserialize %s', static::class);
                throw DomainException::create($message);
            }
        }

        return $class->newInstanceArgs($arguments);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return get_object_vars($this);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return json_encode($this->serialize(), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->toString();
    }
}
