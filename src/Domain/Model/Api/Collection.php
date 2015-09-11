<?php

namespace Novuso\Common\Domain\Model\Api;

use ArrayAccess;
use Closure;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Collection is the interface for a domain collection
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Collection extends ArrayAccess, Countable, IteratorAggregate
{
    /**
     * Checks if empty
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Retrieves the count
     *
     * @return int
     */
    public function count();

    /**
     * Adds an element
     *
     * @param mixed $element The element
     *
     * @return true
     */
    public function add($element);

    /**
     * Sets an element at a given key/index
     *
     * @param string|int $key     The key
     * @param mixed      $element The element
     *
     * @return void
     */
    public function set($key, $element);

    /**
     * Retrieves an element at a given key/index
     *
     * @param string|int $key The key
     *
     * @return mixed
     */
    public function get($key);

    /**
     * Checks whether an element is in the collection
     *
     * @param mixed $element The element
     *
     * @return bool
     */
    public function contains($element);

    /**
     * Checks whether an element has the given key
     *
     * @param string|int $key The key
     *
     * @return bool
     */
    public function containsKey($key);

    /**
     * Removes an element with the given key
     *
     * Returns the removed element or null if not found.
     *
     * @param string|int $key The key
     *
     * @return mixed
     */
    public function remove($key);

    /**
     * Removes the given element if it is found
     *
     * @param mixed $element The element
     *
     * @return bool
     */
    public function removeElement($element);

    /**
     * Clears the collection
     *
     * @return void
     */
    public function clear();

    /**
     * Sets or adds an element using array syntax
     *
     * @param string|int|null $key     The key
     * @param mixed           $element The element
     *
     * @return void
     */
    public function offsetSet($key, $element);

    /**
     * Retrieves an element using array syntax
     *
     * @param string|int $key The key
     *
     * @return mixed
     */
    public function offsetGet($key);

    /**
     * Checks for an element using array syntax
     *
     * @param string|int $key The key
     *
     * @return bool
     */
    public function offsetExists($key);

    /**
     * Removes an element using array syntax
     *
     * @param string|int $key The key
     *
     * @return void
     */
    public function offsetUnset($key);

    /**
     * Retrieves a list of keys
     *
     * @return array
     */
    public function getKeys();

    /**
     * Retrieves a list of values
     *
     * @return array
     */
    public function getValues();

    /**
     * Retrieves the key/index of a given element
     *
     * Comparison is strict.
     *
     * Returns false if the element is not found.
     *
     * @param mixed $element The element
     *
     * @return string|int|bool
     */
    public function indexOf($element);

    /**
     * Retrieves a slice of elements
     *
     * @param int      $offset The starting offset
     * @param int|null $length The length or null for no limit
     *
     * @return array
     */
    public function slice($offset, $length = null);

    /**
     * Sets the internal pointer to the first element and returns it
     *
     * @return mixed|false
     */
    public function first();

    /**
     * Sets the internal pointer to the last element and returns it
     *
     * @return mixed|false
     */
    public function last();

    /**
     * Retrieves the key/index at the current pointer position
     *
     * @return string|int|null
     */
    public function key();

    /**
     * Retrieves the element at the current pointer position
     *
     * @return mixed|false
     */
    public function current();

    /**
     * Moves the internal pointer to the next element and returns it
     *
     * @return mixed|false
     */
    public function next();

    /**
     * Creates a collection with the callback result for each element
     *
     * @param Closure $callback The callback function
     *
     * @return Collection
     */
    public function map(Closure $callback);

    /**
     * Creates a collection with each element that passes the given predicate
     *
     * @param Closure $predicate The predicate function
     *
     * @return Collection
     */
    public function filter(Closure $predicate);

    /**
     * Checks if any elements pass the given predicate
     *
     * @param Closure $predicate The predicate function
     *
     * @return bool
     */
    public function exists(Closure $predicate);

    /**
     * Checks if all elements pass the given predicate
     *
     * @param Closure $predicate The predicate function
     *
     * @return bool
     */
    public function forAll(Closure $predicate);

    /**
     * Creates two collections from the predicate result for each element
     *
     * Elements that pass the test function are added to the collection in
     * the return array[0]. Elements that do not pass are added to the
     * collection in the return array[1].
     *
     * @param Closure $predicate The predicate function
     *
     * @return array
     */
    public function partition(Closure $predicate);

    /**
     * Retrieves an array representation
     *
     * @return array
     */
    public function toArray();

    /**
     * Retrieves an iterator
     *
     * @return Traversable
     */
    public function getIterator();
}
