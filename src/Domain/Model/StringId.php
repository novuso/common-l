<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Exception\DomainException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * StringId is the base class for string identifiers
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class StringId implements Identifier
{
    /**
     * String ID
     *
     * @var string
     */
    protected $id;

    /**
     * Constructs StringId
     *
     * @internal
     *
     * @param string $id The ID string
     *
     * @throws TypeException When id is not a string
     * @throws DomainException When id is not valid
     */
    protected function __construct($id)
    {
        if (!is_string($id)) {
            $message = sprintf(
                '%s expects $id to be a string; received (%s) %s',
                __METHOD__,
                gettype($id),
                VarPrinter::toString($id)
            );
            throw TypeException::create($message);
        }

        $this->guardId($id);

        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString($id)
    {
        return new static($id);
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->id;
    }

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

        $comp = strnatcmp($this->id, $object->id);

        if ($comp > 0) {
            return 1;
        }
        if ($comp < 0) {
            return -1;
        }

        return 0;
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

        return $this->id === $object->id;
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->id;
    }

    /**
     * Validates the ID
     *
     * @param string $id The ID string
     *
     * @return void
     *
     * @throws DomainException When id is not valid
     */
    protected function guardId($id)
    {
    }
}
