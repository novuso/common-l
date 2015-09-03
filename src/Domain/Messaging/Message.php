<?php

namespace Novuso\Common\Domain\Messaging;

use JsonSerializable;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Type\Comparable;
use Novuso\System\Type\Equatable;
use Novuso\System\Type\Type;
use Serializable;

/**
 * Message is the interface for an application message
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
interface Message extends Comparable, Equatable, JsonSerializable, Serializable
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

    /**
     * Retrieves a value for JSON encoding
     *
     * @return array
     */
    public function jsonSerialize();

    /**
     * Retrieves a serialized representation
     *
     * @return string
     */
    public function serialize();

    /**
     * Handles construction from a serialized representation
     *
     * @param string $serialized The serialized representation
     *
     * @return void
     */
    public function unserialize($serialized);
}
