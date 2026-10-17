# Upgrading to 3.0

Serde has been removed. Every class implementing `DomainEvent` must now implement
`asArray(): array` and `static fromJson(string $json): static`, and provide
`jsonSerialize(): array` either directly or with `JsonDomainEventTrait`.

`asArray()` defines the event's JSON payload. `fromJson()` should decode with
`JSON_THROW_ON_ERROR`, validate its fields, and call the event constructor and
the constructors or factories of nested value objects. The public JSON serializer,
deserializer, and `Envelope` methods retain their signatures.

The payload format can change. JSON written by earlier versions is not guaranteed
to be readable by 3.0, and this library does not migrate stored events.
