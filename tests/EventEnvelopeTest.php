<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use spriebsch\timestamp\Timestamp;

#[CoversClass(Envelope::class)]
#[UsesClass(AbstractId::class)]
#[UsesClass(MapToTopic::class)]
#[UsesClass(Payload::class)]
#[UsesClass(Topic::class)]
#[UsesClass(SchemaVersion::class)]
#[UsesClass(JsonDomainEventDeserializer::class)]
#[UsesClass(JsonDomainEventSerializer::class)]
final class EventEnvelopeTest extends TestCase
{
    public function test_wraps_event(): void
    {
        $event = new SimpleEvent();
        $envelope = Envelope::from($event);

        $this->assertSame($event, $envelope->payload()->event());
    }

    public function test_has_EventId(): void
    {
        $envelope = Envelope::from(new SimpleEvent());

        $this->assertInstanceOf(EventId::class, $envelope->eventId());
    }

    public function test_has_Schema_Version(): void
    {
        $event = new SimpleEvent();
        $schemaVersion = SchemaVersion::from(3);

        $envelope = Envelope::from($event, null, $schemaVersion);

        $this->assertTrue($schemaVersion->equals($envelope->schemaVersion()));
    }

    public function test_Schema_Version_defaults_to_1(): void
    {
        $envelope = Envelope::from(new SimpleEvent());

        $this->assertSame(1, $envelope->schemaVersion()->asInt());
    }

    public function test_has_CorrelationId(): void
    {
        $id = TestId::generate();
        $event = new EventA($id);

        $envelope = Envelope::from($event);

        $this->assertTrue($event->id()->equals($envelope->correlationId()));
    }

    public function test_CorrelationId_is_optional(): void
    {
        $envelope = Envelope::from(new EventWithoutCorrelationId());

        $this->assertNull($envelope->correlationId());
    }

    public function test_has_CausationId(): void
    {
        $causationId = CausationId::generate();
        $envelope = Envelope::from(new SimpleEvent(), $causationId);

        $this->assertSame($causationId, $envelope->causationId());
    }

    public function test_CausationId_is_optional(): void
    {
        $event = new SimpleEvent();
        $envelope = Envelope::from($event);

        $this->assertNull($envelope->causationId());
    }

    public function test_a_new_envelope_is_reported_as_not_persisted(): void
    {
        $envelope = Envelope::from(new SimpleEvent());

        $this->assertFalse($envelope->isPersisted());
    }

    public function test_a_loaded_envelope_is_reported_as_persisted(): void
    {
        $topic = Topic::fromString('spriebsch.training.eventSourcing.created');
        $receivedAt = Timestamp::generate();
        $persistedAt = Timestamp::generate();
        $event = new SimpleEvent();

        $envelope = Envelope::from($event);

        $json = (new JsonDomainEventSerializer())->serialize($event);

        $loaded = Envelope::fromStorage(
            $envelope->eventId(),
            $receivedAt,
            $persistedAt,
            $json,
            SimpleEvent::class,
            $topic,
        );

        $this->assertTrue($loaded->isPersisted());
    }

    public function test_receivedAt_is_generated_on_envelope_creation(): void
    {
        $event = new SimpleEvent();
        $envelope = Envelope::from($event);

        $this->assertInstanceOf(Timestamp::class, $envelope->receivedAt());
    }

    public function test_persistedAt_is_null_by_default(): void
    {
        $envelope = Envelope::from(new SimpleEvent());

        $this->assertNull($envelope->persistedAt());
        $this->assertFalse($envelope->isPersisted());
    }

    public function test_persistedAt_is_returned_when_persisted(): void
    {
        $topic = Topic::fromString('spriebsch.domain.context.name');
        $receivedAt = Timestamp::generate();
        $persistedAt = Timestamp::generate();
        $event = new SimpleEvent();
        $json = (new JsonDomainEventSerializer())->serialize($event);

        $envelope = Envelope::fromStorage(
            EventId::generate(),
            $receivedAt,
            $persistedAt,
            $json,
            SimpleEvent::class,
            $topic
        );

        $this->assertSame($persistedAt, $envelope->persistedAt());
    }

    public function test_topic_returns_topic(): void
    {
        $event = new SimpleEvent();
        $envelope = Envelope::from($event);

        $this->assertSame('spriebsch.domainEvent.test.simple', $envelope->topic()->asString());
    }
}
