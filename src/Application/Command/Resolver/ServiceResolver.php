<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command\Resolver;

use Novuso\Common\Application\Command\{Command, Handler};
use Novuso\Common\Application\Command\Exception\HandlerNotFoundException;

/**
 * ServiceResolver resolves a handler from a service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ServiceResolver implements HandlerResolver
{
    /**
     * Service map
     *
     * @var ServiceMap
     */
    protected $serviceMap;

    /**
     * Constructs ServiceResolver
     *
     * @param ServiceMap $serviceMap The handler service map
     */
    public function __construct(ServiceMap $serviceMap)
    {
        $this->serviceMap = $serviceMap;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Command $command): Handler
    {
        $commandClass = get_class($command);

        if (!$this->serviceMap->hasHandler($commandClass)) {
            $message = sprintf('Handler not defined for command: %s', $commandClass);
            throw HandlerNotFoundException::create($message);
        }

        return $this->serviceMap->getHandler($commandClass);
    }
}
