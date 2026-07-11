<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\Attributes\StaticTypeMap;
use spriebsch\uuid\UUID;
use spriebsch\uuid\UUIDv4;

readonly abstract class AbstractId implements UUID
{
    #[StaticTypeMap(key: 'type', map: ['uuidv4' => UUIDv4::class])]
    protected UUID $uuid; // See https://github.com/Crell/Serde/issues/91

    final public static function generate(): static
    {
        return new static(UUIDv4::generate());
    }

    final public static function from(string $uuid): static
    {
        return new static(UUIDv4::from($uuid));
    }

    final public static function fromUUID(UUID $uuid): static
    {
        return new static($uuid);
    }

    final private function __construct(UUID $uuid)
    {
        $this->uuid = $uuid;
    }

    public function asUUID(): UUID
    {
        return $this->uuid;
    }

    public function equals(?UUID $that): bool
    {
        if ($that === null) {
            return false;
        }

        return $this->asString() === $that->asString();
    }

    public function asString(): string
    {
        return $this->uuid->asString();
    }
}
