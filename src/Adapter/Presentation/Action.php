<?php

namespace Novuso\Common\Adapter\Presentation;

use Exception;
use Symfony\Component\HttpFoundation\Request;

/**
 * Action is the interface for an HTTP request handler
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Action
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
    public function handle(Request $request);
}
