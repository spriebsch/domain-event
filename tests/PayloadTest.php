<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use spriebsch\timestamp\Timestamp;

#[CoversClass(Payload::class)]
#[UsesClass(AbstractId::class)]
#[UsesClass(MapToTopic::class)]
#[UsesClass(Topic::class)]
#[UsesClass(Envelope::class)]
#[UsesClass(JsonDomainEventDeserializer::class)]
#[UsesClass(JsonDomainEventSerializer::class)]
final class PayloadTest extends TestCase
{
    public function test_can_be_serialized_and_unserialized(): void
    {
        $id = TestId::generate();

        $event = new ComplexEvent(
            $id,
            true,
            'the-string',
            42,
            3.14,
            ['a', 'b', 'c'],
            SomeEnum::A,
            SomeBackedEnum::B,
            new SomeValueObject(
                true,
                'the-string',
                42,
                3.14,
                ['a', 'b', 'c'],
                SomeEnum::A,
                SomeBackedEnum::B,
                new NestedValueObject('the-string')
            ),
            new Nullable(
                null,
                null,
                null,
                null,
                null,
                null,
                null,
            ),
            new ImplementsInterface('the-string'),
            new SecondInterfaceImplementation(42),
        );

        $envelope = Envelope::from($event);
        $payload = $envelope->payload();

        $json = (new JsonDomainEventSerializer())->serialize($event);
        $persistedAt = Timestamp::generate();

        $recreated = Envelope::fromStorage(
            $envelope->eventId(),
            $envelope->receivedAt(),
            $persistedAt,
            $json,
            ComplexEvent::class,
            $envelope->topic()
        );

        $this->assertEquals($event, $recreated->payload()->event());
    }

    public function test_envelope(): void
    {
        $event = new SimpleEvent();
        $envelope = Envelope::from($event);
        $payload = $envelope->payload();

        $this->assertSame($envelope, $payload->envelope());
    }
}
