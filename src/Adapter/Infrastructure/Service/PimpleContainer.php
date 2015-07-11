<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Infrastructure\Service;

use Exception;
use Novuso\Common\Application\Service\Container;
use Novuso\Common\Application\Service\Exception\{EntryNotFoundException, ServiceException};
use Novuso\System\Utility\VarPrinter;
use Pimple\Container as ServiceContainer;

/**
 * PimpleContainer is a Pimple container adapter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class PimpleContainer implements Container
{
    /**
     * Service container
     *
     * @var ServiceContainer
     */
    protected $container;

    /**
     * Constructs PimpleContainer
     *
     * @param ServiceContainer $container A Pimple Container instance
     */
    public function __construct(ServiceContainer $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function get(string $id)
    {
        if (!isset($this->container[$id])) {
            $message = sprintf('Identifier (%s) is not defined', VarPrinter::toString($id));
            throw EntryNotFoundException::create($message);
        }

        // @codeCoverageIgnoreStart
        try {
            return $this->container[$id];
        } catch (Exception $exception) {
            throw ServiceException::create($exception->getMessage(), $exception);
        }
        // @codeCoverageIgnoreEnd
    }

    /**
     * {@inheritdoc}
     */
    public function has(string $id): bool
    {
        return isset($this->container[$id]);
    }
}
