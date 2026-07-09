<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(CorrelationId::class)]
#[CoversClass(CausationId::class)]
#[UsesClass(AbstractId::class)]
final class IdTest extends TestCase
{
    public function test_correlation_id_can_be_generated(): void
    {
        $id = CorrelationId::generate();
        $this->assertInstanceOf(CorrelationId::class, $id);
    }

    public function test_causation_id_can_be_generated(): void
    {
        $id = CausationId::generate();
        $this->assertInstanceOf(CausationId::class, $id);
    }
}
