<?php

namespace Novuso\Common\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\Message;

/**
 * QueryMessage is the interface for a domain query message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface QueryMessage extends Message
{
    /**
     * Retrieves the payload
     *
     * @return Query
     */
    public function payload();
}
