<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\{ContainerBuilder, Reference};
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * MiddlewareCompilerPass registers middleware with the command pipeline
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class MiddlewareCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('novuso_common.command.pipeline.command_pipeline')) {
            return;
        }

        $definition = $container->findDefinition('novuso_common.command.pipeline.command_pipeline');
        $taggedServices = $container->findTaggedServiceIds('novuso_common.command_middleware');

        foreach ($taggedServices as $id => $tags) {
            $definition->addMethodCall('addMiddleware', [new Reference($id)]);
        }
    }
}
