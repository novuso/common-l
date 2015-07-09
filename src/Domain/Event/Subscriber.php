<?php declare(strict_types=1);

namespace Novuso\Common\Domain\Event;

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
     * Retrieves event registration
     *
     * The returned array keys are event types. The event type string is the
     * canonicalized and underscored version of the class name. Use the
     * Novuso\System\Utility\ClassName::underscore($className) to retrieve the
     * event type, passing in the event's fully-qualified class name.
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
     * * ['event_type' => 'methodName']
     * * ['event_type' => ['methodName', 10]]
     * * ['event_type' => [['methodOne', 10], ['methodTwo']]]
     *
     * @return array
     */
    public static function eventRegistration(): array;
}
