<?php

namespace Novuso\Common\Domain\Event;

use Countable;
use IteratorAggregate;
use Novuso\Common\Domain\Value\Value;
use Novuso\Common\Domain\Value\ValueSerializer;
use Novuso\System\Collection\SortedTable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Serialization\Serializable;
use Traversable;

/**
 * MetaData is informational data for a domain event message
 *
 * Data is stored as value objects with string keys.
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
final class MetaData implements Countable, IteratorAggregate, Serializable
{
    /**
     * Meta data
     *
     * @var SortedTable
     */
    protected $data;

    /**
     * Constructs MetaData
     *
     * @param array $metaData An associated array of meta data
     */
    public function __construct(array $metaData)
    {
        $this->data = SortedTable::string(Value::class);

        foreach ($metaData as $key => $value) {
            $this->data->set($key, $value);
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        $input = [];

        foreach ($data as $key => $value) {
            $input[$key] = ValueSerializer::deserialize($value);
        }

        return new static($input);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $output = [];

        foreach ($this->data as $key => $value) {
            $output[$key] = ValueSerializer::serialize($value);
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
        return $this->data->isEmpty();
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return $this->data->count();
    }

    /**
     * Retrieves a value by key
     *
     * @param string $key The key
     *
     * @return Value
     *
     * @throws KeyException When the key is not defined
     */
    public function get($key)
    {
        return $this->data->get($key);
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
        return $this->data->has($key);
    }

    /**
     * Retrieves an iterator for keys
     *
     * @return Traversable
     */
    public function keys()
    {
        return $this->data->keys();
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString()
    {
        $output = [];

        foreach ($this->data as $key => $value) {
            $output[$key] = $value->toString();
        }

        return json_encode($output, JSON_UNESCAPED_SLASHES);
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
        return $this->data->getIterator();
    }
}
