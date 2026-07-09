<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.twoCorrelationIds')]
final readonly class EventWithTwoCorrelationIds implements DomainEvent
{
    #[UseAsCorrelationId]
    #[UseAsCorrelationId]
    public function idA(): TestId
    {
        return TestId::generate();
    }
}
