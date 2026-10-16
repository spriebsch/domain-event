<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SharedSerde::class)]
final class SharedSerdeTest extends TestCase
{
    public function test_returns_the_same_instance_on_every_call(): void
    {
        $this->assertSame(SharedSerde::instance(), SharedSerde::instance());
    }
}
