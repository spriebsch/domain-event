<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Envelope::class)]
#[UsesClass(AbstractId::class)]
#[UsesClass(MapToTopic::class)]
#[UsesClass(Payload::class)]
#[UsesClass(Topic::class)]
#[UsesClass(JsonDomainEventDeserializer::class)]
final class EnvelopeErrorTest extends TestCase
{
    public function test_creating_envelope_without_topic_attribute_throws(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Event has no topic attribute');

        Envelope::from(new EventWithoutTopic());
    }

    public function test_correlationId_throws_exception_when_method_has_multiple_attributes(): void
    {
        $envelope = Envelope::from(new EventWithTwoCorrelationIds());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Method has more than one UseAsCorrelationId attribute');

        $envelope->correlationId();
    }
}
