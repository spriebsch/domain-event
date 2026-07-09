<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use ReflectionClass;
use RuntimeException;

final readonly class GenerateTopicMap
{
    public static function for(string $directory): void
    {
        if (!is_dir($directory)) {
            throw new RuntimeException(
                sprintf('Directory "%s" does not exist', $directory)
            );
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            /** @var \SplFileInfo $file */
            if ($file->isDir()) {
                continue;
            }

            if (!str_ends_with($file->getPathname(), '.php')) {
                continue;
            }

            if (($file->getFilename() === 'TopicMap.php')) {
                continue;
            }

            require_once $file->getPathname();
        }

        $allClasses = get_declared_classes();

        $result = [];

        foreach ($allClasses as $class) {
            $reflection = new ReflectionClass($class);
            $fileName = $reflection->getFileName();
            $directoryPath = realpath($directory);
            if ($fileName === false || $directoryPath === false || !str_starts_with($fileName, $directoryPath)) {
                continue;
            }

            $interfaces = class_implements($class);

            if (in_array(DomainEvent::class, $interfaces)) {
                $attributes = new ReflectionClass($class)->getAttributes(MapToTopic::class);

                if ($attributes !== []) {
                    $result[$attributes[0]->newInstance()->topic] = $class;
                }
            }
        }

        file_put_contents(
            $directory . '/TopicMap.php',
            '<?php return ' . var_export($result, true) . ';'
        );
    }
}
