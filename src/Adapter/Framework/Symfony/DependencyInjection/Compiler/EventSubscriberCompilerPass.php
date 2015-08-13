<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use InvalidArgumentException;
use Novuso\Common\Domain\Messaging\Event\Subscriber;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * EventSubscriberCompilerPass registers event subscribers with the dispatcher
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class EventSubscriberCompilerPass implements CompilerPassInterface
{
    /**
     * Processes event subscriber tags
     *
     * @param ContainerBuilder $container The container builder
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.event_dispatcher')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.event_dispatcher');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.event_subscriber');

        foreach (array_keys($taggedServices) as $id) {
            $def = $container->getDefinition($id);

            if (!$def->isPublic()) {
                $message = sprintf('The service "%s" must be public as event subscribers are lazy-loaded', $id);
                throw new InvalidArgumentException($message);
            }

            if ($def->isAbstract()) {
                $message = sprintf('The service "%s" must not be abstract as event subscribers are lazy-loaded', $id);
                throw new InvalidArgumentException($message);
            }

            $class = $container->getParameterBag()->resolveValue($def->getClass());
            $refClass = new ReflectionClass($class);

            if (!$refClass->implementsInterface(Subscriber::class)) {
                $message = sprintf('Service "%s" must implement interface "%s"', $id, Subscriber::class);
                throw new InvalidArgumentException($message);
            }

            $definition->addMethodCall('attachService', [$id, $class]);
        }
    }
}
