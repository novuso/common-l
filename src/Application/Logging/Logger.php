<?php

namespace Novuso\Common\Application\Logging;

/**
 * Logger is the interface for an application logger
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Logger
{
    /**
     * Logs an emergency; system is unusable
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function emergency($message, array $context = []);

    /**
     * Logs an alert; action must be taken immediately
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function alert($message, array $context = []);

    /**
     * Logs a critical condition
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function critical($message, array $context = []);

    /**
     * Logs an error condition
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function error($message, array $context = []);

    /**
     * Logs a warning condition
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function warning($message, array $context = []);

    /**
     * Logs a normal but significant event
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function notice($message, array $context = []);

    /**
     * Logs an informational message
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function info($message, array $context = []);

    /**
     * Logs a debug-level message
     *
     * The message MAY contain placeholders in the form: {foo} where foo
     * will be replaced by the context data in key "foo".
     *
     * The context array can contain arbitrary data, the only assumption that
     * can be made by implementers is that if an Exception instance is given
     * to produce a stack trace, it MUST be in a key named "exception".
     *
     * @param string $message The message
     * @param array  $context Additional information
     *
     * @return void
     */
    public function debug($message, array $context = []);
}
