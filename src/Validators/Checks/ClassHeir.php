<?php

namespace Novikovvs\QueueMonitor\Validators\Checks;

use Novikovvs\QueueMonitor\Services\ClassUses;
use Novikovvs\QueueMonitor\Traits\IsMonitored;
use Novikovvs\Events\QueueableEvent;

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
