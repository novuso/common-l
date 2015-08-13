<?php

namespace Novuso\Common\Domain\Model;

use Novuso\System\Utility\Test;

/**
 * ValueObject is the base class for a domain value object
 *
 * Implementations must adhere to value characteristics:
 *
 * * It measures, quantifies, or describes a thing in the domain
 * * It is maintained as immutable
 * * It models a conceptual whole by composing related attributes as an
 *   integral unit
 * * It is completely replaceable when the measurement or description changes
 * * It can be compared with others using value equality
 * * It supplies its collaborators with side-effect-free behavior
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
abstract class ValueObject implements Value
{
    /**
     * {@inheritdoc}
     */
    abstract public function toString();

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object)
    {
        if ($this === $object) {
            return true;
        }

        if (!Test::areSameType($this, $object)) {
            return false;
        }

        return $this->toString() === $object->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->toString();
    }
}
