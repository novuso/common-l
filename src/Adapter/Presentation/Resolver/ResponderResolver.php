<?php

namespace Novuso\Common\Adapter\Presentation\Resolver;

use LogicException;
use Novuso\Common\Adapter\Presentation\Responder;
use Novuso\System\Type\Type;

/**
 * ResponderResolver resolves an action type to a responder
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface ResponderResolver
{
    /**
     * Retrieves a responder for an action
     *
     * @param Type $actionType The action type
     *
     * @return Responder
     *
     * @throws LogicException When unable to resolver a responder
     */
    public function resolve(Type $actionType);
}
