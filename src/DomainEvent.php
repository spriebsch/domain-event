<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

interface DomainEvent extends \JsonSerializable
{
    /** @return array<string, mixed> */
    public function asArray(): array;

    /** @return array<string, mixed> */
    public function jsonSerialize(): array;

    public static function fromJson(string $json): static;
}
