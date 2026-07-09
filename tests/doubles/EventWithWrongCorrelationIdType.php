<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.wrongCorrelationIdType')]
final readonly class EventWithWrongCorrelationIdType implements DomainEvent
{
    #[UseAsCorrelationId]
    public function id(): string
    {
        return 'not-an-id';
    }
}
