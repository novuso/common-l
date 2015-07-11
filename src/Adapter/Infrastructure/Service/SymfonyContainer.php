<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Infrastructure\Service;

use Exception;
use Novuso\Common\Application\Service\Container;
use Novuso\Common\Application\Service\Exception\{EntryNotFoundException, ServiceException};
use Novuso\System\Utility\VarPrinter;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SymfonyContainer is a Symfony container adapter
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
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
    public function get(string $id)
    {
        if (!$this->container->has($id)) {
            $message = sprintf('Identifier (%s) is not defined', VarPrinter::toString($id));
            throw EntryNotFoundException::create($message);
        }

        // @codeCoverageIgnoreStart
        try {
            return $this->container->get($id);
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
        return $this->container->has($id);
    }
}
