<?php

namespace Novuso\Common\Adapter\Presentation;

use Novuso\System\Type\Type;

/**
 * View represents a domain payload that may contain additional data
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class View
{
    /**
     * Action type
     *
     * @var Type
     */
    protected $action;

    /**
     * Domain payload
     *
     * @var mixed|null
     */
    protected $data;

    /**
     * Request format
     *
     * @var string|null
     */
    protected $format;

    /**
     * Template parameters
     *
     * @var array
     */
    protected $parameters;

    /**
     * Constructs View
     *
     * @param Type        $action     The action type
     * @param mixed|null  $data       The domain payload
     * @param string|null $format     The request format
     * @param array       $parameters Template parameters
     */
    public function __construct(Type $action, $data, $format, array $parameters = [])
    {
        $this->action = $action;
        $this->data = $data;
        $this->format = $format;
        $this->parameters = $parameters;
    }

    /**
     * Retrieves the action type
     *
     * @return Type
     */
    public function action()
    {
        return $this->action;
    }

    /**
     * Retrieves the domain payload
     *
     * @return mixed|null
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * Retrieves the request format
     *
     * @return string|null
     */
    public function format()
    {
        return $this->format;
    }

    /**
     * Retrieves parameters for templates
     *
     * An associated array where keys are mapped to template variables. Do not
     * use the key 'data', as that is reserved for the domain payload.
     *
     * @return array
     */
    public function parameters()
    {
        return $this->parameters;
    }
}
