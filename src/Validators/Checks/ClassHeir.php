<?php

namespace Napopravku\QueueMonitor\Validators\Checks;

use Napopravku\QueueMonitor\Services\ClassUses;
use Napopravku\QueueMonitor\Traits\IsMonitored;
use Napopravku\Events\QueueableEvent;

class ClassHeir implements Check
{
    public static function check($class): bool
    {
        return array_key_exists(IsMonitored::class,
                ClassUses::classUsesRecursive(
                    $class
                ))
            || array_key_exists(QueueableEvent::class,
                class_implements($class)
            );
    }
}