<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Symfony;

use Novuso\Common\Adapter\Framework\Symfony\DependencyInjection\Compiler\{
    CommandHandlerCompilerPass,
    DomainEventCompilerPass,
    MiddlewareCompilerPass
};
use Symfony\Component\Console\Application;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * NovusoCommonBundle is the bundle for the common context
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class NovusoCommonBundle extends Bundle
{
    /**
     * Builds in container modifications when cache is empty
     *
     * @param ContainerBuilder $container The container builder
     *
     * @return void
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new CommandHandlerCompilerPass());
        $container->addCompilerPass(new DomainEventCompilerPass());
        $container->addCompilerPass(new MiddlewareCompilerPass());
    }

    /**
     * Registers commands for the console application
     *
     * @param Application $console The console application
     *
     * @return void
     */
    public function registerCommands(Application $console)
    {
    }
}
