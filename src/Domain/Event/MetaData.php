<?php

namespace Novuso\Common\Domain\Event;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Novuso\System\Exception\KeyException;
use Novuso\System\Exception\TypeException;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Utility\VarPrinter;
use Traversable;

/**
 * MetaData contains informational data for a domain event message
 *
 * Keys must be strings. Values may be scalar or an array of scalars.
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
     * @var array
     */
    protected $data = [];

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
     * {@inheritdoc}
     */
    public static function deserialize(array $data)
    {
        return new self($data);
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        $output = $this->data;

        return $output;
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
        if (!isset($this->data[$key])) {
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
        return isset($this->data[$key]);
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
     * Retrieves a list of keys
     *
     * @return string[]
     */
    public function keys()
    {
        return array_keys($this->data);
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
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator()
    {
        return new ArrayIterator($this->data);
    }

    /**
     * Validates value
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
            throw TypeException::create('Value must be scalar or array of scalars');
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
