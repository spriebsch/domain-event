<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(GenerateTopicMap::class)]
#[UsesClass(MapToTopic::class)]
final class GenerateTopicMapTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        $map = __DIR__ . '/../testdata/TopicMap.php';

        if (is_file($map)) {
            unlink($map);
        }
    }

    public static function tearDownAfterClass(): void
    {
        $map = __DIR__ . '/../testdata/TopicMap.php';

        if (is_file($map)) {
            // unlink($map);
        }
    }

    public function test_generate_topic_map(): void
    {
        $map = __DIR__ . '/../testdata/TopicMap.php';

        GenerateTopicMap::for(__DIR__ . '/../testdata');

        $result = require $map;

        $this->assertFileExists($map);

        $this->assertArrayHasKey('spriebsch.domainEvent.topicmap.a', $result);
        $this->assertSame(A::class, $result['spriebsch.domainEvent.topicmap.a']);

        $this->assertArrayHasKey('spriebsch.domainEvent.topicmap.b', $result);
        $this->assertSame(B::class, $result['spriebsch.domainEvent.topicmap.b']);

        $this->assertArrayHasKey('spriebsch.domainEvent.topicmap.c', $result);
        $this->assertSame(C::class, $result['spriebsch.domainEvent.topicmap.c']);
    }
}
