<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Contract\Value;
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
 * @version   0.0.0
 */
abstract class ValueObject implements Value
{
    /**
     * {@inheritdoc}
     */
    abstract public function toString(): string;

    /**
     * {@inheritdoc}
     */
    abstract public function jsonSerialize();

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize(get_object_vars($this));
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        $properties = unserialize($str);
        foreach ($properties as $property => $value) {
            $this->$property = $value;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function equals($object): bool
    {
        if ($this === $object) {
            return true;
        }

        if (!Test::sameType($this, $object)) {
            return false;
        }

        return $this->toString() === $object->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->toString();
    }
}
