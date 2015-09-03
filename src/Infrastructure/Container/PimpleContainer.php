<?php

namespace Novuso\Common\Infrastructure\Container;

use Exception;
use Novuso\Common\Application\Container\Container;
use Novuso\Common\Application\Container\Exception\EntryNotFoundException;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\System\Utility\VarPrinter;
use Pimple\Container as ServiceContainer;

/**
 * PimpleContainer is a Pimple service container adapter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class PimpleContainer implements Container
{
    /**
     * Service container
     *
     * @var ServiceContainer
     */
    private $container;

    /**
     * Constructs PimpleContainer
     *
     * @param ServiceContainer $container A Pimple container instance
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!isset($this->container[$id])) {
            $message = sprintf('Entry (%s) is not defined', VarPrinter::toString($id));
            throw EntryNotFoundException::create($message);
        }

        // @codeCoverageIgnoreStart
        try {
            return $this->container[$id];
        } catch (Exception $exception) {
            throw ServiceContainerException::create($exception->getMessage(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return isset($this->container[$id]);
    }
}
