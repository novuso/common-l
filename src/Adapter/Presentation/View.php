<?php

namespace Novuso\Common\Adapter\Presentation;

use Novuso\System\Type\Type;

/**
 * View represents a domain payload that may also contain metadata
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface View
{
    /**
     * Retrieves the domain payload
     *
     * @return mixed|null
     */
    public function data();

    /**
     * Retrieves the request format
     *
     * @return string|null
     */
    public function format();

    /**
     * Retrieves the action type
     *
     * @return Type
     */
    public function action();

    /**
     * Retrieves parameters for templates
     *
     * An associated array where keys are mapped to template variables. Do not
     * use the key 'data', as that is reserved for the domain payload.
     *
     * @return array
     */
    public function parameters();
}
