<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * CommandHandlerCompilerPass registers command handlers with the service map
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandHandlerCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.command_resolver.service_map')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.command_resolver.service_map');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.command_handler');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('setHandler', [$id, $attributes['command']]);
            }
        }
    }
}
