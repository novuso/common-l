<?php

namespace Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver;

use LogicException;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Responder;

/**
 * ResponderResolver resolves an action class to a responder
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
interface ResponderResolver
{
    /**
     * Retrieves a responder for an action
     *
     * @param string $actionClass The full action class name
     *
     * @return Responder
     *
     * @throws LogicException When unable to retrieve a responder
     */
    public function resolve($actionClass);
}
