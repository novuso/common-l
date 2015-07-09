<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Silex\Provider;

use Novuso\Common\Application\Command\Pipeline\ApplicationBus;
use Novuso\Common\Application\Command\Pipeline\CommandLogger;
use Novuso\Common\Application\Command\Pipeline\CommandPipeline;
use Novuso\Common\Application\Command\Resolver\ServiceMap;
use Novuso\Common\Application\Command\Resolver\ServiceResolver;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * CommandServiceProvider provides command services for the application
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class CommandServiceProvider implements ServiceProviderInterface
{
    /**
     * Registers services on the given container
     *
     * @param Container $app A Container instance
     *
     * @return void
     */
    public function register(Container $app)
    {
        // Novuso\Common\Application\Command\CommandBus
        $app['novuso_common.command.command_bus'] = function () use ($app) {
            return $app['novuso_common.command.pipeline.command_pipeline'];
        };

        // Novuso\Common\Application\Command\Resolver\HandlerResolver
        $app['novuso_common.command.resolver'] = function () use ($app) {
            return $app['novuso_common.command.resolver.service_resolver'];
        };

        // Novuso\Common\Application\Command\Resolver\ServiceMap
        $app['novuso_common.command.resolver.service_map'] = function ($app) {
            return new ServiceMap($app['novuso_common.service.container']);
        };

        // Novuso\Common\Application\Command\Pipeline\CommandPipeline
        $app['novuso_common.command.pipeline.command_pipeline'] = function ($app) {
            return new CommandPipeline($app['novuso_common.command.pipeline.application_bus']);
        };

        // Novuso\Common\Application\Command\Pipeline\ApplicationBus
        $app['novuso_common.command.pipeline.application_bus'] = function ($app) {
            return new ApplicationBus($app['novuso_common.command.resolver']);
        };

        // Novuso\Common\Application\Command\Pipeline\CommandLogger
        $app['novuso_common.command.pipeline.command_logger'] = function ($app) {
            return new CommandLogger($app['novuso_common.logging.logger']);
        };

        // Novuso\Common\Application\Command\Resolver\ServiceResolver
        $app['novuso_common.command.resolver.service_resolver'] = function ($app) {
            return new ServiceResolver($app['novuso_common.command.resolver.service_map']);
        };

        // extends the command pipeline; adding the command logger as middleware
        $app->extend('novuso_common.command.pipeline.command_pipeline', function ($pipeline, $app) {
            $pipeline->addMiddleware($app['novuso_common.command.pipeline.command_logger']);

            return $pipeline;
        });
    }
}
