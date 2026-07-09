<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(JsonDomainEventSerializer::class)]
#[CoversClass(JsonDomainEventDeserializer::class)]
#[UsesClass(AbstractId::class)]
final class JsonSerdeTest extends TestCase
{
    public function test_serializes_domain_event_to_json(): void
    {
        $serializer = new JsonDomainEventSerializer();
        $id = TestId::generate();
        $event = new EventA($id);

        $json = $serializer->serialize($event);

        $this->assertIsString($json);
        $this->assertStringContainsString($id->asString(), $json);
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
        $json = '{"id":{"uuid":{"value":"00000000-0000-4000-8000-000000000001","type":"uuidv4"}}}';

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Deserialized object is not a domain event');

        // We use a class that is NOT a DomainEvent but is an object
        // TestId is an object but doesn't implement DomainEvent
        $deserializer->deserialize($json, TestId::class);
    }
}
