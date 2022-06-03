<?php

namespace napopravku\QueueMonitor\Validators;

use napopravku\QueueMonitor\Validators\Checks\ClassHeir;
use napopravku\QueueMonitor\Validators\Checks\Check;

class ClassValidator
{
    const CHECKS = [
        ClassHeir::class
    ];

    private bool $result = true;

    public function validate($class): bool
    {
        /**
         * @var Check $check
         */
        foreach (self::CHECKS as $check) {
          $this->result &= $check::check($class);
        }
        return $this->result;
    }

}