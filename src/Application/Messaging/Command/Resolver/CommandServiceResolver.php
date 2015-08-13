<?php

namespace Novuso\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Domain\Messaging\Command\Command;

/**
 * CommandServiceResolver resolves handlers from an CommandServiceMap
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class CommandServiceResolver implements CommandHandlerResolver
{
    /**
     * Handler map
     *
     * @var CommandServiceMap
     */
    protected $handlerMap;

    /**
     * Constructs CommandServiceResolver
     *
     * @param CommandServiceMap $handlerMap The handler map
     */
    public function __construct(CommandServiceMap $handlerMap)
    {
        $this->handlerMap = $handlerMap;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Command $command)
    {
        return $this->handlerMap->getHandler(get_class($command));
    }
}
