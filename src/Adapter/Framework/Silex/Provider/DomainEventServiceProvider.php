<?php declare(strict_types=1);

namespace Novuso\Common\Adapter\Framework\Silex\Provider;

use Novuso\Common\Application\DomainEvent\ServiceAwareDispatcher;
use Pimple\{Container, ServiceProviderInterface};

/**
 * DomainEventServiceProvider provides services for dispatching domain events
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class DomainEventServiceProvider implements ServiceProviderInterface
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
        // Novuso\Common\Domain\Event\Dispatcher
        $app['novuso_common.domain_event.dispatcher'] = function ($app) {
            return new ServiceAwareDispatcher($app['novuso_common.service.container']);
        };
    }
}
