<?php declare(strict_types=1);

namespace spriebsch\DomainEvent;

use Crell\Serde\SerdeCommon;

/**
 * @internal
 */
final class SharedSerde
{
    private static ?SerdeCommon $instance = null;

    public static function instance(): SerdeCommon
    {
        return self::$instance ??= new SerdeCommon();
    }
}
