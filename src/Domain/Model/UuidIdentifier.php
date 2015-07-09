<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Model;

use Novuso\Common\Domain\Model\Identity\Uuid;
use Novuso\System\Utility\Test;

/**
 * UuidIdentifier is the base class for UUID identifiers
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class UuidIdentifier implements Identifier
{
    /**
     * UUID
     *
     * @var Uuid
     */
    protected $uuid;

    /**
     * Constructs UuidIdentifier
     *
     * @param Uuid $uuid The UUID
     */
    protected function __construct(Uuid $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * {@inheritdoc}
     */
    public static function generate(): Identifier
    {
        return new static(Uuid::comb());
    }

    /**
     * {@inheritdoc}
     */
    public static function fromString(string $string): Identifier
    {
        return new static(Uuid::parse($string));
    }

    /**
     * Retrieves the UUID
     *
     * @return Uuid
     */
    public function uuid(): Uuid
    {
        return $this->uuid;
    }

    /**
     * {@inheritdoc}
     */
    public function toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return $this->uuid->toString();
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize(): string
    {
        return $this->uuid->jsonSerialize();
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize(['uuid' => $this->uuid]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($str)
    {
        $data = unserialize($str);
        $this->uuid = $data['uuid'];
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

        return $this->uuid->compareTo($object->uuid());
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

        return $this->uuid->equals($object->uuid());
    }

    /**
     * {@inheritdoc}
     */
    public function hashValue(): string
    {
        return $this->uuid->toHex();
    }
}
