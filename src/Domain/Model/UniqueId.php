<?php

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\Common\Domain\Model\Api\IdentifierFactory;
use Novuso\Common\Domain\Model\Identifier\Uuid;
use Novuso\System\Exception\DomainException;
use Novuso\System\Utility\Test;

/**
 * UniqueId is the base class for UUID based identifiers
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
abstract class UniqueId extends ValueObject implements Identifier, IdentifierFactory
{
    /**
     * UUID
     *
     * @var Uuid
     */
    protected $uuid;

    /**
     * Constructs UniqueId
     *
     * @internal
     *
     * @param Uuid $uuid The Uuid instance
     */
    protected function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * {@inheritdoc}
     */
    public static function generate()
    {
        return new static(Uuid::comb());
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

        return new static(Uuid::parse($id));
    }

    /**
     * {@inheritdoc}
     */
    public function toString()
    {
        return $this->uuid->toString();
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

        return $this->uuid->compareTo($object->uuid);
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

        return $this->uuid->equals($object->uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue()
    {
        return $this->uuid->hashValue();
    }
}
