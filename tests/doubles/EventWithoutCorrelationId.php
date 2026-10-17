<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.simple')]
final readonly class EventWithoutCorrelationId implements DomainEvent
{
    use EmptyJsonEventTrait;
}
