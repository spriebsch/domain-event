<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\UsesTrait;
use PHPUnit\Framework\TestCase;

#[CoversTrait(CanRecordDomainEventsTrait::class)]
#[CoversTrait(IsEventSourcedTrait::class)]
#[UsesTrait(CanApplyDomainEventsTrait::class)]
final class SourcingTraitTest extends TestCase
{
    public function test_can_record_domain_events(): void
    {
        $aggregate = new TestAggregateRecording();
        $event = new SimpleEvent();

        $aggregate->doSomething($event);

        $events = $aggregate->newEvents();
        $this->assertCount(1, $events);
        $this->assertSame($event, $events[0]);
        $this->assertTrue($aggregate->wasApplied());

        $this->assertCount(0, $aggregate->newEvents());
    }

    public function test_is_event_sourced(): void
    {
        $event1 = new SimpleEvent();
        $event2 = new SimpleEvent();

        $aggregate = TestAggregateSourced::sourceFrom($event1, $event2);

        $this->assertInstanceOf(TestAggregateSourced::class, $aggregate);
        $this->assertCount(2, $aggregate->appliedEvents());
        $this->assertSame($event1, $aggregate->appliedEvents()[0]);
        $this->assertSame($event2, $aggregate->appliedEvents()[1]);
    }
}

final class TestAggregateRecording
{
    use CanRecordDomainEventsTrait;

    private bool $applied = false;

    public function doSomething(DomainEvent $event): void
    {
        $this->record($event);
    }

    public function applySimpleEvent(SimpleEvent $event): void
    {
        $this->applied = true;
    }

    public function wasApplied(): bool
    {
        return $this->applied;
    }
}

final class TestAggregateSourced
{
    use IsEventSourcedTrait;

    /** @var array<int, DomainEvent> */
    private array $appliedEvents = [];

    public function __construct(DomainEvent ...$events)
    {
        // Parameter is used by trait which is not visible to PHPStan
        unset($events);
    }

    public function applySimpleEvent(SimpleEvent $event): void
    {
        $this->appliedEvents[] = $event;
    }

    /** @return array<int, DomainEvent> */
    public function appliedEvents(): array
    {
        return $this->appliedEvents;
    }
}
