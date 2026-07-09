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
        if (!is_array($result)) {
            $this->fail('Generated TopicMap.php must return an array');
        }

        $this->assertFileExists($map);

        $this->assertArrayHasKey('spriebsch.domainEvent.topicmap.a', $result);
        $this->assertSame(A::class, $result['spriebsch.domainEvent.topicmap.a']);

        $this->assertArrayHasKey('spriebsch.domainEvent.topicmap.b', $result);
        $this->assertSame(B::class, $result['spriebsch.domainEvent.topicmap.b']);

        $this->assertArrayHasKey('spriebsch.domainEvent.topicmap.c', $result);
        $this->assertSame(C::class, $result['spriebsch.domainEvent.topicmap.c']);
    }

    public function test_for_throws_exception_when_directory_does_not_exist(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Directory "/non/existent" does not exist');

        GenerateTopicMap::for('/non/existent');
    }

    public function test_for_skips_non_php_files_and_TopicMap(): void
    {
        $tempDir = sys_get_temp_dir() . '/topic_map_test_' . uniqid();
        mkdir($tempDir);
        mkdir($tempDir . '/sub');

        file_put_contents($tempDir . '/NotPHP.txt', '<?php class NotPHP {}');
        file_put_contents($tempDir . '/TopicMap.php', '<?php return [];');
        file_put_contents($tempDir . '/ValidEvent.php', '<?php
namespace spriebsch\DomainEvent\Temp;
use spriebsch\DomainEvent\DomainEvent;
use spriebsch\DomainEvent\MapToTopic;

#[MapToTopic("valid.event")]
final class ValidEvent implements DomainEvent {}');

        GenerateTopicMap::for($tempDir);

        $result = require $tempDir . '/TopicMap.php';
        if (!is_array($result)) {
            $this->fail('Generated TopicMap.php must return an array');
        }

        $this->assertArrayHasKey('valid.event', $result);
        $this->assertCount(1, $result);

        unlink($tempDir . '/NotPHP.txt');
        unlink($tempDir . '/TopicMap.php');
        unlink($tempDir . '/ValidEvent.php');
        rmdir($tempDir . '/sub');
        rmdir($tempDir);
    }
}
