<?php

namespace Napopravku\QueueMonitor\Validators\Checks;

interface Check
{
    public static function check($class);
}