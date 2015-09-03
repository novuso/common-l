<?php

namespace Novuso\Common\Domain\Messaging;

use ArrayAccess;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use JsonSerializable;
use Novuso\System\Exception\KeyException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Utility\VarPrinter;
use Serializable;
use Traversable;

/**
 * MetaData contains informational data related to a message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class MetaData implements ArrayAccess, Countable, IteratorAggregate, JsonSerializable, Serializable
{
    /**
     * Meta data
     *
     * @var array
     */
    private $data = [];

    /**
     * Constructs MetaData
     *
     * @param array $metaData An associated array of meta data
     */
    public function __construct(array $metaData = [])
    {
        foreach ($metaData as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->data);
    }

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Sets a key/value pair
     *
     * The value may be a scalar, array, or null. An array may be nested as
     * long as it does not contain any more complex types than arrays, scalars,
     * or null.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return void
     *
     * @throws TypeException When value is an invalid type
     */
    public function set($key, $value)
    {
        $key = (string) $key;

        $this->guardValue($value);

        $this->data[$key] = $value;
    }

    /**
     * Retrieves a value by key
     *
     * @param string $key The key
     *
     * @return mixed
     *
     * @throws KeyException When the key is not defined
     */
    public function get($key)
    {
        if (!array_key_exists($key, $this->data)) {
            $message = sprintf('Key not found: %s', VarPrinter::toString($key));
            throw KeyException::create($message);
        }

        return $this->data[$key];
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
        return array_key_exists($key, $this->data);
    }

    /**
     * Removes a key/value pair
     *
     * @param string $key The key
     *
     * @return void
     */
    public function remove($key)
    {
        unset($this->data[$key]);
    }

    /**
     * Sets a key/value pair
     *
     * The value may be a scalar or array. An array may be nested as long as
     * it does not contain any more complex types than arrays or scalars.
     *
     * @param string $key   The key
     * @param mixed  $value The value
     *
     * @return void
     *
     * @throws TypeException When value is an invalid type
     */
    public function offsetSet($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Retrieves a value by key
     *
     * @param string $key The key
     *
     * @return mixed
     *
     * @throws KeyException When the key is not defined
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * Checks if a key is defined
     *
     * @param string $key The key
     *
     * @return bool
     */
    public function offsetExists($key)
    {
        return $this->has($key);
    }

    /**
     * Removes a key/value pair
     *
     * @param string $key The key
     *
     * @return void
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * Retrieves a list of keys
     *
     * @return string[]
     */
    public function keys()
    {
        return array_keys($this->data);
    }

    /**
     * Merges given meta data
     *
     * @param MetaData $metaData The meta data
     *
     * @return void
     */
    public function merge(MetaData $metaData)
    {
        foreach ($metaData as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString()
    {
        return json_encode($this->data, JSON_UNESCAPED_SLASHES);
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
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->data;
    }

    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(['data' => $this->data]);
    }

    /**
     * Handles construction from a serialized representation
     *
     * @param string $serialized The serialized representation
     *
     * @return void
     */
    public function unserialize($serialized)
    {
        $data = unserialize($serialized);
        $this->data = $data['data'];
    }

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Validates value type
     *
     * @param mixed $value The value
     *
     * @return void
     *
     * @throws TypeException When value is an invalid type
     */
    private function guardValue($value)
    {
        if (!$this->isValid($value)) {
            $message = 'Value must be scalar or an array of scalars';
            throw TypeException::create($message);
        }
    }

    /**
     * Checks if value is valid
     *
     * @param mixed $value The value
     *
     * @return bool
     */
    private function isValid($value)
    {
        $type = gettype($value);
        switch ($type) {
            case 'string':
            case 'integer':
            case 'double':
            case 'boolean':
            case 'NULL':
                return true;
                break;
            case 'array':
                foreach ($value as $v) {
                    if (!$this->isValid($v)) {
                        return false;
                    }
                }

                return true;
                break;
            default:
                break;
        }

        return false;
    }
}
