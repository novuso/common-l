<?php

namespace Novuso\Common\Application\Container;

use Novuso\Common\Application\Container\Exception\EntryNotFoundException;
use Novuso\System\Utility\VarPrinter;

/**
 * ServiceContainer is a simple application service container
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class ServiceContainer implements Container
{
    /**
     * Service factories
     *
     * @var array
     */
    private $services = [];

    /**
     * Config parameters
     *
     * @var array
     */
    private $parameters = [];

    /**
     * Defines an object factory
     *
     * @param string   $id       The entry ID
     * @param callable $callback The object factory callback
     *
     * @return void
     */
    public function factory($id, callable $callback)
    {
        $this->services[$id] = $callback;
    }

    /**
     * Defines a service
     *
     * @param string   $id       The entry ID
     * @param callable $callback The service factory callback
     *
     * @return void
     */
    public function service($id, callable $callback)
    {
        $this->services[$id] = function ($c) use ($callback) {
            static $object;

            if ($object === null) {
                $object = $callback($c);
            }

            return $object;
        };
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!isset($this->services[$id])) {
            $message = sprintf('Entry (%s) is not defined', VarPrinter::toString($id));
            throw EntryNotFoundException::create($message);
        }

        return $this->services[$id]($this);
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return isset($this->services[$id]);
    }

    /**
     * Removes an entry
     *
     * @param string $id The entry ID
     *
     * @return void
     */
    public function remove($id)
    {
        unset($this->services[$id]);
    }

    /**
     * Sets a config parameter
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @return void
     */
    public function setParameter($name, $value)
    {
        $this->parameters[$name] = $value;
    }

    /**
     * Retrieves a config parameter
     *
     * @param string $name    The parameter name
     * @param mixed  $default A default value to return if not found
     *
     * @return mixed
     */
    public function getParameter($name, $default = null)
    {
        if (!(isset($this->parameters[$name]) || array_key_exists($name, $this->parameters))) {
            return $default;
        }

        return $this->parameters[$name];
    }

    /**
     * Checks if a parameter exists
     *
     * @param string $name The parameter name
     *
     * @return bool
     */
    public function hasParameter($name)
    {
        return isset($this->parameters[$name]) || array_key_exists($name, $this->parameters);
    }

    /**
     * Removes a parameter
     *
     * @param string $name The parameter name
     *
     * @return void
     */
    public function removeParameter($name)
    {
        unset($this->parameters[$name]);
    }
}
