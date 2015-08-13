<?php

namespace Novuso\Test\Common\Domain\Messaging\Command;

use Novuso\Common\Domain\Messaging\Command\CommandMessage;
use Novuso\Common\Domain\Messaging\MessageId;
use Novuso\Common\Domain\Messaging\MetaData;
use Novuso\Common\Domain\Model\DateTime\DateTime;
use Novuso\System\Serialization\JsonSerializer;
use Novuso\Test\Common\Doubles\Domain\Messaging\Command\MakeThingCommand;
use PHPUnit_Framework_TestCase;

/**
 * @covers Novuso\Common\Domain\Messaging\Command\CommandMessage
 */
class CommandMessageTest extends PHPUnit_Framework_TestCase
{
    protected $commandMessage;

    public function setUp()
    {
        $messageId = MessageId::fromString('014ec11e-4343-49cd-9b7a-cdd4ced5cedc');
        $timestamp = DateTime::fromString('2015-01-01T13:12:31.045234[America/Chicago]');
        $payload = new MakeThingCommand('foo', 'bar');
        $metaData = new MetaData();
        $this->commandMessage = new CommandMessage($messageId, $timestamp, $payload, $metaData);
    }

    public function test_that_it_is_serializable()
    {
        $serializer = new JsonSerializer();
        $string = $serializer->serialize($this->commandMessage);
        $object = $serializer->deserialize($string);
        $this->assertTrue($object->equals($this->commandMessage));
    }

    public function test_that_message_id_returns_expected_instance()
    {
        $messageId = $this->commandMessage->messageId();
        $this->assertSame('014ec11e-4343-49cd-9b7a-cdd4ced5cedc', $messageId->toString());
    }

    public function test_that_timestamp_returns_expected_instance()
    {
        $timestamp = $this->commandMessage->timestamp();
        $this->assertSame('2015-01-01T13:12:31.045234[America/Chicago]', $timestamp->toString());
    }

    public function test_that_payload_returns_expected_instance()
    {
        $payload = $this->commandMessage->payload();
        $this->assertTrue($payload->foo() === 'foo' && $payload->bar() === 'bar');
    }

    public function test_that_payload_type_returns_expected_instance()
    {
        $payloadType = $this->commandMessage->payloadType();
        $expected = 'Novuso.Test.Common.Doubles.Domain.Messaging.Command.MakeThingCommand';
        $this->assertSame($expected, $payloadType->toString());
    }

    public function test_that_meta_data_returns_expected_instance()
    {
        $metaData = $this->commandMessage->metaData();
        $this->assertTrue($metaData->isEmpty());
    }

    public function test_that_with_meta_data_returns_equal_instance_with_new_meta_data()
    {
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $commandMessage = $this->commandMessage->withMetaData($metaData);
        $this->assertTrue(
            $commandMessage->metaData()->get('ip_address') === '127.0.0.1'
            && $this->commandMessage->equals($commandMessage)
        );
    }

    public function test_that_merge_meta_data_returns_equal_instance_with_merged_meta_data()
    {
        $metaData = new MetaData(['ip_address' => '127.0.0.1']);
        $commandMessage = $this->commandMessage->withMetaData($metaData);
        $metaData = new MetaData(['format' => 'json']);
        $commandMessage = $commandMessage->mergeMetaData($metaData);
        $this->assertTrue(
            $commandMessage->metaData()->get('ip_address') === '127.0.0.1'
            && $commandMessage->metaData()->get('format') === 'json'
            && $this->commandMessage->equals($commandMessage)
        );
    }

    public function test_that_to_string_returns_expected_value()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, $this->commandMessage->toString());
    }

    public function test_that_string_cast_returns_expected_value()
    {
        $expected = $this->getToStringJson();
        $this->assertSame($expected, (string) $this->commandMessage);
    }

    public function test_that_compare_to_returns_zero_for_same_instance()
    {
        $this->assertSame(0, $this->commandMessage->compareTo($this->commandMessage));
    }

    public function test_that_compare_to_returns_zero_for_same_value()
    {
        $serializer = new JsonSerializer();
        $commandMessage = $serializer->deserialize($serializer->serialize($this->commandMessage));
        $this->assertSame(0, $this->commandMessage->compareTo($commandMessage));
    }

    public function test_that_compare_to_returns_one_for_greater_instance()
    {
        $next = $this->getNextCommand();
        $this->assertSame(1, $next->compareTo($this->commandMessage));
    }

    public function test_that_compare_to_returns_neg_one_for_lesser_instance()
    {
        $next = $this->getNextCommand();
        $this->assertSame(-1, $this->commandMessage->compareTo($next));
    }

    public function test_that_equals_returns_true_for_same_instance()
    {
        $this->assertTrue($this->commandMessage->equals($this->commandMessage));
    }

    public function test_that_equals_returns_true_for_same_value()
    {
        $serializer = new JsonSerializer();
        $commandMessage = $serializer->deserialize($serializer->serialize($this->commandMessage));
        $this->assertTrue($this->commandMessage->equals($commandMessage));
    }

    public function test_that_equals_returns_false_for_invalid_value()
    {
        $this->assertFalse($this->commandMessage->equals('014ec11e434349cd9b7acdd4ced5cedc'));
    }

    public function test_that_equals_returns_false_for_unequal_value()
    {
        $next = $this->getNextCommand();
        $this->assertFalse($this->commandMessage->equals($next));
    }

    public function test_that_hash_value_returns_expected_string()
    {
        $this->assertSame('014ec11e434349cd9b7acdd4ced5cedc', $this->commandMessage->hashValue());
    }

    protected function getNextCommand()
    {
        $messageId = MessageId::fromString('014ec11f-317e-475a-9d5f-b8ae6c20aab1');
        $timestamp = DateTime::fromString('2015-01-02T10:34:12.672291[America/Chicago]');
        $metaData = new MetaData();
        $payload = new MakeThingCommand('foo', 'bar');

        return new CommandMessage(
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
            .'"command_type":"Novuso.Test.Common.Doubles.Domain.Messaging.Command.MakeThingCommand",'
            .'"command_data":{"foo":"foo","bar":"bar"},"meta_data":[]}';
    }
}
