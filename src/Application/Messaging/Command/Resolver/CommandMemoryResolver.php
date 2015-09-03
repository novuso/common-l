<?php

namespace Novuso\Common\Application\Messaging\Command\Resolver;

use Novuso\Common\Domain\Messaging\Command\Command;

/**
 * CommandMemoryResolver resolves handlers from an CommandMemoryMap
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
class CommandMemoryResolver implements CommandHandlerResolver
{
    /**
     * Handler map
     *
     * @var CommandMemoryMap
     */
    protected $handlerMap;

    /**
     * Constructs CommandMemoryResolver
     *
     * @param CommandMemoryMap $handlerMap The handler map
     */
    public function __construct(CommandMemoryMap $handlerMap)
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
