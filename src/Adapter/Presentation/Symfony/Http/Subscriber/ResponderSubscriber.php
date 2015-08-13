<?php

namespace Novuso\Common\Adapter\Presentation\Symfony\Http\Subscriber;

use Exception;
use Novuso\Common\Adapter\Presentation\Symfony\Http\Resolver\ResponderResolver;
use Novuso\Common\Adapter\Presentation\Symfony\Http\View;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForControllerResultEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * ResponderSubscriber uses a responder to create a response
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.1
 */
class ResponderSubscriber implements EventSubscriberInterface
{
    /**
     * Responder resolver
     *
     * @var ResponderResolver
     */
    protected $resolver;

    /**
     * Constructs ResponderSubscriber
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
        return [KernelEvents::VIEW => 'onControllerResult'];
    }

    /**
     * Attempts to create a response
     *
     * @param GetResponseForControllerResultEvent $event The event
     *
     * @return void
     */
    public function onControllerResult(GetResponseForControllerResultEvent $event)
    {
        $view = $event->getControllerResult();

        if (!($view instanceof View)) {
            return;
        }

        $actionClass = $view->action()->toClassName();
        $responder = $this->resolver->resolve($actionClass);
        $response = $responder->format($view);

        if (!($response instanceof Response)) {
            return;
        }

        $event->setResponse($response);
    }
}
