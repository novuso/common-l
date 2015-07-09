<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Silex\Provider;

use Novuso\Common\Adapter\Infrastructure\Service\PimpleContainer;
use Pimple\Container;
use Pimple\ServiceProviderInterface;

/**
 * ContainerServiceProvider provides the service container to the application
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ContainerServiceProvider implements ServiceProviderInterface
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
        // Novuso\Common\Application\Service\Container
        $app['novuso_common.service.container'] = function ($app) {
            return new PimpleContainer($app);
        };
    }
}
