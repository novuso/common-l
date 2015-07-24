<?php

namespace Novuso\Common\Domain\Entity;

use Novuso\System\Utility\Test;

/**
 * DomainEntity is the base class for a domain entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class DomainEntity implements Entity
{
    /**
     * {@inheritdoc}
     */
    public function compareTo($object)
    {
        if ($this === $object) {
            return 0;
        }

        assert(
            Test::areSameType($this, $object),
            sprintf('Comparison requires instance of %s', static::class)
        );

        return $this->id()->compareTo($object->id());
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

        return $this->id()->equals($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->id()->hashValue();
    }
}
