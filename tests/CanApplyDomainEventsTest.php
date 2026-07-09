<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use RuntimeException;

#[CoversClass(CanApplyDomainEventsTrait::class)]
#[UsesClass(SimpleEvent::class)]
#[UsesClass(EventA::class)]
final class CanApplyDomainEventsTest extends TestCase
{
    public function test_apply_throws_exception_when_method_does_not_exist(): void
    {
        $aggregate = new TestAggregateWithNoApply();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('No method spriebsch\DomainEvent\TestAggregateWithNoApply::applySimpleEvent(spriebsch\DomainEvent\SimpleEvent $event): void');

        $aggregate->applyEvent(new SimpleEvent());
    }

    public function test_apply_throws_exception_when_method_has_no_parameters(): void
    {
        $aggregate = new TestAggregateWithNoParams();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Method applySimpleEvent() has no parameters, expected spriebsch\DomainEvent\TestAggregateWithNoParams::applySimpleEvent(spriebsch\DomainEvent\SimpleEvent $event): void');

        $aggregate->applyEvent(new SimpleEvent());
    }

    public function test_apply_throws_exception_when_method_has_too_many_parameters(): void
    {
        $aggregate = new TestAggregateWithTooManyParams();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Method applySimpleEvent(...) has too many parameters, expected spriebsch\DomainEvent\TestAggregateWithTooManyParams::applySimpleEvent(spriebsch\DomainEvent\SimpleEvent $event): void');

        $aggregate->applyEvent(new SimpleEvent());
    }

    public function test_apply_throws_exception_when_parameter_has_no_type(): void
    {
        $aggregate = new TestAggregateWithNoType();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Method applySimpleEvent($event) parameter has no type, expected spriebsch\DomainEvent\TestAggregateWithNoType::applySimpleEvent(spriebsch\DomainEvent\SimpleEvent $event): void');

        $aggregate->applyEvent(new SimpleEvent());
    }

    public function test_apply_throws_exception_when_parameter_has_wrong_type(): void
    {
        $aggregate = new TestAggregateWithWrongType();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Method applySimpleEvent(spriebsch\DomainEvent\EventA $event) parameter type must be spriebsch\DomainEvent\TestAggregateWithWrongType::applySimpleEvent(spriebsch\DomainEvent\SimpleEvent $event): void');

        $aggregate->applyEvent(new SimpleEvent());
    }

    public function test_apply_throws_exception_when_method_has_non_void_return_type(): void
    {
        $aggregate = new TestAggregateWithNonVoidReturn();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Method spriebsch\DomainEvent\TestAggregateWithNonVoidReturn::applySimpleEvent() must have void return type');

        $aggregate->applyEvent(new SimpleEvent());
    }
}
