<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Contract\{Entity, Identifier};
use Novuso\System\Utility\Test;

/**
 * BaseEntity is the base class for a domain entity
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class BaseEntity implements Entity
{
    /**
     * ID
     *
     * @var Identifier
     */
    protected $id;

    /**
     * Constructs BaseEntity
     *
     * @internal
     *
     * @param Identifier $id The identifier
     */
    protected function __construct(Identifier $id)
    {
        $this->id = $id;
    }

    /**
     * {@inheritdoc}
     */
    public function id(): Identifier
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function compareTo($object): int
    {
        if ($this === $object) {
            return 0;
        }

        assert(Test::sameType($this, $object), sprintf('Comparison requires instance of %s', static::class));

        return $this->id->compareTo($object->id());
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

        return $this->id->equals($object->id());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->id->hashValue();
    }
}
