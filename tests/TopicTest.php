<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Topic::class)]
#[UsesClass(AbstractId::class)]
#[UsesClass(MapToTopic::class)]
#[UsesClass(Payload::class)]
#[UsesClass(JsonDomainEventDeserializer::class)]
final class TopicTest extends TestCase
{
    public function test_has_vendor(): void
    {
        $topic = Topic::fromString('vendor.domain.context.name');

        $this->assertSame('vendor', $topic->vendor());
    }

    public function test_has_domain(): void
    {
        $topic = Topic::fromString('vendor.domain.context.name');

        $this->assertSame('domain', $topic->domain());
    }

    public function test_has_context(): void
    {
        $topic = Topic::fromString('vendor.domain.context.name');

        $this->assertSame('context', $topic->context());
    }

    public function test_has_name(): void
    {
        $topic = Topic::fromString('vendor.domain.context.name');

        $this->assertSame('name', $topic->name());
    }

    public function test_name_can_have_dots(): void
    {
        $topic = Topic::fromString('vendor.domain.context.name.with.dots');

        $this->assertSame('name.with.dots', $topic->name());
    }

    public function test_can_be_created_from_components(): void
    {
        $topic = Topic::fromComponents(
            'vendor',
            'domain',
            'context',
            'name.with.dots'
        );

        $this->assertEquals(
            Topic::fromString('vendor.domain.context.name.with.dots'),
            $topic
        );
    }

    public function test_can_be_compared(): void
    {
        $topic = Topic::fromComponents(
            'vendor',
            'domain',
            'context',
            'name.with.dots'
        );

        $this->assertTrue($topic->equals($topic));
        $this->assertFalse($topic->equals(Topic::fromString('vendor.domain.context.other')));
    }
}
