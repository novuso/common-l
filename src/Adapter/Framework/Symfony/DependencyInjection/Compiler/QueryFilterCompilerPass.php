<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * QueryFilterCompilerPass registers filters with the query pipeline
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class QueryFilterCompilerPass implements CompilerPassInterface
{
    /**
     * Processes query filter tags
     *
     * @param ContainerBuilder $container The container builder
     *
     * @return void
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.query_service')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.query_service');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.query_filter');

        foreach (array_keys($taggedServices) as $id) {
            $definition->addMethodCall('addFilter', [new Reference($id)]);
        }
    }
}
