<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Silex\Provider;

use Novuso\Common\Adapter\Infrastructure\Logging\PsrLogger;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * LoggingServiceProvider provides logging services for the application
 *
 * This service provider depends on another service provider that registers a
 * PSR-3 compatible logger to the container as 'logger'.
 *
 * Silex comes with such a provider: Silex\Provider\MonologServiceProvider
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class LoggingServiceProvider implements ServiceProviderInterface
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
        // Novuso\Common\Application\Logging\Logger
        $app['novuso_common.logging.logger'] = function ($app) {
            return new PsrLogger($app['logger']);
        };
    }
}
