<?php

namespace Novuso\Common\Application\Service;

use Novuso\Common\Application\Service\Exception\EntryNotFoundException;
use Novuso\Common\Application\Service\Exception\ServiceException;

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
     * @throws ServiceException When an error occurs during processing
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
