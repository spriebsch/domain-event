<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(MapToTopic::class)]
final class MapToTopicTest extends TestCase
{
    public function test_holds_topic(): void
    {
        $topic = 'some.topic.name';
        $attribute = new MapToTopic($topic);

        $this->assertSame($topic, $attribute->topic);
    }
}
