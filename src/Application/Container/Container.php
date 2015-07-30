<?php

namespace Novuso\Common\Application\Container;

use Novuso\Common\Application\Container\Exception\EntryNotFoundException;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;

/**
 * Container is the interface for an application service container
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Container
{
    /**
     * Retrieves a container entry by ID
     *
     * @param string $id The entry ID
     *
     * @return mixed
     *
     * @throws EntryNotFoundException When the entry is not found
     * @throws ServiceContainerException When an unexpected error occurs
     */
    public function get($id);

    /**
     * Checks if an entry ID is defined
     *
     * @param string $id The entry ID
     *
     * @return bool
     */
    public function has($id);
}
