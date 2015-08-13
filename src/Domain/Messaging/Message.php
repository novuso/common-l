<?php

namespace Novuso\Common\Domain\Messaging;

use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Serialization\Serializable;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;

/**
 * Message is the interface for an application message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 * @version   0.0.0
 */
interface Message extends Comparable, Equatable, Serializable
{
    /**
     * Retrieves the message ID
     *
     * @return MessageId
     */
    public function messageId();

    /**
     * Retrieves the timestamp
     *
     * @return DateTime
     */
    public function timestamp();

    /**
     * Retrieves the payload
     *
     * @return Serializable
     */
    public function payload();

    /**
     * Retrieves the payload type
     *
     * @return Type
     */
    public function payloadType();

    /**
     * Retrieves meta data
     *
     * @return MetaData
     */
    public function metaData();

    /**
     * Creates instance with the given meta data
     *
     * @param MetaData $metaData The meta data
     *
     * @return Message
     */
    public function withMetaData(MetaData $metaData);

    /**
     * Creates instance after merging meta data
     *
     * @param MetaData $metaData The meta data
     *
     * @return Message
     */
    public function mergeMetaData(MetaData $metaData);

    /**
     * Retrieves a string representation
     *
     * @return string
     */
    public function toString();

    /**
     * Handles casting to a string
     *
     * @return string
     */
    public function __toString();
}
