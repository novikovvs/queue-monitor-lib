<?php

namespace napopravku\QueueMonitor\Validators\Checks;

use napopravku\QueueMonitor\Services\ClassUses;
use napopravku\QueueMonitor\Traits\IsMonitored;
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