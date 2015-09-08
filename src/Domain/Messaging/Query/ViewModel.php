<?php

namespace Novuso\Common\Domain\Messaging\Query;

/**
 * ViewModel is a data response to a domain query
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface ViewModel
{
    /**
     * Retrieves values for the view
     *
     * @return array
     */
    public function toArray();
}
