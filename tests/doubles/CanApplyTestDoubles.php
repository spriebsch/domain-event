<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

final class TestAggregate
{
    use CanRecordDomainEventsTrait;

    public function recordEvent(DomainEvent $event): void
    {
        $this->record($event);
    }
}

final class EventWithoutApply implements DomainEvent
{
}

final class TestAggregateWithNoApply
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }
}

final class TestAggregateWithNoParams
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    private function applySimpleEvent(): void
    {
    }
}

final class TestAggregateWithTooManyParams
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    private function applySimpleEvent(SimpleEvent $event, SimpleEvent $other): void
    {
    }
}

final class TestAggregateWithNoType
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    /** @param mixed $event */
    private function applySimpleEvent($event): void
    {
    }
}

final class TestAggregateWithWrongType
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    private function applySimpleEvent(EventA $event): void
    {
    }
}

final class TestAggregateWithNonVoidReturn
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    private function applySimpleEvent(SimpleEvent $event): string
    {
        return 'foo';
    }
}

final class TestAggregateWithUnionType
{
    use CanApplyDomainEventsTrait;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    private function applySimpleEvent(SimpleEvent|EventA $event): void
    {
    }
}

final class TestAggregateValid
{
    use CanApplyDomainEventsTrait;

    private bool $applied = false;

    public function applyEvent(DomainEvent $event): void
    {
        $this->apply($event);
    }

    public function wasApplied(): bool
    {
        return $this->applied;
    }

    private function applySimpleEvent(SimpleEvent $event): void
    {
        $this->applied = true;
    }
}
