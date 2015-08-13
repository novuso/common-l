<?php

namespace Novuso\Test\Common\Domain\Messaging\Query;

use Novuso\Common\Domain\Messaging\Query\QueryMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\Test\Common\Doubles\Domain\Messaging\Query\GetThingQuery;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Messaging\Query\QueryMessage
 */
class QueryMessageTest extends PHPUnit_Framework_TestCase
{
    protected $queryMessage;

    public function setUp()
    {
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new GetThingQuery('014ec11d-2f21-4d33-a624-5df1196a4f85');
        $metaData = new MetaData();
        $this->queryMessage = new QueryMessage($messageId, $timestamp, $payload, $metaData);
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $string = $serializer->serialize($this->queryMessage);
        $object = $serializer->deserialize($string);
        $this->assertTrue($object->equals($this->queryMessage));
    }

    public function test_that_message_id_returns_expected_instance()
    {
        $messageId = $this->queryMessage->messageId();
        $this->assertSame('014ec11e-4343-49cd-9b7a-cdd4ced5cedc', $messageId->toString());
    }

    public function test_that_timestamp_returns_expected_instance()
    {
        $timestamp = $this->queryMessage->timestamp();
        $this->assertSame('2015-01-01T13:12:31.045234[America/Chicago]', $timestamp->toString());
    }

    public function test_that_payload_returns_expected_instance()
    {
        $payload = $this->queryMessage->payload();
        $this->assertSame('014ec11d-2f21-4d33-a624-5df1196a4f85', $payload->id());
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $payloadType = $this->queryMessage->payloadType();
        $expected = 'Novuso.Test.Common.Doubles.Domain.Messaging.Query.GetThingQuery';
        $this->assertSame($expected, $payloadType->toString());
    }

    public function test_that_meta_data_returns_expected_instance()
    {
        $metaData = $this->queryMessage->metaData();
        $this->assertTrue($metaData->isEmpty());
    }

    public function test_that_with_meta_data_returns_equal_instance_with_new_meta_data()
    {
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $queryMessage = $this->queryMessage->withMetaData($metaData);
        $this->assertTrue(
            $queryMessage->metaData()->get('ip_address') === '127.0.0.1'
            && $this->queryMessage->equals($queryMessage)
        );
    }

    public function test_that_merge_meta_data_returns_equal_instance_with_merged_meta_data()
    {
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $queryMessage = $this->queryMessage->withMetaData($metaData);
        $metaData = new MetaData(['format' => 'json']);
        $queryMessage = $queryMessage->mergeMetaData($metaData);
        $this->assertTrue(
            $queryMessage->metaData()->get('ip_address') === '127.0.0.1'
            && $queryMessage->metaData()->get('format') === 'json'
            && $this->queryMessage->equals($queryMessage)
        );
    }

    public function test_that_to_string_returns_expected_value()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, $this->queryMessage->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, (string) $this->queryMessage);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $this->assertSame(0, $this->queryMessage->compareTo($this->queryMessage));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $serializer = new JsonSerializer();
        $queryMessage = $serializer->deserialize($serializer->serialize($this->queryMessage));
        $this->assertSame(0, $this->queryMessage->compareTo($queryMessage));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $next = $this->getNextQuery();
        $this->assertSame(1, $next->compareTo($this->queryMessage));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $next = $this->getNextQuery();
        $this->assertSame(-1, $this->queryMessage->compareTo($next));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $this->assertTrue($this->queryMessage->equals($this->queryMessage));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $serializer = new JsonSerializer();
        $queryMessage = $serializer->deserialize($serializer->serialize($this->queryMessage));
        $this->assertTrue($this->queryMessage->equals($queryMessage));
    }

    public function test_that_equals_returns_false_for_invalid_value()
    {
        $this->assertFalse($this->queryMessage->equals('014ec11e434349cd9b7acdd4ced5cedc'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $next = $this->getNextQuery();
        $this->assertFalse($this->queryMessage->equals($next));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $this->assertSame('014ec11e434349cd9b7acdd4ced5cedc', $this->queryMessage->hashValue());
    }

    protected function getNextQuery()
    {
        $messageId = MessageId::fromString('014ec11f-317e-475a-9d5f-b8ae6c20aab1');
        $timestamp = DateTime::fromString('2015-01-02T10:34:12.672291[America/Chicago]');
        $metaData = new MetaData();
        $payload = new GetThingQuery('014ec11d-2f21-4d33-a624-5df1196a4f85');

        return new QueryMessage(
            $messageId,
            $timestamp,
            $payload,
            $metaData
        );
    }

    protected function getToStringJson()
    {
        return '{"message_id":"014ec11e-4343-49cd-9b7a-cdd4ced5cedc",'
            .'"timestamp":"2015-01-01T13:12:31.045234[America/Chicago]",'
            .'"query_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Query.GetThingQuery",'
            .'"query_data":{"id":"014ec11d-2f21-4d33-a624-5df1196a4f85"},"meta_data":[]}';
    }
}
