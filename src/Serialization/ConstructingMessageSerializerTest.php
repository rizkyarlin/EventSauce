<?php

namespace EventSauce\EventSourcing\Serialization;

use EventSauce\EventSourcing\Message;
use EventSauce\EventSourcing\Time\TestClock;
use EventSauce\EventSourcing\UuidAggregateRootId;
use function iterator_to_array;
use PHPStan\Testing\TestCase;

class ConstructingMessageSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function serializing_messages_with_aggregate_root_ids()
    {
        $aggregateRootId = UuidAggregateRootId::create();
        $timeOfRecording = (new TestClock())->pointInTime();
        $message = new Message(new EventStub($timeOfRecording, 'original value'), [
            'aggregate_root_id' => $aggregateRootId,
        ]);
        $serializer = new ConstructingMessageSerializer(UuidAggregateRootId::class);
        $serialized = $serializer->serializeMessage($message);
        $deserializedMessage = iterator_to_array($serializer->unserializePayload($serialized))[0];
        $this->assertEquals($message, $deserializedMessage);
    }
}