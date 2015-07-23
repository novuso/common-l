<?php

namespace Novuso\Common\Adapter\Presentation\Subscriber;

use Exception;
use Novuso\Common\Adapter\Presentation\Resolver\ResponderResolver;
use Novuso\Common\Adapter\Presentation\View;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ViewSubscriber exchanges a view for a response
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
class ViewSubscriber implements EventSubscriberInterface
{
    /**
     * Responder resolver
     *
     * @var ResponderResolver
     */
    protected $resolver;

    /**
     * Constructs ViewSubscriber
     *
     * @param ResponderResolver $resolver The responder resolver
     */
    public function __construct(ResponderResolver $resolver)
    {
        $this->resolver = $resolver;
    }

    /**
     * Retrieves the event subscription
     *
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::VIEW => 'onView'];
    }

    /**
     * Attempts to retrieve a response from a view
     *
     * @param GetResponseForControllerResultEvent $event The event
     *
     * @return void
     *
     * @throws Exception When an error occurs during processing
     */
    public function onView(GetResponseForControllerResultEvent $event)
    {
        $view = $event->getControllerResult();

        if (!($view instanceof View)) {
            return;
        }

        $actionType = $view->action();
        $responder = $this->resolver->resolve($actionType);
        $response = $responder->format($view);

        if (!($response instanceof Response)) {
            return;
        }

        $event->setResponse($response);
    }
}
