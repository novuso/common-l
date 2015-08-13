<?php

namespace Novuso\Common\Adapter\Presentation\Symfony\Http;

use Exception;
use Novuso\System\Type\Type;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action is the base class for an HTTP request handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
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
     * @param Request $request    The request
     * @param mixed   $data       The domain data
     * @param array   $parameters Additional parameters
     *
     * @return View
     */
    protected function view(Request $request, $data = null, array $parameters = [])
    {
        $action = Type::create($this);

        return new View($request, $action, $data, $parameters);
    }
}
