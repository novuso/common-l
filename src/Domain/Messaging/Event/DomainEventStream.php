<?php

namespace Novuso\Common\Domain\Messaging\Event;

use Novuso\Common\Domain\Model\Api\Identifier;
use Novuso\System\Collection\SortedSet;
use Novuso\System\Type\Type;

/**
 * DomainEventStream contains event messages for a single aggregate
 *
 * @copyright Copyright (c) 2015, Novuso. <http://novuso.com>
 * @license   http://opensource.org/licenses/MIT The MIT License
 * @author    John Nickell <email@johnnickell.com>
 */
final class DomainEventStream implements EventStream
{
    /**
     * Aggregate ID
     *
     * @var Identifier
     */
    private $aggregateId;

    /**
     * Aggregate type
     *
     * @var Type
     */
    private $aggregateType;

    /**
     * Event messages
     *
     * @var SortedSet
     */
    private $messages;

    /**
     * Committed version
     *
     * @var int|null
     */
    private $committed;

    /**
     * Version number
     *
     * @var int|null
     */
    private $version;

    /**
     * Constructs EventStream
     *
     * @param Identifier     $aggregateId   The aggregate ID
     * @param Type           $aggregateType The aggregate type
     * @param int|null       $committed     The committed version
     * @param int|null       $version       The current version
     * @param EventMessage[] $messages      A list of event messages
     */
    public function __construct(Identifier $aggregateId, Type $aggregateType, $committed, $version, array $messages)
    {
        $this->aggregateId = $aggregateId;
        $this->aggregateType = $aggregateType;
        $this->committed = ($committed === null) ? null : (int) $committed;
        $this->version = ($version === null) ? null : (int) $version;
        $this->messages = SortedSet::comparable(EventMessage::class);

        foreach ($messages as $message) {
            $this->messages->add($message);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isEmpty()
    {
        return $this->messages->isEmpty();
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return $this->messages->count();
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * {@inheritdoc}
     */
    public function aggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * {@inheritdoc}
     */
    public function committed()
    {
        return $this->committed;
    }

    /**
     * {@inheritdoc}
     */
    public function version()
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        $messages = [];
        foreach ($this->messages as $message) {
            $messages[] = $message;
        }

        return [
            'id'        => $this->aggregateId,
            'type'      => $this->aggregateType,
            'committed' => $this->committed,
            'version'   => $this->version,
            'messages'  => $messages
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return $this->messages->getIterator();
    }
}
