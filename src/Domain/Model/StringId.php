<?php

namespace Novuso\Common\Domain\Model;

use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Test;
use Novuso\System\Utility\VarPrinter;

/**
 * StringId is the base class for string identifiers
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
abstract class StringId extends ValueObject implements Identifier
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
     * @throws DomainException When the ID is not valid
     */
    protected function __construct($id)
    {
        $this->guardId($id);

        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString($id)
    {
        assert(
            Test::isString($id),
            sprintf('%s expects $id to be a string', __METHOD__)
        );

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
     * Validates the ID
     *
     * Override to implement validation.
     *
     * @codeCoverageIgnore
     *
     * @param string $id The ID string
     *
     * @return void
     *
     * @throws DomainException When the ID is not valid
     */
    protected function guardId($id)
    {
    }
}
