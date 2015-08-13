<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use InvalidArgumentException;
use Novuso\Common\Adapter\Presentation\Http\Responder;
use ReflectionClass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * ResponderCompilerPass registers HTTP responders with the service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class ResponderCompilerPass implements CompilerPassInterface
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
        if (!$container->has('novuso_common.responder_service_map')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.responder_service_map');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.responder');

        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);

            if (!$def->isPublic()) {
                $message = sprintf('The service "%s" must be public as responders are lazy-loaded', $id);
                throw new InvalidArgumentException($message);
            }

            if ($def->isAbstract()) {
                $message = sprintf('The service "%s" must not be abstract as responders are lazy-loaded', $id);
                throw new InvalidArgumentException($message);
            }

            $class = $container->getParameterBag()->resolveValue($def->getClass());
            $refClass = new ReflectionClass($class);

            if (!$refClass->isSubclassOf(Responder::class)) {
                $message = sprintf('Service "%s" must extend "%s"', $id, Responder::class);
                throw new InvalidArgumentException($message);
            }

            foreach ($tags as $attributes) {
                if (!isset($attributes['action'])) {
                    $message = sprintf('Service "%s" is missing action attribute', $id);
                    throw new InvalidArgumentException($message);
                }
                $definition->addMethodCall('registerResponder', [$attributes['action'], $id]);
            }
        }
    }
}
