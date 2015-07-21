<?php

namespace Novuso\Common\Domain\Event\Api;

/**
 * Subscriber is the interface for a domain event subscriber
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Subscriber
{
    /**
     * Special type for all events
     *
     * @var string
     */
    const ALL_EVENTS = '__ALL_EVENTS__';

    /**
     * Retrieves event registration
     *
     * The returned array keys are event types. The event type string is the
     * canonicalized version of the class name.
     *
     * Novuso\System\Utility\ClassName::canonical($className) will retrieve the
     * event type, when passed in the fully-qualified class name of the event.
     *
     * Array values can be:
     *
     * * The method name to call (default priority of 0)
     * * An array consisting of the method name to call and the priority
     * * An array of arrays consisting of the method names and priorities;
     *   unset priorities default to zero
     *
     * Example:
     *
     * * ['Event.Type' => 'methodName']
     * * ['Event.Type' => ['methodName', 10]]
     * * ['Event.Type' => [['methodOne', 10], ['methodTwo']]]
     *
     * Event handler signature: function (EventMessage $message): void {}
     *
     * @return array
     */
    public static function eventRegistration();
}
