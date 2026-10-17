<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

#[MapToTopic('spriebsch.domainEvent.topicmap.c')]
final readonly class C implements DomainEvent
{
    use IdJsonEventTrait;

    public function __construct(
        private TestId $id
    ) {}

    public static function from(TestId $id): self
    {
        return new self($id);
    }

    #[UseAsCorrelationId]
    public function id(): TestId
    {
        return $this->id;
    }
}
