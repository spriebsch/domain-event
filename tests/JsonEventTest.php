<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonDomainEventSerializer::class)]
#[CoversClass(JsonDomainEventDeserializer::class)]
#[UsesClass(AbstractId::class)]
#[UsesTrait(JsonDomainEventTrait::class)]
final class JsonEventTest extends TestCase
{
    public function test_serializes_domain_event_to_json(): void
    {
        $serializer = new JsonDomainEventSerializer();
        $id = TestId::generate();
        $event = new EventA($id);

        $json = $serializer->serialize($event);

        $this->assertSame(['id' => $id->asString()], json_decode($json, true, 512, JSON_THROW_ON_ERROR));
        $this->assertSame($json, json_encode($event, JSON_THROW_ON_ERROR));
    }

    public function test_deserializes_domain_event_from_json(): void
    {
        $serializer = new JsonDomainEventSerializer();
        $id = TestId::generate();
        $event = new EventA($id);
        $json = $serializer->serialize($event);

        $deserializer = new JsonDomainEventDeserializer();
        $recreated = $deserializer->deserialize($json, EventA::class);

        $this->assertInstanceOf(EventA::class, $recreated);
        $this->assertSame($id->asString(), $recreated->id()->asString());
    }

    public function test_deserialize_throws_exception_when_not_domain_event(): void
    {
        $deserializer = new JsonDomainEventDeserializer();
        $json = '{"id":"00000000-0000-4000-8000-000000000001"}';

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Class must implement DomainEvent');

        // We use a class that is NOT a DomainEvent but is an object
        // TestId is an object but doesn't implement DomainEvent
        /** @var mixed $class */
        $class = TestId::class;
        /** @var class-string<DomainEvent> $class */
        $deserializer->deserialize($json, $class);
    }

    public function test_rejects_malformed_json(): void
    {
        $this->expectException(\JsonException::class);

        new JsonDomainEventDeserializer()->deserialize('{', EventA::class);
    }

    public function test_rejects_invalid_id(): void
    {
        $this->expectException(\spriebsch\uuid\UUIDException::class);

        new JsonDomainEventDeserializer()->deserialize('{"id":"not-a-uuid"}', EventA::class);
    }

    public function test_rejects_missing_id(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a string id');

        new JsonDomainEventDeserializer()->deserialize('{}', EventA::class);
    }
}
