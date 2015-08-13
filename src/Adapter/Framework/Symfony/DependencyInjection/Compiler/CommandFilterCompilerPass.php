<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * CommandFilterCompilerPass registers filters with the command pipeline
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class CommandFilterCompilerPass implements CompilerPassInterface
{
    /**
     * Processes command filter tags
     *
     * @param ContainerBuilder $container The container builder
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.command_bus')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.command_bus');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.command_filter');

        foreach (array_keys($taggedServices) as $id) {
            $definition->addMethodCall('addFilter', [new Reference($id)]);
        }
    }
}
