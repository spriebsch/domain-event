<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.test.twoCorrelationIds')]
final readonly class EventWithTwoCorrelationIds implements DomainEvent
{
    #[UseAsCorrelationId]
    // @phpstan-ignore attribute.nonRepeatable (intentional invalid test fixture)
    #[UseAsCorrelationId]
    public function idA(): TestId
    {
        return TestId::generate();
    }
}
