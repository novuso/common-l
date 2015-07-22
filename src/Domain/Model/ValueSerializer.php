<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Api\Parsable;
use Novuso\Common\Domain\Model\Api\Serializer;
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
final class ValueSerializer implements Serializer
{
    /**
     * {@inheritdoc}
     */
    public static function serialize(Parsable $object)
    {
        return json_decode([
            'type'  => Type::create($object)->toString(),
            'value' => $object->toString()
        ]);
    }

    /**
     * {@inheritdoc}
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

        $array = json_decode($state, true);

        if (!isset($array['type']) || !isset($array['value'])) {
            $message = sprintf('%s expects keys "type" and "value"; received %s', __METHOD__, $state);
            throw DomainException::create($message);
        }

        $class = Type::create($array['type'])->toClassName();

        return $class::fromString($array['value']);
    }
}
