<?php

namespace Novuso\Common\Adapter\Infrastructure\Container;

use Exception;
use Novuso\Common\Application\Container\Container;
use Novuso\Common\Application\Container\Exception\EntryNotFoundException;
use Novuso\Common\Application\Container\Exception\ServiceContainerException;
use Novuso\System\Utility\VarPrinter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SymfonyContainer is a Symfony container adapter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class SymfonyContainer implements Container
{
    /**
     * Service container
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructs SymfonyContainer
     *
     * @param ContainerInterface $container A ContainerInterface instance
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        if (!$this->container->has($id)) {
            $message = sprintf('Identifier (%s) is not defined', VarPrinter::toString($id));
            throw EntryNotFoundException::create($message);
        }

        // @codeCoverageIgnoreStart
        try {
            return $this->container->get($id);
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
        return $this->container->has($id);
    }
}
