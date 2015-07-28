<?php

namespace Novuso\Common\Adapter\Presentation;

use Exception;
use Novuso\System\Type\Type;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action is the base class for an HTTP request handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
abstract class Action
{
    /**
     * Handles a request
     *
     * @param Request $request The request
     *
     * @return View
     *
     * @throws Exception When unable to handle the request
     */
    abstract public function handle(Request $request);

    /**
     * Creates a view for the current request
     *
     * @param Request    $request    The request
     * @param mixed|null $data       The domain payload
     * @param array      $parameters Template parameters
     *
     * @return View
     */
    public function view(Request $request, $data, array $parameters = [])
    {
        $action = Type::create($this);
        $format = $request->attributes->get('_format');

        return new View($action, $data, $format, $parameters);
    }
}
