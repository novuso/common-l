<?php

namespace Novuso\Common\Domain\Model;

use ArrayIterator;
use Closure;
use Novuso\Common\Domain\Model\Api\Collection;

/**
 * EntityCollection is a domain entity collection
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class EntityCollection implements Collection
{
    /**
     * Elements
     *
     * @var array
     */
    protected $elements;

    /**
     * Constructs EntityCollection
     *
     * @param array $elements An array of elements
     */
    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return empty($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function add($element)
    {
        $this->elements[] = $element;

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $element)
    {
        $this->elements[$key] = $element;
    }

    /**
     * {@inheritdoc}
     */
    public function get($key)
    {
        if (!isset($this->elements[$key])) {
            return null;
        }

        return $this->elements[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function contains($element)
    {
        return in_array($element, $this->elements, true);
    }

    /**
     * {@inheritdoc}
     */
    public function containsKey($key)
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($key)
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    /**
     * {@inheritdoc}
     */
    public function removeElement($element)
    {
        $key = array_search($element, $this->elements, true);

        if ($key !== false) {
            unset($this->elements[$key]);

            return true;
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        $this->elements = [];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($key, $element)
    {
        if ($key === null) {
            $this->add($element);

            return;
        }

        $this->set($key, $element);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($key)
    {
        return $this->get($key);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($key)
    {
        return $this->containsKey($key);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($key)
    {
        $this->remove($key);
    }

    /**
     * {@inheritdoc}
     */
    public function getKeys()
    {
        return array_keys($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function getValues()
    {
        return array_values($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    /**
     * {@inheritdoc}
     */
    public function slice($offset, $length = null)
    {
        return array_slice($this->elements, $offset, $length, true);
    }

    /**
     * {@inheritdoc}
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function last()
    {
        return end($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return key($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return current($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        return next($this->elements);
    }

    /**
     * {@inheritdoc}
     */
    public function map(Closure $callback)
    {
        return new static(array_map($callback, $this->elements));
    }

    /**
     * {@inheritdoc}
     */
    public function filter(Closure $predicate)
    {
        return new static(array_filter($this->elements, $predicate));
    }

    /**
     * {@inheritdoc}
     */
    public function exists(Closure $predicate)
    {
        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function forAll(Closure $predicate)
    {
        foreach ($this->elements as $key => $element) {
            if (!$predicate($key, $element)) {
                return false;
            }
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function partition(Closure $predicate)
    {
        $data1 = $data2 = [];

        foreach ($this->elements as $key => $element) {
            if ($predicate($key, $element)) {
                $data1[$key] = $element;
            } else {
                $data2[$key] = $element;
            }
        }

        return [new static($data1), new static($data2)];
    }

    /**
     * {@inheritdoc}
     */
    public function toArray()
    {
        return $this->elements;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->elements);
    }
}
