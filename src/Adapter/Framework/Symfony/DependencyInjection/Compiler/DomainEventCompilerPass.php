<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use InvalidArgumentException;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * DomainEventCompilerPass registers event subscribers with the dispatcher
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class DomainEventCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.domain_event.dispatcher')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.domain_event.dispatcher');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.event_subscriber');

        foreach ($taggedServices as $id => $tags) {
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
            $interface = 'Novuso\\Common\\Domain\\Event\\Subscriber';

            if ($refClass->implementsInterface($interface)) {
                $message = sprintf('Service "%s" must implement interface "%s"', $id, $interface);
                throw new InvalidArgumentException($message);
            }

            $definition->addMethodCall('attachService', [$id, $class]);
        }
    }
}
