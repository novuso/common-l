<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use InvalidArgumentException;
use Novuso\Common\Domain\Messaging\Query\QueryHandler;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * QueryHandlerCompilerPass registers query handlers with the service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class QueryHandlerCompilerPass implements CompilerPassInterface
{
    /**
     * Processes query handler tags
     *
     * @param ContainerBuilder $container The container builder
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.query_service_map')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.query_service_map');
        $taggedServices = $container->findTaggedServices('novuso_common.query_handler');

        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);

            if (!$def->isPublic()) {
                $message = sprintf('The service "%s" must be public as query handlers are lazy-loaded', $id);
                throw new InvalidArgumentException($message);
            }

            if ($def->isAbstract()) {
                $message = sprintf('The service "%s" must not be abstract as query handlers are lazy-loaded', $id);
                throw new InvalidArgumentException($message);
            }

            $class = $container->getParameterBag()->resolveValue($def->getClass());
            $refClass = new ReflectionClass($class);

            if (!$refClass->implementsInterface(QueryHandler::class)) {
                $message = sprintf('Service "%s" must implement interface "%s"', $id, QueryHandler::class);
                throw new InvalidArgumentException($message);
            }

            foreach ($tags as $attributes) {
                if (!isset($attributes['query'])) {
                    $message = sprintf('Service "%s" is missing query attribute', $id);
                    throw new InvalidArgumentException($message);
                }
                $definition->addMethodCall('registerHandler', [$attributes['query'], $id]);
            }
        }
    }
}
