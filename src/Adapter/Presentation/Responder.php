<?php

namespace Novuso\Common\Adapter\Presentation;

use Exception;
use Symfony\Component\HttpFoundation\Response;

/**
 * Responder is the interface for an HTTP response formatter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Responder
{
    /**
     * Formats a view into a response
     *
     * @param View $view The view
     *
     * @return Response
     *
     * @throws Exception When unable to format a response
     */
    public function format(View $view);
}
