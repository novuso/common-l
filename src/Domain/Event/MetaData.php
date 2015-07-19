<?php

namespace Novuso\Common\Domain\Event;

use Countable;
use IteratorAggregate;
use Novuso\System\Collection\HashTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Serialization\Serializable;
use Traversable;

/**
 * MetaData is informational data for a domain event message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class MetaData implements Countable, IteratorAggregate, Serializable
{
    /**
     * Hash table
     *
     * @var HashTable
     */
    protected $table;

    /**
     * Constructs MetaData
     *
     * @param array $metaData An associated array of meta data
     */
    public function __construct(array $metaData)
    {
        $this->table = HashTable::of('string', 'string');

        foreach ($metaData as $key => $value) {
            $this->table->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new static($data);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $output = [];

        foreach ($this->table as $key => $value) {
            $output[$key] = $value;
        }

        return $output;
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return $this->table->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->table->count();
    }

    /**
     * Retrieves a value by key
     *
     * @param string $key The key
     *
     * @return string
     *
     * @throws KeyException When the key is not defined
     */
    public function get($key)
    {
        return $this->table->get($key);
    }

    /**
     * Checks if a key is defined
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function has($key)
    {
        return $this->table->has($key);
    }

    /**
     * Retrieves an iterator for keys
     *
     * @return Traversable
     */
    public function keys()
    {
        return $this->table->keys();
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString()
    {
        $output = [];

        foreach ($this->table as $key => $value) {
            $output[$key] = $value;
        }

        return json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toString();
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return $this->table->getIterator();
    }
}
