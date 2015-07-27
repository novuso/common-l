<?php

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * ResponderCompilerPass registers view responders with the resolver
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ResponderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.responder_resolver')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.responder_resolver');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.view_resolver');

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall('setResponder', [$id, $attributes['action']]);
            }
        }
    }
}
