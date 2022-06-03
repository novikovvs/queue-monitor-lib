<?php

namespace napopravku\QueueMonitor\Validators\Checks;

interface Check
{
    public static function check($class);
}