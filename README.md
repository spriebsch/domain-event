# DomainEvent

Domain Events, Done Right.

## Features

- **Structured Topics**: Define topics using a `vendor.domain.context.name` format.
- **Event Enveloping**: Wrap domain events in an `Envelope` that carries essential metadata:
    - `EventId` (UUIDv4) to uniquely identify the event
    - `Timestamp` (Received and Persisted)
    - `Topic`
    - optional `CausationId` and `CorrelationId` for tracing
    - an optional `SchemaVersion` to support event schema versioning
- **Serialization**: Built-in JSON serialization and deserialization (powered by `Crell/Serde`).
- **Event Sourcing Support**: Traits to easily implement event recording and applying in your code.
- **Attribute-based Configuration**: Use PHP 8 attributes to map events to topics and identify correlation IDs.
- **Topic Mapping**: Tools to generate and use a mapping between topics and PHP classes.

## Getting Started

### 1. Define a Domain Event

Implement the `DomainEvent` interface and use the `MapToTopic` attribute.

```php
use spriebsch\DomainEvent\DomainEvent;
use spriebsch\DomainEvent\MapToTopic;

#[MapToTopic('my-vendor.my-domain.my-context.something_happened')]
final readonly class SomethingHappened implements DomainEvent
{
    public function __construct(
        public string $data
    ) {}
}
```

### 2. Wrap the Event in an Envelope

The `Envelope` provides the metadata needed for handling the event.

```php
use spriebsch\DomainEvent\Envelope;

$event = new SomethingHappened('some data');
$envelope = Envelope::from($event);

echo $envelope->topic()->asString(); // my-vendor.my-domain.my-context.something_happened
echo $envelope->eventId()->asString(); // (a random UUIDv4)
```

### 3. Serialization

```php
use spriebsch\DomainEvent\JsonDomainEventSerializer;

$serializer = new JsonDomainEventSerializer();
$json = $serializer->serialize($event);
```

### 4. Event Sourcing

Use the provided traits in your code.

```php
use spriebsch\DomainEvent\CanRecordDomainEventsTrait;
use spriebsch\DomainEvent\CanApplyDomainEventsTrait;

final class MyDecision
{
    use CanRecordDomainEventsTrait;
    use CanApplyDomainEventsTrait;

    public function doSomething(string $data): void
    {
        $this->record(new SomethingHappened($data));
    }

    private function applySomethingHappened(SomethingHappened $event): void
    {
        // Update object's state
    }
}
```

## Installation

```bash
composer require spriebsch/domain-event
```

## Development

You can use the `php-devbox` container () to run tests and static analysis.

### Run Tests

```bash
php-devbox phpunit
```

### Static Analysis

```bash
php-devbox phpstan
```
