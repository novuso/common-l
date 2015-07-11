<?php declare(strict_types=1);

namespace Novuso\Common\Application\Command\Resolver;

use Novuso\Common\Application\Command\{Command, Handler};
use Novuso\Common\Application\Command\Exception\HandlerNotFoundException;

/**
 * InMemoryResolver resolves handlers from an InMemoryMap
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class InMemoryResolver implements HandlerResolver
{
    /**
     * Handler map
     *
     * @var InMemoryMap
     */
    protected $map;

    /**
     * Constructs InMemoryResolver
     *
     * @param InMemoryMap $map The handler map
     */
    public function __construct(InMemoryMap $map)
    {
        $this->map = $map;
    }

    /**
     * {@inheritdoc}
     */
    public function resolve(Command $command): Handler
    {
        $commandClass = get_class($command);

        if (!$this->handlerMap->hasHandler($commandClass)) {
            $message = sprintf('Handler not defined for command: %s', $commandClass);
            throw HandlerNotFoundException::create($message);
        }

        return $this->handlerMap->getHandler($commandClass);
    }
}
