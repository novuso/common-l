<?php

namespace Novuso\Common\Domain\Value;

use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Type\Type;
use Novuso\System\Utility\VarPrinter;

/**
 * ValueSerializer is a value object serializer
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class ValueSerializer
{
    /**
     * Serializes a value object
     *
     * @param Value $value The value
     *
     * @return string
     */
    public static function serialize(Value $value)
    {
        return sprintf('[%s]%s', Type::create($value)->toString(), $value->toString());
    }

    /**
     * Deserializes a value object
     *
     * @param string $state The serialized object
     *
     * @return Value
     *
     * @throws TypeException When state is not a string
     * @throws DomainException When the string is invalid
     */
    public static function deserialize($state)
    {
        if (!is_string($state)) {
            $message = sprintf(
                '%s expects $state to be a string; received (%s) %s',
                __METHOD__,
                gettype($state),
                VarPrinter::toString($state)
            );
            throw TypeException::create($message);
        }

        $pattern = '/\A\[(?P<type>[a-z0-9\.]+)\](?P<value>.*)\z/i';
        if (!preg_match($pattern, $state, $matches)) {
            $message = sprintf('Invalid state: %s', $state);
            throw DomainException::create($message);
        }

        $class = Type::create($matches['type'])->toClassName();

        if (!class_exists($class)) {
            $message = sprintf('%s is not a valid class name', $class);
            throw DomainException::create($message);
        }

        return $class::fromString($matches['value']);
    }
}
